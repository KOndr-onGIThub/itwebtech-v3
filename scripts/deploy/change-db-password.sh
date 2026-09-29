#!/usr/bin/env bash
#
# OND-463: výměna hesla k databázi na Webglobe tak, aby nové heslo nikdy
# nebylo v textu mimo administraci Webglobe a server. Spouští se ručně
# v SSH konzoli Webglobe (přihlašovací shell je fish, proto přes `bash`):
#
#   bash /home/html/ondraweb.cz/app/current/scripts/deploy/change-db-password.sh
#
# Předtím se heslo změní v administraci Webglobe (Hosting → Databáze).
# Skript si nové heslo vyžádá (při psaní se nezobrazuje), zapíše ho do kopie
# `.env.new` a nejdřív s ní zkusí přihlášení do databáze. Teprve když projde,
# kopii přejmenuje na `.env` (stejně jako runbook, část F), obnoví cache
# konfigurace a vyzkouší stránky. Když přihlášení neprojde, `.env` zůstane
# beze změny.
#
# Cache konfigurace obnovuje i ve starších release, jinak by návrat na
# předchozí verzi (`rollback`) spustil web se starým heslem.

set -euo pipefail

APP_DIR="${APP_DIR:-/home/html/ondraweb.cz/app}"
PHP_BIN="${PHP_BIN:-php8.4}"

SHARED="$APP_DIR/shared"
ENV_FILE="$SHARED/.env"
NEW_FILE="$SHARED/.env.new"
CURRENT="$APP_DIR/current"

fail() {
    printf '\nCHYBA: %s\n' "$*" >&2
    exit 1
}

[ -s "$ENV_FILE" ] || fail "Nenašel jsem $ENV_FILE."
[ -f "$CURRENT/artisan" ] || fail "Nenašel jsem aplikaci v $CURRENT."
command -v "$PHP_BIN" >/dev/null 2>&1 || fail "Na serveru chybí $PHP_BIN."
[ "$(grep -c '^DB_PASSWORD=' "$ENV_FILE")" = 1 ] \
    || fail "V $ENV_FILE musí být právě jeden řádek DB_PASSWORD=."

printf 'Vlož nové heslo k databázi a stiskni Enter (při psaní se nezobrazuje): '
IFS= read -rs NEW_DB_PASSWORD || true
printf '\n'
[ -n "$NEW_DB_PASSWORD" ] || fail "Heslo je prázdné. Nic jsem nezměnil."
case "$NEW_DB_PASSWORD" in
    *"'"*) fail "Heslo obsahuje apostrof ('), ten do .env zapsat neumím. Vygeneruj v administraci jiné. Nic jsem nezměnil." ;;
esac
export NEW_DB_PASSWORD

# Kopie přes `cp -p` drží práva .env (web může běžet pod jiným uživatelem).
# Heslo jde do awk přes prostředí, ne jako argument (ten by viděl `ps`).
rm -f "$NEW_FILE"
cp -p "$ENV_FILE" "$NEW_FILE"
awk '/^DB_PASSWORD=/ { print "DB_PASSWORD='\''" ENVIRON["NEW_DB_PASSWORD"] "'\''"; next } { print }' \
    "$ENV_FILE" > "$NEW_FILE"

echo "Zkouším nové heslo proti databázi…"
# shellcheck disable=SC2016 # PHP kód, proměnné patří PHP, ne shellu
if ! "$PHP_BIN" -r '
    require $argv[1]."/vendor/autoload.php";
    $env = Dotenv\Dotenv::parse(file_get_contents($argv[2]));
    if (($env["DB_PASSWORD"] ?? null) !== getenv("NEW_DB_PASSWORD")) {
        fwrite(STDERR, "Heslo se do .env.new nezapsalo přesně.\n");
        exit(1);
    }
    try {
        $pdo = new PDO(
            sprintf("mysql:host=%s;port=%s;dbname=%s", $env["DB_HOST"], $env["DB_PORT"] ?? "3306", $env["DB_DATABASE"]),
            $env["DB_USERNAME"],
            $env["DB_PASSWORD"],
            [PDO::ATTR_TIMEOUT => 10, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
        $pdo->query("SELECT 1");
    } catch (PDOException $e) {
        fwrite(STDERR, $e->getMessage()."\n");
        exit(1);
    }
' "$CURRENT" "$NEW_FILE"; then
    rm -f "$NEW_FILE"
    fail "Databáze nové heslo nepřijala, .env jsem nezměnil a web běží dál jako předtím. Zkontroluj, že je změna v administraci uložená, počkej minutu a spusť skript znovu."
fi
unset NEW_DB_PASSWORD

mv -f "$NEW_FILE" "$ENV_FILE"
echo "Heslo funguje a je zapsané v $ENV_FILE."

echo "Obnovuji cache konfigurace…"
(cd "$CURRENT" && "$PHP_BIN" artisan config:cache --no-interaction >/dev/null) \
    || fail "config:cache selhal. Spusť nasazení znovu: GitHub → Actions → Nasazení produkce (Webglobe) → Run workflow → deploy."

active="$(basename "$(readlink "$CURRENT")")"
for rel in "$APP_DIR"/releases/*/; do
    rel="${rel%/}"
    [ "$(basename "$rel")" = "$active" ] && continue
    [ -f "$rel/.deployed" ] && [ -f "$rel/bootstrap/cache/config.php" ] || continue
    (cd "$rel" && "$PHP_BIN" artisan config:cache --no-interaction >/dev/null) \
        || echo "Upozornění: cache konfigurace v $(basename "$rel") se nepovedla, návrat na tuhle verzi by nefungoval."
done

echo "Zkouším stránky webu…"
"$PHP_BIN" "$CURRENT/scripts/deploy/smoke.php" "$CURRENT" \
    || fail "Stránky po změně nevrací 200. Spusť nasazení znovu: GitHub → Actions → Nasazení produkce (Webglobe) → Run workflow → deploy."

printf '\nHOTOVO: web používá nové heslo k databázi.\n'
