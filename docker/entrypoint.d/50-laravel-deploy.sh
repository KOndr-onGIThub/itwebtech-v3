#!/bin/sh
#
# Spouští se při startu kontejneru (před php-fpm/nginx) jako root,
# pak Laravel příkazy přepneme přes `s6-setuidgid www-data`.
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
# Skript je idempotentní: pending migrace se aplikují, ostatní skipnou;
# AdminUserSeeder dělá updateOrCreate na jednom users řádku.

set -eu

# Spustit jen v hlavním kontejneru (ne v sidecarech). serversideup/php nastavuje
# CONTAINER_ROLE=app v základním image, ale pro jistotu testujeme i existenci
# webroot.
APP_PATH="/var/www/html"
if [ ! -f "${APP_PATH}/artisan" ]; then
    echo "[laravel-deploy] artisan not found in ${APP_PATH}, skipping."
    exit 0
fi

cd "${APP_PATH}"

echo "[laravel-deploy] Running database migrations..."
s6-setuidgid www-data php artisan migrate --force --no-interaction

echo "[laravel-deploy] Seeding admin user..."
s6-setuidgid www-data php artisan db:seed --class="Database\\Seeders\\AdminUserSeeder" --force --no-interaction

echo "[laravel-deploy] Done."
