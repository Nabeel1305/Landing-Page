<div class="docs-eyebrow">Getting Started</div>
<h1>Errors &amp; Status Codes</h1>

<p>Read <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'response-format']) }}">Response Format</a> first: validation errors are HTTP 200 with <code class="inline">"status": false</code>. This page lists what everything else looks like.</p>

<h2 id="error-shape">Error shape</h2>
<x-docs.code lang="json">{
  "status": false,
  "message": "Incorrect PIN. Setting was not changed."
}</x-docs.code>
<p>Some errors add a machine-readable <code class="inline">code</code> (string) your client should branch on instead of matching <code class="inline">message</code>:</p>
<x-docs.code lang="json">{
  "success": false,
  "message": "Your account has been temporarily suspended. Please contact support.",
  "code": "ACCOUNT_LOCKED"
}</x-docs.code>

<h2 id="status-codes">HTTP status codes</h2>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Code</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td>200</td><td>Success — <em>or</em> a validation failure (<code class="inline">status:false</code>). Check the body.</td></tr>
    <tr><td>201</td><td>Resource created (registration, linked account, payment point)</td></tr>
    <tr><td>202</td><td><code class="inline">POST /pay/initiate</code> needs PIN re-entry (<code class="inline">require_pin: true</code>)</td></tr>
    <tr><td>401</td><td>Missing/invalid token, <code class="inline">SESSION_EXPIRED</code>, or bad phone/PIN at login</td></tr>
    <tr><td>403</td><td>Locked account/device, PIN lock, fraud HARD_BLOCK, or a business-only feature used on a personal account</td></tr>
    <tr><td>404</td><td>Resource not found — or not owned by the caller (the two are deliberately indistinguishable)</td></tr>
    <tr><td>409</td><td>Device conflict on offline-key provisioning</td></tr>
    <tr><td>422</td><td>Business rule violated (wrong PIN/OTP, limits, invalid state) or inline validation failure</td></tr>
    <tr><td>423</td><td>Offline-key provisioning while PIN-locked</td></tr>
    <tr><td>429</td><td>Rate limit or fraud SOFT_BLOCK (velocity, OTP abuse)</td></tr>
    <tr><td>500</td><td>Unexpected error. Always the generic body <code class="inline">"Something went wrong. Please try again."</code> — details go to logs only</td></tr>
    <tr><td>503</td><td><code class="inline">SERVICE_SUSPENDED</code> (kill switch) or an upstream provider (KYC) is unavailable</td></tr>
  </tbody>
</table>
</div>

<h2 id="error-codes">Named error codes</h2>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th><code class="inline">code</code></th><th>HTTP</th><th>Meaning / client action</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">SESSION_EXPIRED</code></td><td>401</td><td>Token idle for more than 30 minutes (it has been revoked). Send the user to login.</td></tr>
    <tr><td><code class="inline">ACCOUNT_LOCKED</code></td><td>403</td><td>An operator locked the account. Show "contact support"; do not retry.</td></tr>
    <tr><td><code class="inline">DEVICE_SUSPENDED</code></td><td>403</td><td>This device was suspended. Contact support to re-verify.</td></tr>
    <tr><td><code class="inline">SERVICE_SUSPENDED</code></td><td>503</td><td>Global kill switch is active. Retry later with back-off.</td></tr>
    <tr><td><code class="inline">PIN_LOCKED</code></td><td>403</td><td>Too many wrong PINs (5 in 15 minutes). PIN entry is locked for 15 minutes <em>and</em> the account is flagged locked, so later calls return <code class="inline">ACCOUNT_LOCKED</code> until an operator unlocks it.</td></tr>
    <tr><td>Fraud event types (<code class="inline">velocity_*</code>, <code class="inline">qr_*</code>, <code class="inline">otp_*</code>, <code class="inline">pin_brute_force</code>, <code class="inline">offline_velocity_*</code> …)</td><td>403 / 429 / 422</td><td>Returned as <code class="inline">code</code> on payment and QR endpoints. HTTP status follows severity: HARD_BLOCK→403, SOFT_BLOCK→429, WARN→422. Full list in <a href="{{ route('docs.show', ['section' => 'platform', 'page' => 'fraud-engine']) }}">Fraud Engine</a>.</td></tr>
  </tbody>
</table>
</div>

<h2 id="handling">Recommended client handling</h2>
<ol>
  <li>Parse JSON; if it is not JSON, treat as a network/proxy error.</li>
  <li>If <code class="inline">code === "SESSION_EXPIRED"</code> → clear the token and show login.</li>
  <li>If <code class="inline">code</code> is <code class="inline">ACCOUNT_LOCKED</code>, <code class="inline">DEVICE_SUSPENDED</code> or <code class="inline">SERVICE_SUSPENDED</code> → show a blocking screen.</li>
  <li>If HTTP 429 → honour <code class="inline">Retry-After</code> and back off.</li>
  <li>Otherwise success is <code class="inline">(body.status ?? body.success) === true</code>; on failure show <code class="inline">body.message</code>.</li>
</ol>
