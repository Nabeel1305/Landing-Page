<div class="docs-eyebrow">Reference</div>
<h1>Changelog</h1>

<p>Notable changes to the Offline Payments API and developer portal, most recent first.</p>

<h2 id="2026-10-10">10 October 2026</h2>
<ul>
  <li><strong>Bank accounts are now required.</strong> <code class="inline">PUT /subscribers/{reference}</code> requires <code class="inline">account_number</code> and <code class="inline">bank_code</code> (the account funds are held on and debited from). <code class="inline">PUT /merchants/{reference}</code> requires <code class="inline">name</code>, <code class="inline">account_number</code> and <code class="inline">bank_code</code> (the account credited); <code class="inline">account_reference</code> becomes an optional label. A merchant created on the fly with <code class="inline">POST /codes</code> needs the same fields. <strong>Breaking:</strong> existing integrations must send the new fields, and subscribers and merchants registered before this change must be re-registered before they can be used for a new code (<code class="inline">POST /codes</code> answers 422 until then).</li>
  <li><code class="inline">source_account_reference</code> on <code class="inline">POST /codes</code> is now optional — funds are held on the subscriber's registered account. Both accounts are frozen on the code when it is issued, so editing a merchant never redirects a payment in flight.</li>
  <li>The settlement adapter now receives account number and bank code for both <code class="inline">hold</code> (subscriber) and <code class="inline">capture</code> (merchant).</li>
  <li><strong>Merchants can be created while issuing a code.</strong> <code class="inline">POST /codes</code> accepts an optional <code class="inline">merchant</code> object (<code class="inline">name</code>, <code class="inline">account_number</code>, <code class="inline">bank_code</code>); an unknown <code class="inline">merchant_reference</code> is registered together with the code, only if the hold succeeds. The response gains <code class="inline">merchant_created</code>. Existing merchants are never changed this way, and a conflicting account is refused with 422.</li>
</ul>

<h2 id="2026-10-09">9 October 2026</h2>
<ul>
  <li><strong><code class="inline">POST /codes</code> now returns dialling fields:</strong> <code class="inline">voice_number</code>, <code class="inline">dial_string</code> (number, pauses, code and <code class="inline">#</code> in one string) and <code class="inline">dial_uri</code> (a <code class="inline">tel:</code> link). They are <code class="inline">null</code> until a voice number is set up for the account. Existing fields are unchanged.</li>
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
