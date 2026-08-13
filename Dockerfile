FROM serversideup/php:8.4.5-fpm-nginx
ENV PHP_OPCACHE_ENABLE=1

# Switch to root to install dependencies
USER root

# Install GD dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Imagick — required by simplesoftwareio/simple-qrcode's PNG
# backend (bacon/bacon-qr-code's ImagickImageBackEnd). Without this,
# QRService::generateBase64() throws "You need to install the imagick
# extension to use this back end" on every /api/receive/qr call —
# hit this in production before it was added here.
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && rm -rf /var/lib/apt/lists/*


RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    libpng-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_sqlite mbstring opcache 

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Switch back to www-data user
USER www-data

EXPOSE 8080

# Install dependencies and optimize
RUN composer install --no-interaction --optimize-autoloader --no-dev
# Migrations deliberately do NOT run here. Baking `php artisan migrate`
# into the image build is fine for a pre-production/dev workflow, but
# risky once live: it runs at BUILD time (which may not even have DB
# access, e.g. a CI build server), with no --force flag (Laravel prompts
# for confirmation in production, which a non-interactive build can't
# answer), and with no control over WHEN it runs relative to the actual
# release — every image build becomes an implicit migration attempt,
# including rebuilds that have nothing to do with a schema change.
# Migrations belong to the deploy/release step instead, run deliberately
# via `php artisan migrate --force` — in Coolify, that's the service's
# "Post-deployment command" (or similar) setting, not this Dockerfile.
RUN php artisan storage:link
RUN php artisan optimize:clear

