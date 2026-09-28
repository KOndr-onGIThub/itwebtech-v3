#!/usr/bin/env bash
#
# OND-459: nasazení produkce na hosting Webglobe. Spouští ho GitHub Actions
# (.github/workflows/deploy-production.yml) přes SSH, a to z adresáře nového
# release, který tam workflow právě nahrál. Návod pro lidi:
# docs/deploy-production.md.
#
#   remote-deploy.sh deploy     připraví nový release a přepne na něj web
#   remote-deploy.sh rollback   přepne web o jeden release zpět
#
# Rozložení na serveru (vše v domovském adresáři SSH účtu):
#
#   $APP_DIR/releases/<id>/   jednotlivá nasazení (build z Actions)
#   $APP_DIR/shared/.env      produkční konfigurace, sdílená všemi release
#   $APP_DIR/shared/storage/  nahrané soubory, logy, cache, sdílené
#   $APP_DIR/current          symlink na aktivní release
#   $WEB_ROOT                 symlink na $APP_DIR/current/public (web root hostingu)
#
# Pořadí je schválně: migrace, seedery, optimize a zkouška stránek proběhnou
# v novém release ještě PŘED přepnutím. Když cokoli selže, skript skončí
# chybou, `current` se nepřepne a návštěvníci dál vidí předchozí verzi.
#
# Pozor na hardlinky: rsync --link-dest sdílí nezměněné soubory mezi release.
# Soubor v release se proto nikdy nesmí přepsat na místě (`> soubor`,
# `sed -i` bez nového inode), změnil by i předchozí verze. Mazat (`rm`)
# a zakládat nové soubory je v pořádku.
#
# Pravidla z docker/entrypoint.d/50-laravel-deploy.sh platí i tady: jen
# `migrate --force` a tři vyjmenované seedery. Nikdy `db:seed` bez --class
# (přepsal by portfolio z Filamentu) ani migrate:fresh/refresh.

set -euo pipefail

MODE="${1:-deploy}"
APP_DIR="${APP_DIR:-ondraweb}"
WEB_ROOT="${WEB_ROOT:-www}"
PHP_BIN="${PHP_BIN:-php8.4}"
KEEP_RELEASES="${KEEP_RELEASES:-5}"

cd "$HOME"
case "$APP_DIR" in /*) BASE="$APP_DIR" ;; *) BASE="$HOME/$APP_DIR" ;; esac
case "$WEB_ROOT" in /*) WEB="$WEB_ROOT" ;; *) WEB="$HOME/$WEB_ROOT" ;; esac

step() { printf '\n==> %s\n' "$*"; }
fail() {
    # `::error::` zobrazí GitHub Actions jako červenou hlášku v přehledu běhu.
    printf '::error::%s\n' "$*" >&2
    exit 1
}

# Přepne symlink atomicky: nový odkaz vznikne vedle a `mv -T` ho přejmenuje
# přes starý. Web tak nikdy nevidí chvíli bez odkazu.
switch_link() {
    local target="$1" link="$2"
    ln -sfn "$target" "$link.tmp-$$"
    mv -Tf "$link.tmp-$$" "$link"
}

# Seznam release od nejstaršího po nejnovější (id začíná UTC časem).
list_releases() {
    find "$BASE/releases" -mindepth 1 -maxdepth 1 -type d -printf '%f\n' | sort
}

# Jen release, které někdy opravdu běžely (mají značku `.deployed`). Nasazení,
# které selhalo před přepnutím, v releases/ zůstane, ale vracet se na něj nesmí.
list_deployed_releases() {
    local r
    list_releases | while read -r r; do
        if [ -f "$BASE/releases/$r/.deployed" ]; then echo "$r"; fi
    done
}

rollback() {
    [ -L "$BASE/current" ] || fail "Není co vracet: $BASE/current neexistuje."
    local active previous
    active="$(basename "$(readlink "$BASE/current")")"
    previous="$(list_deployed_releases | awk -v a="$active" '$0 == a { print prev; exit } { prev = $0 }')"
    [ -n "$previous" ] || fail "Před release $active už žádný starší není, není kam se vrátit."

    step "Návrat z $active na $previous"
    switch_link "releases/$previous" "$BASE/current"
    echo "Web teď běží z release $previous. Migrace databáze se NEVRACEJÍ."
}

deploy() {
    : "${RELEASE:?RELEASE musí být nastavené (id adresáře v releases/)}"
    local rel="$BASE/releases/$RELEASE"
    [ -d "$rel" ] || fail "Release $rel neexistuje, nahrání z Actions neproběhlo."

    step "Kontrola PHP ($PHP_BIN)"
    command -v "$PHP_BIN" >/dev/null 2>&1 \
        || fail "Na serveru chybí příkaz $PHP_BIN. Nastav v administraci Webglobe PHP 8.4, nebo uprav proměnnou PHP_BIN ve workflow."
    "$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.4.0", ">=") ? 0 : 1);' \
        || fail "$PHP_BIN je verze $("$PHP_BIN" -r 'echo PHP_VERSION;'), aplikace potřebuje PHP 8.4."
    local missing
    # shellcheck disable=SC2016 # PHP kód, $need a $e patří PHP, ne shellu
    missing="$("$PHP_BIN" -r '
        $need = ["pdo_mysql", "mbstring", "intl", "gd", "zip", "bcmath", "exif", "fileinfo",
                 "openssl", "tokenizer", "xml", "dom", "ctype", "curl", "iconv"];
        echo implode(" ", array_filter($need, fn ($e) => ! extension_loaded($e)));
    ')"
    [ -z "$missing" ] || fail "PHP na serveru nemá rozšíření: $missing"
    "$PHP_BIN" -v | head -1

    step "Sdílené soubory ($BASE/shared)"
    mkdir -p "$BASE/shared/storage/app/public" \
             "$BASE/shared/storage/app/private" \
             "$BASE/shared/storage/framework/cache/data" \
             "$BASE/shared/storage/framework/sessions" \
             "$BASE/shared/storage/framework/views" \
             "$BASE/shared/storage/logs"
    [ -s "$BASE/shared/.env" ] \
        || fail "Chybí $BASE/shared/.env. Vyplň GitHub Secret PRODUCTION_ENV (viz docs/deploy-production.md) a spusť nasazení znovu."
    grep -q '^APP_KEY=base64:' "$BASE/shared/.env" \
        || fail "V .env chybí APP_KEY. Musí být stejný jako na Coolify, jinak přestane fungovat dvoufázové přihlášení do administrace."

    step "Propojení release $RELEASE se sdílenými soubory"
    rm -rf "$rel/storage" "$rel/.env" "$rel/public/storage" "$rel/public/hot"
    ln -s "$BASE/shared/storage" "$rel/storage"
    ln -s "$BASE/shared/.env" "$rel/.env"
    mkdir -p "$rel/bootstrap/cache"
    # Cache konfigurace a rout z buildu by nesla cesty runneru. packages.php
    # a services.php z `composer install --no-dev` jsou v pořádku, zůstávají.
    rm -f "$rel/bootstrap/cache/config.php" "$rel/bootstrap/cache/routes-v7.php" "$rel/bootstrap/cache/events.php"

    cd "$rel"
    local artisan=("$PHP_BIN" artisan --no-interaction)

    step "Migrace databáze"
    "${artisan[@]}" migrate --force || fail "Migrace selhaly. Web se nepřepnul a běží předchozí verze."

    step "Seedery (idempotentní, stejné jako na Coolify)"
    local seeder
    for seeder in AdminUserSeeder EnsureArticlesSeededSeeder EnsurePortfolioSeededSeeder; do
        echo "-- $seeder"
        "${artisan[@]}" db:seed --class="Database\\Seeders\\$seeder" --force \
            || fail "Seeder $seeder selhal. Web se nepřepnul a běží předchozí verze."
    done

    step "storage:link a optimize"
    "${artisan[@]}" storage:link
    "${artisan[@]}" optimize || fail "artisan optimize selhal. Web se nepřepnul a běží předchozí verze."

    step "Zkouška stránek nového release (před přepnutím)"
    "$PHP_BIN" scripts/deploy/smoke.php "$rel" \
        || fail "Nový release nevrací stránky. Web se nepřepnul a běží předchozí verze. Detail v $BASE/shared/storage/logs/."

    step "Přepnutí webu na $RELEASE"
    touch "$rel/.deployed"
    switch_link "releases/$RELEASE" "$BASE/current"

    # Web root hostingu (`www`) musí ukazovat na current/public. Při prvním
    # nasazení je to ještě skutečný adresář se starým webem itwebtech.cz. Ten
    # se jen přejmenuje (nic se nemaže), takže jde kdykoli vrátit.
    if [ -L "$WEB" ]; then
        if [ "$(readlink "$WEB")" != "$BASE/current/public" ]; then
            switch_link "$BASE/current/public" "$WEB"
        fi
    else
        if [ -e "$WEB" ]; then
            local backup
            backup="$WEB.pred-ondraweb-$(date -u +%Y%m%d%H%M%S)"
            mv "$WEB" "$backup" || fail "Nejde přejmenovat $WEB. Web root nastav ručně podle docs/deploy-production.md."
            echo "Starý obsah $WEB je zachovaný v $backup."
        fi
        ln -s "$BASE/current/public" "$WEB" || fail "Nejde vytvořit symlink $WEB. Web root nastav ručně podle docs/deploy-production.md."
    fi
    echo "$WEB -> $(readlink "$WEB")"
    echo "$BASE/current -> $(readlink "$BASE/current")"

    step "Úklid starých release (ponechávám posledních $KEEP_RELEASES)"
    local active old
    active="$(basename "$(readlink "$BASE/current")")"
    list_releases | head -n "-$KEEP_RELEASES" | while read -r old; do
        [ "$old" = "$active" ] && continue
        echo "mažu releases/$old"
        rm -rf "${BASE:?}/releases/$old"
    done

    step "Hotovo: web běží z release $RELEASE"
}

case "$MODE" in
    deploy) deploy ;;
    rollback) rollback ;;
    *) fail "Neznámý režim '$MODE' (deploy | rollback)." ;;
esac
