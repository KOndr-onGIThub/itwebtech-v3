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

# Deploy hook: serversideup/php image spouští skripty z /etc/entrypoint.d/
# před hlavním procesem. Tady běží `migrate --force` + AdminUserSeeder.
# Detaily a bezpečnostní pravidla viz docker/entrypoint.d/50-laravel-deploy.sh.
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/

# OND-123: long-cache headers pro Vite hash-suffixované assety v /build/.
# serversideup/php-fpm-nginx auto-includuje *.conf z server-opts.d do default
# server kontextu. Detail viz docker/nginx/server-opts.d/cache-build-assets.conf.
COPY docker/nginx/server-opts.d/ /etc/nginx/server-opts.d/

USER www-data

EXPOSE 8080