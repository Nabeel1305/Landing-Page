# PakaPay Website — Laravel

The PakaPay marketing site, converted from static HTML to a real Laravel 11 / PHP 8.4 project — matching the stack the actual PakaPay backend already runs on.

## What changed from the static version

- **Blade templating** — the ~70KB of CSS and the header/footer markup that used to be duplicated in every one of the 10 HTML files now lives once, in `resources/views/layouts/` and `resources/views/partials/`.
- **Real routes** — `routes/web.php`, named (`route('business')`, `route('contact')`, etc.) instead of hardcoded file paths.
- **A working contact form** — `POST /contact.html` validates input, stores it in a `contact_submissions` table, and attempts to email it. See "Contact form" below for what "working" means before you configure real SMTP.
- **Laravel's actual 404 convention** — `resources/views/errors/404.blade.php`, served automatically by the framework for any `abort(404)`.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Visit `http://localhost:8000`.

## Contact form

Submissions are always saved to the database — nothing is lost even before email is configured. By default `MAIL_MAILER=log`, so instead of sending, the notification email is written to `storage/logs/laravel.log`. To actually receive these by email, set real SMTP credentials in `.env` (or your host's environment variables):

```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS="hello@pakapay.ng"
```

No code changes needed — `ContactController` already reads from `config('mail.*')`.

## Deploying with Docker / Coolify

```bash
docker build -t pakapay-website .
docker run --rm -p 8080:80 pakapay-website
```

Or `docker compose up --build`.

**Before deploying to Coolify:**

1. **Set `APP_KEY` as an environment variable**, generated once locally with `php artisan key:generate --show`. The entrypoint script will generate one automatically if it's missing, but that key lives in the container's `.env` file — a rebuild creates a new container and would silently rotate it, invalidating existing sessions. Setting it explicitly avoids that.
2. **Mount persistent storage for the database.** This app defaults to SQLite for zero-config simplicity, but the database file is created *inside* the container at build/boot time. Without a persistent volume, every redeploy wipes it — including all contact form submissions. In Coolify, add a "Persistent Storage" entry mapping a volume to `/var/www/html/database` (and ideally `/var/www/html/storage/logs` too, so logs survive as well). The included `docker-compose.yml` does this for local/compose-based deployments already.
3. If you'd rather not deal with SQLite persistence at all, point `DB_CONNECTION` at a managed MySQL/Postgres instance instead and skip the volume for `database/`.
4. Set the domain to `pakapay.ng` in Coolify's resource settings, same as the static-site version.

## What wasn't verified

This was built by extracting and restructuring the working static HTML site into Blade templates — every route, view, and link was checked programmatically for consistency (no dead `route()` calls, no orphaned links). **It has not been run against an actual PHP interpreter**, since none was available in the environment this was built in. Run `composer install` and `php artisan serve` yourself before trusting this in production, and treat this as a careful first pass rather than a tested release.
