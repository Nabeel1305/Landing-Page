FROM serversideup/php:8.4.5-fpm-nginx
ENV PHP_OPCACHE_ENABLE=1

# Switch to root to install dependencies
USER root

# Install GD dependencies + SQLite driver
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Create the SQLite database file. This happens at build time (still root,
# before the USER switch below) so it exists before any artisan command —
# including composer's own package:discover / vendor:publish hooks — ever
# tries to touch the database, which is exactly what broke the build last
# time (optimize:clear hit a database file that didn't exist yet).
#
# NOTE: this file lives inside the image's writable layer. Every rebuild
# starts from this empty file again — mount a persistent volume at
# /var/www/html/database in production (Coolify: Persistent Storage on this
# path) or every redeploy silently wipes the database, including contact
# form submissions.
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database \
    && chmod -R 775 database

# Switch back to www-data user
USER www-data

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN php artisan storage:link

# Cache routes and views at build time — pure disk I/O, no database or
# runtime env vars needed, so it's safe to bake into the image.
#RUN php artisan migrate --force
RUN php artisan optimize

# config:cache is deliberately NOT run here — it would bake in whatever
# environment variables are visible at BUILD time. Run it at deploy time
# instead, alongside migrations, in Coolify's "Post-deployment command":
#   php artisan config:cache && php artisan migrate --force

EXPOSE 8080