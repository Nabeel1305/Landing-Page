<div class="docs-eyebrow">Platform (Internal)</div>
<h1>Configuration</h1>

<p>PakaPay is a Laravel 12 application (PHP ≥ 8.2). Core packages: Sanctum (tokens), Pulse (metrics), Scramble (API docs), Sentry, AWS SDK (KMS), dompdf, simple-qrcode/barcode, Resend, Laravel UI. Clients are served from <code class="inline">routes/api.php</code> (prefix <code class="inline">/api</code>); the back office from <code class="inline">routes/web.php</code>.</p>

<h2 id="setup">Local setup</h2>
<x-docs.code lang="bash">composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
npm install && npm run build        # admin dashboard assets
php artisan serve</x-docs.code>
<p>Create the first super-admin through the <code class="inline">super_admins</code> table (or a tinker/seeder); there is no public sign-up for operators.</p>

<h2 id="env">Environment variables</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Group</th><th>Variables</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td>App</td><td><code class="inline">APP_ENV</code>, <code class="inline">APP_KEY</code>, <code class="inline">APP_DEBUG</code>, <code class="inline">APP_URL</code>, <code class="inline">APP_TIMEZONE</code></td><td><code class="inline">APP_ENV=production</code> enables HTTPS redirect + HSTS</td></tr>
    <tr><td>Database / cache / queue</td><td><code class="inline">DB_*</code>, <code class="inline">CACHE_STORE</code>, <code class="inline">QUEUE_CONNECTION</code>, <code class="inline">SESSION_DRIVER</code>, <code class="inline">REDIS_*</code></td><td>Fraud counters and rate limits live in cache: use a shared, persistent store</td></tr>
    <tr><td>Sanctum</td><td><code class="inline">SANCTUM_TOKEN_EXPIRATION_MINUTES</code> (43200), <code class="inline">SANCTUM_INACTIVITY_TIMEOUT_MINUTES</code> (30)</td><td>Absolute and idle token lifetime</td></tr>
    <tr><td>Transaction signing</td><td><code class="inline">TRANSACTION_SIGNING_KMS_KEY_ID</code>, <code class="inline">_KMS_ALG</code>, <code class="inline">_KMS_REGION</code>, <code class="inline">TRANSACTION_SIGNING_AWS_ACCESS_KEY_ID</code>, <code class="inline">…_SECRET_ACCESS_KEY</code></td><td>Asymmetric <code class="inline">SIGN_VERIFY</code> key. Unset = payments refuse to complete</td></tr>
    <tr><td>Offline rail</td><td><code class="inline">AT_USERNAME</code>, <code class="inline">AT_API_KEY</code>, <code class="inline">AT_VOICE_NUMBER</code>, <code class="inline">AT_WEBHOOK_SECRET</code>, <code class="inline">AT_BASE_URL</code>, <code class="inline">DEVICE_KEY_KMS_KEY_ID</code>, <code class="inline">DEVICE_KEY_KMS_REGION</code>, <code class="inline">DEVICE_KEY_AWS_*</code>, <code class="inline">OFFLINE_MAX_SINGLE_TXN</code>, <code class="inline">OFFLINE_MAX_DAILY_VOLUME</code></td><td>HMAC <code class="inline">GENERATE_VERIFY_MAC</code> key for device keys</td></tr>
    <tr><td>SMS / OTP</td><td><code class="inline">KUDISMS_API_KEY</code>, <code class="inline">KUDISMS_SENDER_ID</code>, <code class="inline">KUDISMS_GATEWAY</code>, <code class="inline">KUDISMS_APPNAMECODE</code>, <code class="inline">KUDISMS_TEMPLATECODE</code></td><td></td></tr>
    <tr><td>KYC</td><td><code class="inline">YOUVERIFY_API_KEY</code>, <code class="inline">YOUVERIFY_WEBHOOK_SECRET</code>, <code class="inline">YOUVERIFY_PUBLIC_MERCHANT_KEY</code>, <code class="inline">YOUVERIFY_SANDBOX</code></td><td>Webhook secret must match the Youverify dashboard</td></tr>
    <tr><td>Mail</td><td><code class="inline">MAIL_*</code> (or Resend key)</td><td></td></tr>
    <tr><td>Observability</td><td><code class="inline">SENTRY_LARAVEL_DSN</code>, <code class="inline">SENTRY_TRACES_SAMPLE_RATE</code>, <code class="inline">LOG_*</code></td><td></td></tr>
    <tr><td>AWS (general)</td><td><code class="inline">AWS_ACCESS_KEY_ID</code>, <code class="inline">AWS_SECRET_ACCESS_KEY</code>, <code class="inline">AWS_DEFAULT_REGION</code>, <code class="inline">AWS_BUCKET</code></td><td>Region defaults to <code class="inline">us-east-1</code> — decide hosting region deliberately</td></tr>
  </tbody>
</table></div>
<x-docs.callout type="warn" title="Secrets hygiene">
  <p>Provider credentials in the admin <em>Service API Keys</em> screen take precedence over <code class="inline">.env</code>. Firebase credentials are a service-account JSON file under <code class="inline">storage/app/</code>; keep it, and every <code class="inline">.env</code>, out of version control and backups that leave your control.</p>
</x-docs.callout>

<h2 id="deploy-checklist">Deploy checklist</h2>
<ol>
  <li>Run migrations (<code class="inline">php artisan migrate</code>) — the 22 July 2026 security migrations add signing-key ids, audit logs, device suspension, the kill-switch table and drop <code class="inline">kyc_selfie_url</code>.</li>
  <li>Create KMS keys (signing: asymmetric SIGN_VERIFY; device keys: HMAC_256) and grant the app's IAM identity <code class="inline">kms:Sign</code>/<code class="inline">kms:Verify</code> and <code class="inline">kms:GenerateMac</code> only.</li>
  <li>Revoke <code class="inline">UPDATE</code>/<code class="inline">DELETE</code> on <code class="inline">audit_logs</code> for the app's DB user.</li>
  <li>Configure Africa's Talking callback and events URLs with the secret token; confirm the deployed build enforces it.</li>
  <li>Set Youverify webhook URL to <code class="inline">/api/webhook/youverify</code> and the matching secret.</li>
  <li>Point the load balancer / Cloudflare at the app; ensure HTTPS and (for the bank link) mTLS + IP allow-listing at the edge.</li>
  <li>Run a queue worker if mail/notifications are queued; run <code class="inline">php artisan schedule:work</code> if scheduled jobs are added.</li>
  <li>Smoke test: register → verify → login → link account → pay → verify audit chain.</li>
</ol>

<h2 id="tests">Tests</h2>
<p><code class="inline">phpunit.xml</code> is configured; the repo ships only a base <code class="inline">TestCase</code> — there is no feature coverage of payments, auth or the offline rail yet. Adding tests for those flows (especially the offline signature interop with the Flutter app) is the highest-value follow-up.</p>
