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

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Switch back to www-data user
USER www-data

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN php artisan storage:link

# Cache routes and views at build time — pure disk I/O, no database or
# runtime env vars needed, so it's safe to bake into the image.
RUN php artisan route:cache
RUN php artisan view:cache

# config:cache is deliberately NOT run here. It would bake in whatever
# environment variables are visible at BUILD time — if Coolify only injects
# them at container runtime (common), this caches empty/wrong config into
# the image instead of your real values. Run it at deploy time instead,
# alongside `migrate --force`, in Coolify's "Post-deployment command":
#   php artisan config:cache && php artisan migrate --force
# That also keeps it consistent with why migrations were pulled out of this
# Dockerfile in the first place — same failure mode, same fix.

EXPOSE 8080