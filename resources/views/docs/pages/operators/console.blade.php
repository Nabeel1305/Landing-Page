<div class="docs-eyebrow">Operators</div>
<h1>Operator Console</h1>

<p>For PakaPay staff who run the platform — <strong>not</strong> for tenants. It lives at <code class="inline">/admin</code>, with its own accounts (separate from tenant staff) protected by password <em>and</em> an authenticator code (mandatory in production).</p>

<h2 id="tenants">Tenants</h2>
<p><strong>Create</strong> a tenant with a name and voice mode (own number or shared pool). It starts in the <strong>sandbox</strong>, gets a short code and a first API key — displayed once. The list shows environment, status, voice mode, active key count and pending transactions per tenant.</p>
<p>On a tenant's page:</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Setting</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td>Status</td><td><code class="inline">active</code> or <code class="inline">suspended</code>. A suspended tenant's API calls get <code class="inline">403 tenant_suspended</code>, and its calls and codes stop working.</td></tr>
    <tr><td>Environment</td><td><code class="inline">sandbox</code> or <code class="inline">live</code>. A live tenant can't use the sandbox adapter.</td></tr>
    <tr><td>Settlement adapter</td><td>Currently only <code class="inline">sandbox</code> exists.</td></tr>
    <tr><td>Voice mode</td><td><code class="inline">own</code> or <code class="inline">shared</code>. Can't change while the tenant has open (<code class="inline">issued</code> or <code class="inline">redeemed</code>) codes.</td></tr>
    <tr><td>Code lifetime</td><td>1–60 minutes.</td></tr>
    <tr><td>Maximum amount</td><td>Optional, in minor units. Larger requests → <code class="inline">settlement_rejected</code>.</td></tr>
    <tr><td>Caller binding</td><td>Only accept a code from the subscriber's registered phone.</td></tr>
  </tbody>
</table></div>
<p>Every settings change is audit-logged with before/after values.</p>

<h2 id="access">API keys, numbers and portal users</h2>
<ul>
  <li><strong>API keys:</strong> issue (shown once) and revoke (with a confirmation).</li>
  <li><strong>Voice numbers:</strong> add a number in E.164 form (the callback URL with its token is shown once — paste it into the telephony provider), enable/disable, and <strong>rotate the token</strong> (the old URL stops working at once).</li>
  <li><strong>Portal users:</strong> invite the tenant's first owner (or anyone) and get a one-time link; issue a new access link, reset someone's two-factor, or switch a person off — the recovery path when a tenant is locked out.</li>
</ul>

<h2 id="monitoring">Monitoring</h2>
<p>A tenant's page also shows its latest webhook deliveries, its latest audit entries, a warning when transactions are waiting on the tenant's settlement system, and a <strong>Verify chain integrity</strong> button for its audit log.</p>

<h2 id="own-account">Your own security</h2>
<p><em>Security</em> in the console sets up your authenticator. It cannot be turned off from the dashboard; someone with server access runs <code class="inline">php artisan admin:reset-2fa &lt;email&gt;</code>, which also ends that operator's sessions.</p>
