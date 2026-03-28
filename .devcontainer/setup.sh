#!/bin/bash
set -e

echo "==> Installing MySQL client + PHP extensions..."
dpkg -s default-mysql-client &>/dev/null || (sudo apt-get update -qq && sudo apt-get install -y -q default-mysql-client)
sudo docker-php-ext-install pdo_mysql pcntl || true
CONF_DIR=$(php --ini 2>/dev/null | grep "Scan for additional" | awk '{print $NF}')
[[ -z "$CONF_DIR" || "$CONF_DIR" == "(none)" ]] && CONF_DIR="/usr/local/etc/php/conf.d"
[ -f "${CONF_DIR}/docker-php-ext-pdo_mysql.ini" ] || echo "extension=pdo_mysql.so" | sudo tee "${CONF_DIR}/docker-php-ext-pdo_mysql.ini" > /dev/null

echo "==> Setting up .env..."
[ -f .env ] || cp .env.example .env
sed -i 's/^DB_HOST=.*/DB_HOST=mysql/' .env

echo "==> Waiting for MySQL (background)..."
until mysqladmin -h mysql -u root --ssl=0 ping --silent 2>/dev/null; do sleep 2; done &
MYSQL_WAIT_PID=$!

echo "==> Installing dependencies (parallel)..."
composer install --no-interaction &
npm install &
wait

echo "==> Generating app key..."
php artisan key:generate

echo "==> Waiting for MySQL to be ready..."
wait $MYSQL_WAIT_PID
echo "    MySQL ready."

echo "==> Creating database..."
DB_NAME=$(grep '^DB_DATABASE=' .env | cut -d'=' -f2- | tr -d '"'"'")
[ -n "$DB_NAME" ] || { echo "ERROR: DB_DATABASE not set in .env"; exit 1; }
mysql -h mysql -u root --ssl=0 -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Setup complete!"
