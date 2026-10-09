<div class="docs-eyebrow">Getting Started</div>
<h1>Response Format</h1>

<p>Every endpoint returns JSON. The envelope is consistent for successes but has a few historical variations on errors, which clients must handle.</p>

<h2 id="success">Success envelope</h2>
<x-docs.code lang="json">{
  "status": true,
  "message": "Payment point created.",
  "data": { "id": "01J...", "name": "Till 2", "status": "active" }
}</x-docs.code>
<p>List endpoints add a <code class="inline">meta</code> object when paginated: <code class="inline">{ "total": 42, "current_page": 1, "last_page": 3 }</code>. Creation endpoints return <strong>201</strong>; everything else returns <strong>200</strong>.</p>

<h2 id="validation-errors-are-http-200">Validation errors are HTTP 200</h2>
<p>Request validation (the <code class="inline">FormRequest</code> classes behind registration, login, payments, gates, settings, etc.) is deliberately converted to a 200 response containing only the <em>first</em> error message:</p>
<x-docs.code lang="json">{
  "status": false,
  "message": "PIN must be exactly 4 digits."
}</x-docs.code>

<x-docs.callout type="danger" title="Always check the body">
  <p>Treat a response as successful only when <code class="inline">status === true</code>. An HTTP 200 does not mean the operation worked.</p>
</x-docs.callout>

<p>A few endpoints validate inline (<code class="inline">GET /gates</code>, <code class="inline">POST /pay/decode-qr</code>, <code class="inline">/settings/activity/*</code>, <code class="inline">/kyc/check-enrollment</code>, <code class="inline">/device/token</code>, <code class="inline">/device/offline-key</code>). Those return Laravel's standard <strong>422</strong>:</p>
<x-docs.code lang="json">{
  "message": "The account id field is required.",
  "errors": { "account_id": ["The account id field is required."] }
}</x-docs.code>

<h2 id="business-errors">Business-rule errors</h2>
<p>Domain failures (wrong PIN, fraud blocks, unknown resource) use real HTTP codes — see <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'errors']) }}">Errors &amp; Status Codes</a>:</p>
<x-docs.code lang="json">{ "status": false, "message": "Invalid or expired transaction reference." }</x-docs.code>

<h2 id="key-variations">Known variations</h2>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Where</th><th>Variation</th></tr></thead>
  <tbody>
    <tr><td>Middleware errors (<code class="inline">SESSION_EXPIRED</code>, <code class="inline">ACCOUNT_LOCKED</code>, <code class="inline">DEVICE_SUSPENDED</code>, <code class="inline">SERVICE_SUSPENDED</code>)</td><td>Use <code class="inline">"success": false</code> instead of <code class="inline">"status"</code>, plus a machine-readable <code class="inline">code</code>.</td></tr>
    <tr><td><code class="inline">POST /auth/verify-pin</code> (wrong PIN), <code class="inline">PIN_LOCKED</code> responses</td><td>Use <code class="inline">"success": false</code>.</td></tr>
    <tr><td><code class="inline">POST /device/token</code></td><td>Returns <code class="inline">{ "success": true }</code> with no message.</td></tr>
    <tr><td><code class="inline">POST /device/offline-key</code>, <code class="inline">GET /device/offline-voice-number</code></td><td>No envelope: raw <code class="inline">{ "device_key": ... }</code> / <code class="inline">{ "message": ... }</code>.</td></tr>
    <tr><td><code class="inline">POST /pay/initiate</code> needing PIN</td><td><code class="inline">202</code> with <code class="inline">{ "require_pin": true }</code> and no <code class="inline">status</code>.</td></tr>
  </tbody>
</table>
</div>
<p>A robust client reads <code class="inline">body.status ?? body.success</code>, then falls back to the HTTP status code and the optional <code class="inline">code</code> field.</p>

<h2 id="formatting">Money, dates and display strings</h2>
<ul>
  <li><strong>Request amounts</strong> are plain numbers in naira (<code class="inline">2500</code> or <code class="inline">2500.50</code>), between ₦1 and ₦9,999,999.99.</li>
  <li><strong>Response amounts</strong> are usually <em>pre-formatted display strings</em> (<code class="inline">"₦2,500.00"</code>, <code class="inline">"+₦2,500.00"</code>, <code class="inline">"NGN 2,500.00"</code>). Where a number is needed the response carries a raw field (e.g. <code class="inline">amount_raw</code>). Do not parse the display strings.</li>
  <li><strong>Timestamps</strong> are either display strings (<code class="inline">"Oct 08, 2026 • 3:14 PM"</code>, <code class="inline">"Today • 3:14 PM"</code>) or ISO 8601 / <code class="inline">Y-m-d H:i:s</code>, depending on the endpoint; each page shows which.</li>
</ul>
