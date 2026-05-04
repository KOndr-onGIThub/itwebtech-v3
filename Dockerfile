FROM serversideup/php:8.4-fpm-nginx

USER root

# PHP extensions
RUN install-php-extensions pdo_mysql mbstring bcmath exif pcntl gd intl zip opcache redis

# Node (pro Vite)
RUN apt-get update && apt-get install -y curl git unzip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

# Composer deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts

# Node deps
COPY package.json package-lock.json* ./
RUN npm install

# Zbytek appky
COPY . .

# Dokončení composeru po zkopírování app souborů
RUN composer dump-autoload --optimize

# Build assetů
RUN npm run build

# Práva
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 8080