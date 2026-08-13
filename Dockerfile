# PakaPay marketing site — Laravel 11 / PHP 8.4
#
# Two stages: install PHP dependencies with Composer, then assemble the
# runtime image (PHP-FPM + nginx, run together via supervisor).

# ---------- Stage 1: Composer dependencies ----------
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---------- Stage 2: Runtime ----------
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        nginx \
        supervisor \
        sqlite \
        sqlite-dev \
        libpng-dev \
        oniguruma-dev \
    && docker-php-ext-install pdo_sqlite mbstring opcache \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN cp .env.example .env \
    && mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=15s \
    CMD wget -qO- http://localhost/up > /dev/null || exit 1

ENTRYPOINT ["entrypoint.sh"]
