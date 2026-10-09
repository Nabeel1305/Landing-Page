<div class="docs-eyebrow">Operators</div>
<h1>Configuration</h1>

<p>Laravel 12 / PHP 8.2+. Settings come from <code class="inline">.env</code> and <code class="inline">config/platform.php</code>.</p>

<h2 id="setup">Setup</h2>
<x-docs.code lang="bash">composer install
cp .env.example .env && php artisan key:generate
# create the database named in DB_DATABASE, then:
php artisan migrate
php artisan admin:create "Your Name" you@example.com        # operator login at /admin
php artisan tenant:create "Acme Bank"                        # prints the first API key once
php artisan voice-number:add +2347000000001 --tenant=acme-bank-x1y2   # prints the callback URL once
php artisan queue:work                                       # webhook delivery
php artisan schedule:work                                    # expiry, reconciliation, pruning</x-docs.code>
<p>After pulling changes, run <code class="inline">php artisan migrate</code>.</p>

<h2 id="platform">Platform settings (<code class="inline">PLATFORM_*</code>)</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Variable</th><th>Default</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">PLATFORM_CODE_LENGTH</code></td><td>12</td><td>Random digits per code (a shared-pool code adds the 4-digit short code)</td></tr>
    <tr><td><code class="inline">PLATFORM_CODE_PEPPER</code></td><td>falls back to <code class="inline">APP_KEY</code></td><td>Secret key for the stored code hashes. <strong>Set once and never rotate while codes are open</strong> — changing it invalidates them.</td></tr>
    <tr><td><code class="inline">PLATFORM_API_PER_MINUTE</code></td><td>300</td><td>Requests per minute per tenant</td></tr>
    <tr><td><code class="inline">PLATFORM_API_BAD_KEY_PER_MINUTE</code></td><td>30</td><td>Invalid-key requests per minute per source address</td></tr>
    <tr><td><code class="inline">PLATFORM_REDEEM_CALLER_FAILURES</code></td><td>5</td><td>Wrong guesses per caller before lockout (10 min)</td></tr>
    <tr><td><code class="inline">PLATFORM_REDEEM_TENANT_PER_MINUTE</code></td><td>120</td><td>Redemption attempts per tenant per minute</td></tr>
    <tr><td><code class="inline">PLATFORM_VOICE_PER_NUMBER_PER_MINUTE</code></td><td>600</td><td>Voice callbacks per number per minute</td></tr>
    <tr><td><code class="inline">PLATFORM_WEBHOOK_BACKOFF</code></td><td><code class="inline">60,300,1800,7200,21600,43200</code></td><td>Seconds before each retry; number of entries + 1 = attempts</td></tr>
    <tr><td><code class="inline">PLATFORM_ALLOW_PRIVATE_WEBHOOK_URLS</code></td><td>false</td><td>Testing only. Live tenants must use public https.</td></tr>
    <tr><td><code class="inline">PLATFORM_ADMIN_REQUIRE_2FA</code></td><td>true in production</td><td>Mandatory authenticator for operators and tenant staff. <code class="inline">false</code> to relax outside production.</td></tr>
    <tr><td><code class="inline">PLATFORM_CORS_ORIGINS</code></td><td><code class="inline">*</code></td><td>Browser origins allowed to call <code class="inline">/api/*</code>; comma-separated list, or empty to disable</td></tr>
    <tr><td><code class="inline">TRUSTED_PROXIES</code></td><td>none</td><td><code class="inline">*</code> or addresses of your load balancer, so client IPs and https links come out right</td></tr>
  </tbody>
</table></div>
<p>Reconciliation timing (2 minutes before checking, 24 hours before flagging for review) and the 5-minute webhook signature tolerance are in <code class="inline">config/platform.php</code>.</p>

<h2 id="infrastructure">Infrastructure</h2>
<ul>
  <li><strong>Database:</strong> MySQL/MariaDB in production. The code path was concurrency-tested on MariaDB 10.4; repeat on your production engine and storage before agreeing capacity with tenants.</li>
  <li><strong>Queue, cache and rate limits:</strong> use <strong>Redis</strong>. The database queue driver deadlocks between workers, and a cache not shared between instances weakens the limits. With <code class="inline">QUEUE_CONNECTION=sync</code>, webhooks are delivered inside the request — tests only.</li>
  <li><strong>Workers:</strong> keep <code class="inline">queue:work</code> and <code class="inline">schedule:work</code> (or cron <code class="inline">schedule:run</code>) running. Without the scheduler, codes never expire and lost captures never reconcile.</li>
  <li><strong>Logging:</strong> exclude <code class="inline">dtmfDigits</code> and the voice route's query string from every logger and APM — they contain live codes and the number's token.</li>
  <li><strong>Backups:</strong> encrypt them; agree retention with each tenant.</li>
</ul>

<h2 id="tests">Tests</h2>
<x-docs.code lang="bash">php vendor/bin/phpunit
DB_CONNECTION=mysql DB_DATABASE=offline_platform_test php vendor/bin/phpunit   # also on MySQL/MariaDB</x-docs.code>
<p>The suite wipes its database, so non-SQLite databases must be named <code class="inline">*_test</code>; anything else is refused.</p>
