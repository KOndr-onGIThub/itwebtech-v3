## 1. Nainstaluj PHP závislosti (Filament, Spatie Sitemap, atd.)
composer install

## 2. Připrav .env a vygeneruj klíč
cp .env.example .env
php artisan key:generate

## 3. Migrace (SQLite defaultně)
php artisan migrate

## 4. Vytvoř Filament admin účet
php artisan make:filament-user

## 5. Nainstaluj JS závislosti
npm install

## 6. Spusť dev server
npm run dev
# V druhém terminálu:
php artisan serve
# Nebo spíš Wampserver
předtím upravit:
C:\Windows\System32\drivers\etc\hosts.txt
C:\wamp64\bin\apache\apache2.4.59\conf\extra\httpd-vhosts.conf

## 7. Git
git init