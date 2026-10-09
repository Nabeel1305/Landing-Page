<div class="docs-eyebrow">Reference</div>
<h1>Changelog</h1>

<p>Notable changes to the Offline Payments API and developer portal, most recent first.</p>

<h2 id="2026-10-09">9 October 2026</h2>
<ul>
  <li><strong>Developer portal</strong> at <code class="inline">/portal</code>: create and revoke API keys, edit, pause, rotate and delete webhook endpoints, send signed test events, inspect every webhook delivery (status, attempts, exact payload) and retry failed ones.</li>
  <li><strong>CORS</strong> is now enabled for <code class="inline">/api/*</code>, so browser-based tools can call the API. Keep API keys on your servers.</li>
</ul>

<h2 id="2026-10-06">6 October 2026</h2>
<ul>
  <li><strong>Initial release of API v1:</strong> <code class="inline">PUT /subscribers/{reference}</code>, <code class="inline">PUT /merchants/{reference}</code>, <code class="inline">POST /webhook-endpoints</code>, <code class="inline">POST /codes</code>, <code class="inline">GET /codes/{id}</code>, <code class="inline">POST /codes/{id}/cancel</code>, <code class="inline">GET /transactions/{id}</code>.</li>
  <li>Idempotent issue and cancel; signed webhooks (<code class="inline">code.redeemed</code>, <code class="inline">transaction.settled</code>, <code class="inline">transaction.failed</code>, <code class="inline">code.expired</code>) with retries up to seven attempts.</li>
  <li>Voice redemption with own or shared numbers and optional caller binding; sandbox settlement adapter; automatic expiry and reconciliation of lost captures.</li>
  <li>PHP and JavaScript client libraries and an OpenAPI description.</li>
</ul>

<x-docs.callout type="note" title="Not yet available">
  <p>Real settlement adapters (only the sandbox simulator exists), live accounts, additional telephony providers, per-key permissions, and an API to list or delete webhook endpoints.</p>
</x-docs.callout>
