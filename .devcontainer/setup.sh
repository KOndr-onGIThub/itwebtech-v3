#!/bin/bash
set -e

echo "==> Installing MySQL client..."
sudo apt-get update -qq && sudo apt-get install -y -q default-mysql-client

echo "==> Setting up .env..."
[ -f .env ] || cp .env.example .env
# V kontejneru je MySQL vždy na hostu 'mysql' (docker-compose service name)
sed -i 's/^DB_HOST=.*/DB_HOST=mysql/' .env

echo "==> Installing PHP dependencies..."
composer install --no-interaction

echo "==> Installing JS dependencies..."
npm install

echo "==> Generating app key..."
php artisan key:generate

echo "==> Waiting for MySQL..."
until mysqladmin -h mysql ping --silent 2>/dev/null; do
    sleep 2
done
echo "    MySQL ready."

echo "==> Creating database..."
DB_NAME=$(grep '^DB_DATABASE=' .env | cut -d'=' -f2)
mysql -h mysql -u root -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Setup complete!"
