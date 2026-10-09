<div class="docs-eyebrow">Operators</div>
<h1>Commands &amp; Scheduling</h1>

<h2 id="artisan">Commands</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Command</th><th>What it does</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">admin:create {name} {email}</code></td><td>Create an operator for <code class="inline">/admin</code> (prompts for a password of 12+ characters)</td></tr>
    <tr><td><code class="inline">admin:reset-2fa {email}</code></td><td>Remove an operator's authenticator and end their sessions. Needs server access by design.</td></tr>
    <tr><td><code class="inline">tenant:create {name} [--voice-mode=own|shared] [--environment=sandbox|live]</code></td><td>Create a tenant and print its first API key once</td></tr>
    <tr><td><code class="inline">voice-number:add {number} [--tenant=slug] [--provider=africastalking]</code></td><td>Register a number (omit <code class="inline">--tenant</code> for the shared pool) and print its callback token once</td></tr>
    <tr><td><code class="inline">tenant-user:create {tenant} {name} {email} [--role=owner]</code></td><td>Invite someone to a tenant's portal; prints a one-time link</td></tr>
    <tr><td><code class="inline">tenant-user:reset-2fa {email}</code></td><td>Clear a portal user's authenticator</td></tr>
    <tr><td><code class="inline">codes:expire</code></td><td>Expire issued codes past their deadline, release holds, send <code class="inline">code.expired</code></td></tr>
    <tr><td><code class="inline">transactions:reconcile</code></td><td>Resolve pending transactions whose capture never reported back</td></tr>
    <tr><td><code class="inline">audit:verify {tenant?}</code></td><td>Check audit chains; exits 1 on tampering — suitable for monitoring</td></tr>
  </tbody>
</table></div>

<h2 id="schedule">Scheduled jobs</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Job</th><th>Frequency</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">codes:expire</code></td><td>every minute (no overlap)</td></tr>
    <tr><td><code class="inline">transactions:reconcile</code></td><td>every five minutes (no overlap)</td></tr>
    <tr><td><code class="inline">model:prune</code></td><td>daily — removes finished webhook deliveries older than 30 days</td></tr>
  </tbody>
</table></div>
<p>Run <code class="inline">php artisan schedule:work</code> as a service, or a cron entry calling <code class="inline">schedule:run</code> every minute.</p>

<h2 id="demo">Demo data</h2>
<p><code class="inline">php artisan db:seed</code> (refuses to run in production) creates four demo tenants, a shared number, a demo operator (<code class="inline">ops@example.test</code>) and portal logins <code class="inline">owner@</code>, <code class="inline">developer@</code> and <code class="inline">viewer@&lt;tenant-slug&gt;.example.test</code>. All passwords are <code class="inline">password</code> unless <code class="inline">SEED_ADMIN_PASSWORD</code> / <code class="inline">SEED_PORTAL_PASSWORD</code> is set. <code class="inline">SEED_DEMO_HISTORY=1</code> adds a fortnight of sample payments. API keys and secrets are random and written to the git-ignored <code class="inline">storage/app/simulation.json</code>.</p>
<p>The <code class="inline">simulation/</code> folder holds an end-to-end load and failure simulation (including concurrency checks) that runs against a database named <code class="inline">*_sim</code>.</p>
