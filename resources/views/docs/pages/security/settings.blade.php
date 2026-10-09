<div class="docs-eyebrow">Security &amp; Identity</div>
<h1>Security Settings</h1>

<p>Per-user feature switches and limits, stored in <code class="inline">user_settings</code>. Endpoints live under <code class="inline">/settings/security</code>.</p>

<h2 id="read">Read settings</h2>
<x-docs.endpoint method="GET" path="/settings/security" />
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "biometrics_enabled": false,
    "quick_pay_enabled": false,
    "offline_payments_enabled": true,
    "payment_alerts_enabled": true,
    "spending_daily_limit": 200000,
    "spending_per_transaction": 50000
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/settings/security" :mutating="false" />

<h2 id="toggle">Toggle a feature</h2>
<x-docs.endpoint method="POST" path="/settings/security/toggle" />
<p>Send <strong>one</strong> setting key set to a boolean. The first recognised key in the body is the one that changes.</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Key</th><th>Effect</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">biometrics_enabled</code></td><td>Records that the user opted in to device biometrics (unlock is enforced client-side).</td></tr>
    <tr><td><code class="inline">quick_pay_enabled</code></td><td>Makes <code class="inline">POST /pay/initiate</code> complete payments immediately, skipping PIN confirmation. See <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'sending']) }}#initiate">Sending Money</a>.</td></tr>
    <tr><td><code class="inline">offline_payments_enabled</code></td><td>Required for the <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'overview']) }}">offline rail</a> to accept calls from this account.</td></tr>
    <tr><td><code class="inline">payment_alerts_enabled</code></td><td>Transaction alert preference (default on).</td></tr>
  </tbody>
</table></div>
<p>Also send <code class="inline">pin</code> (the 4-digit transaction PIN). It is checked whenever a setting is being <em>enabled</em> (turning one off needs no PIN). A wrong PIN returns 422 <code class="inline">Incorrect PIN. Setting was not changed.</code></p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/settings/security/toggle \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "quick_pay_enabled": true, "pin": "4821" }'</x-docs.code>
<x-docs.code lang="json">{ "status": true, "message": "Quick pay enabled enabled.", "data": { "feature": "quick_pay_enabled", "enabled": true } }</x-docs.code>
<x-docs.callout type="note" title="Message wording">
  <p>The <code class="inline">message</code> is generated from the key name and reads awkwardly (e.g. "Quick pay enabled enabled."). Don't display it; use <code class="inline">data.enabled</code>.</p>
</x-docs.callout>
<x-docs.try-it method="POST" path="/settings/security/toggle" :fields="[
    ['name' => 'quick_pay_enabled', 'in' => 'body', 'type' => 'boolean'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password'],
]" warning="Changes a real security setting. Only send one setting key at a time (the Try-It form always sends quick_pay_enabled)." />
