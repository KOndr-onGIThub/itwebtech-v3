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
# Rozložení na serveru:
#
#   $APP_DIR/releases/<id>/        jednotlivá nasazení (build z Actions)
#   $APP_DIR/shared/.env           produkční konfigurace, sdílená všemi release
#   $APP_DIR/shared/storage/       nahrané soubory, logy, cache, sdílené
#   $APP_DIR/shared/deploy-hook/   jednorázové soubory pro kroky s databází
#   $APP_DIR/current               symlink na aktivní release
#   $WEB_ROOT                      symlink na $APP_DIR/current/public (web root domény)
#
# Databáze je na Webglobe dosažitelná jen z PHP webu, ne ze SSH (OND-461,
# ověřeno 29. 9.). Migrace, seedery a zkouška stránek proto běží přes web:
# skript nahraje do shared/deploy-hook jednorázový PHP soubor s náhodným
# jménem a tokenem, zavolá ho curlem a hned ho smaže. Soubor nabootuje NOVÝ
# release (scripts/deploy/web-hook.php). Přes SSH běží jen to, co databázi
# nepotřebuje: kontrola PHP, propojení storage a .env, storage:link, optimize.
#
# Pořadí je schválně: všechno proběhne v novém release ještě PŘED přepnutím.
# Když cokoli selže, skript skončí chybou, `current` se nepřepne a návštěvníci
# dál vidí předchozí verzi.
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
APP_DIR="${APP_DIR:-/home/html/ondraweb.cz/app}"
WEB_ROOT="${WEB_ROOT:-/home/html/ondraweb.cz/public_html}"
WEB_URL="${WEB_URL:-https://ondraweb.cz}"
PHP_BIN="${PHP_BIN:-php8.4}"
KEEP_RELEASES="${KEEP_RELEASES:-5}"

cd "$HOME"
case "$APP_DIR" in /*) BASE="$APP_DIR" ;; *) BASE="$HOME/$APP_DIR" ;; esac
case "$WEB_ROOT" in /*) WEB="$WEB_ROOT" ;; *) WEB="$HOME/$WEB_ROOT" ;; esac
WEB_URL="${WEB_URL%/}"

# Jednorázový soubor pro web se smaže vždy, i když nasazení spadne.
HOOK_FILE=""
trap '[ -z "$HOOK_FILE" ] || rm -f "$HOOK_FILE"' EXIT

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

# Migrace, seedery a zkouška stránek v PHP webu (tam, kde je vidět databáze).
run_web_hook() {
    local rel="$1"
    local hook_dir="$BASE/shared/deploy-hook"

    # Cesta se vkládá do PHP souboru, proto jen bezpečné znaky.
    [[ "$rel" =~ ^[A-Za-z0-9._/-]+$ ]] || fail "Cesta $rel obsahuje nečekané znaky, nasazení přes web ji neumí."

    mkdir -p "$hook_dir"
    chmod 711 "$hook_dir"
    [ -e "$hook_dir/index.html" ] || : > "$hook_dir/index.html"

    # Adresář musí být vidět z webu pod /_deploy/. Nový release ho dostane
    # rovnou. Web ale teď obsluhuje předchozí release (nebo při prvním
    # nasazení původní obsah web rootu), proto odkaz přidáme i tam.
    ln -sfn "$hook_dir" "$rel/public/_deploy"
    local served
    served="$(readlink -f "$WEB" 2>/dev/null || true)"
    if [ -z "$served" ] || [ ! -d "$served" ]; then
        fail "Web root $WEB neexistuje. Zkontroluj proměnnou WEBGLOBE_WEB_ROOT (docs/deploy-production.md)."
    fi
    if [ ! -e "$served/_deploy" ]; then
        ln -s "$hook_dir" "$served/_deploy" \
            || fail "Do web rootu $served nejde přidat odkaz _deploy. Nasazení přes web nemá kde běžet."
    fi

    local name token
    name="$("$PHP_BIN" -r 'echo bin2hex(random_bytes(24));')"
    token="$("$PHP_BIN" -r 'echo bin2hex(random_bytes(32));')"
    HOOK_FILE="$hook_dir/$name.php"

    # Tenhle soubor musí jít spustit i na starém PHP 7.4, aby web na špatné
    # verzi vrátil srozumitelnou hlášku místo chyby syntaxe.
    (umask 022 && cat > "$HOOK_FILE") <<PHP
<?php
// OND-461: jednorázový soubor z scripts/deploy/remote-deploy.sh, po použití se maže.
if (!isset(\$_SERVER['HTTP_X_DEPLOY_TOKEN']) || !hash_equals('$token', (string) \$_SERVER['HTTP_X_DEPLOY_TOKEN'])) {
    http_response_code(404);
    exit;
}
@unlink(__FILE__);
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');
if (PHP_VERSION_ID < 80400) {
    echo "CHYBA: web běží na PHP " . PHP_VERSION . ", aplikace potřebuje PHP 8.4.\n";
    echo "Přepni PHP v administraci Webglobe: Hosting → Web → PHP nastavení → 8.4. Pak nasazení spusť znovu.\n";
    exit;
}
\$release = '$rel';
if (!is_file(\$release . '/scripts/deploy/web-hook.php')) {
    echo "CHYBA: web nevidí nový release na cestě \$release.\n";
    echo "Web (PHP-FPM) a SSH vidí soubory pod jinými cestami. Uprav WEBGLOBE_APP_DIR na cestu, kterou vidí web.\n";
    exit;
}
require \$release . '/scripts/deploy/web-hook.php';
PHP

    local url="$WEB_URL/_deploy/$name.php" log code
    log="$(mktemp)"
    echo "Volám $WEB_URL/_deploy/<jednorázový soubor>.php"
    # Bez -L: přesměrování by poslalo token jinam.
    code="$(curl -sS --max-time 900 -H "X-Deploy-Token: $token" -o "$log" -w '%{http_code}' "$url" || true)"
    cat "$log"
    rm -f "$HOOK_FILE"
    HOOK_FILE=""

    if grep -qx 'DEPLOY_HOOK_OK' "$log"; then
        rm -f "$log"
        return 0
    fi
    rm -f "$log"
    case "$code" in
        200) fail "Migrace, seedery nebo zkouška stránek přes web selhaly (výpis výše). Web se nepřepnul a běží předchozí verze." ;;
        000) fail "Server se nedovolal na $WEB_URL (curl bez odpovědi). Web se nepřepnul. Zkontroluj, že doména míří na tento hosting, nebo nastav proměnnou WEBGLOBE_WEB_URL." ;;
        3*) fail "$WEB_URL přesměrovává (HTTP $code), nasazení přes web potřebuje adresu, která odpoví přímo. Nastav proměnnou WEBGLOBE_WEB_URL. Web se nepřepnul." ;;
        404) fail "Web na $WEB_URL nenašel jednorázový soubor (HTTP 404). Doména neobsluhuje web root $WEB, nebo nesleduje odkaz _deploy. Web se nepřepnul." ;;
        *) fail "Nasazení přes web vrátilo HTTP $code (výpis výše). Web se nepřepnul a běží předchozí verze." ;;
    esac
}

deploy() {
    : "${RELEASE:?RELEASE musí být nastavené (id adresáře v releases/)}"
    local rel="$BASE/releases/$RELEASE"
    [ -d "$rel" ] || fail "Release $rel neexistuje, nahrání z Actions neproběhlo."

    step "Kontrola PHP pro příkazy přes SSH ($PHP_BIN)"
    command -v "$PHP_BIN" >/dev/null 2>&1 \
        || fail "Na serveru chybí příkaz $PHP_BIN. Uprav proměnnou WEBGLOBE_PHP_BIN ve workflow."
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
    command -v curl >/dev/null 2>&1 || fail "Na serveru chybí curl, nasazení přes web ho potřebuje."

    step "Sdílené soubory ($BASE/shared)"
    mkdir -p "$BASE/shared/storage/app/public" \
             "$BASE/shared/storage/app/private" \
             "$BASE/shared/storage/framework/cache/data" \
             "$BASE/shared/storage/framework/sessions" \
             "$BASE/shared/storage/framework/views" \
             "$BASE/shared/storage/logs"
    # Webserver může běžet pod jiným uživatelem než SSH, musí adresáři projít.
    chmod 755 "$BASE" "$BASE/shared" "$BASE/releases"
    [ -s "$BASE/shared/.env" ] \
        || fail "Chybí $BASE/shared/.env. Vyplň GitHub Secret PRODUCTION_ENV (viz docs/deploy-production.md) a spusť nasazení znovu."
    grep -q '^APP_KEY=base64:' "$BASE/shared/.env" \
        || fail "V .env chybí APP_KEY. Musí být stejný jako na Coolify, jinak přestane fungovat dvoufázové přihlášení do administrace."

    step "Propojení release $RELEASE se sdílenými soubory"
    rm -rf "$rel/storage" "$rel/.env" "$rel/public/storage" "$rel/public/hot" "$rel/public/_deploy"
    ln -s "$BASE/shared/storage" "$rel/storage"
    ln -s "$BASE/shared/.env" "$rel/.env"
    mkdir -p "$rel/bootstrap/cache"
    # Cache konfigurace a rout z buildu by nesla cesty runneru. packages.php
    # a services.php z `composer install --no-dev` jsou v pořádku, zůstávají.
    rm -f "$rel/bootstrap/cache/config.php" "$rel/bootstrap/cache/routes-v7.php" "$rel/bootstrap/cache/events.php"

    cd "$rel"
    local artisan=("$PHP_BIN" artisan --no-interaction)

    # Databázi nepotřebují. config:cache uloží absolutní cesty ze SSH, web je
    # vidí stejně (/home/html/ondraweb.cz/…), web-hook.php to hned ověří.
    step "storage:link a optimize (přes SSH)"
    "${artisan[@]}" storage:link
    "${artisan[@]}" optimize || fail "artisan optimize selhal. Web se nepřepnul a běží předchozí verze."

    step "Migrace, seedery a zkouška stránek nového release (přes web, před přepnutím)"
    run_web_hook "$rel"

    step "Přepnutí webu na $RELEASE"
    touch "$rel/.deployed"
    switch_link "releases/$RELEASE" "$BASE/current"

    # Web root domény musí ukazovat na current/public. Při prvním nasazení je
    # to ještě skutečný adresář s výchozím obsahem od Webglobe. Ten se jen
    # přejmenuje (nic se nemaže), takže jde kdykoli vrátit.
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
