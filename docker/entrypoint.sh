#!/bin/sh
set -e

cd /var/www/html

# Generate an app key on first boot if one isn't already set via environment.
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64" .env 2>/dev/null; then
    php artisan key:generate --force
fi

# SQLite is the zero-config default (see .env.example). Create the database
# file if it doesn't exist yet — on Coolify/production, mount a persistent
# volume at /var/www/html/database so this file (and its data) survives
# redeploys, or switch DB_CONNECTION to a managed mysql/pgsql instance.
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p database
    touch database/database.sqlite
fi

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
