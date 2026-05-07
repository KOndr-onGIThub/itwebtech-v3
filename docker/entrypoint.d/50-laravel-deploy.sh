#!/bin/sh
#
# Spouští se při startu kontejneru (před php-fpm/nginx).
# Dockerfile nastavuje `USER www-data`, takže tento skript BĚŽÍ jako www-data —
# `s6-setuidgid` proto nepoužíváme (vyžadoval by CAP_SETUID = root → při volání
# jako www-data padne s "Operation not permitted" a `set -e` by ukončil boot
# kontejneru → 503 outage; viz OND-76).
#
# DŮLEŽITÉ — bezpečnostní pravidlo:
#   Tento skript smí volat výhradně:
#     - php artisan migrate --force
#     - php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
#
#   NIKDY nevolat:
#     - php artisan db:seed (bez --class) → spustí PortfolioSeeder a přepíše
#       ručně upravená portfolio data v Filamentu.
#     - php artisan migrate:fresh / migrate:refresh → vymaže DB.
#
# Skript je idempotentní a fail-safe: pokud DB ještě nejede nebo migrace/seed
# selže, zaloguje chybu, ALE container i tak nastartuje. Cílem je nikdy
# nezablokovat boot kontejneru — admin si runtime stav opraví manuálně.

set -u  # bez -e: jednotlivé chyby logujeme a pokračujeme

APP_PATH="/var/www/html"
if [ ! -f "${APP_PATH}/artisan" ]; then
    echo "[laravel-deploy] artisan not found in ${APP_PATH}, skipping."
    exit 0
fi

cd "${APP_PATH}"

echo "[laravel-deploy] Running as user=$(id -un) uid=$(id -u)"

# Počkat na DB (max ~30 s); deploy občas startuje kontejner dřív, než je
# externí DB připravená přijímat spojení.
echo "[laravel-deploy] Waiting for DB readiness..."
i=1
while [ "$i" -le 15 ]; do
    if php artisan db:show --no-interaction >/dev/null 2>&1; then
        echo "[laravel-deploy] DB reachable after ${i} attempt(s)."
        break
    fi
    echo "[laravel-deploy] DB not ready yet (${i}/15), sleeping 2s..."
    sleep 2
    i=$((i + 1))
done

echo "[laravel-deploy] Running database migrations..."
if ! php artisan migrate --force --no-interaction; then
    echo "[laravel-deploy] WARN: migrate failed, continuing boot anyway." >&2
fi

echo "[laravel-deploy] Seeding admin user..."
if ! php artisan db:seed --class="Database\\Seeders\\AdminUserSeeder" --force --no-interaction; then
    echo "[laravel-deploy] WARN: AdminUserSeeder failed, continuing boot anyway." >&2
fi

echo "[laravel-deploy] Done."
exit 0
