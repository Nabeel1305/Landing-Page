<div class="docs-eyebrow">Webhooks</div>
<h1>Events</h1>

<h2 id="envelope">Envelope</h2>
<p>Every delivery is an HTTP <code class="inline">POST</code> with a JSON body:</p>
<x-docs.code lang="json">{
  "id": "5e9c1f0a-72a6-4d1d-8b0e-6c0f5d2a91b4",
  "type": "transaction.settled",
  "created_at": "2026-10-09T15:03:24+00:00",
  "data": { }
}</x-docs.code>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">id</code></td><td>Unique per event. The same event can be delivered more than once (retries); <strong>de-duplicate on this</strong>. It is also sent as the <code class="inline">Offline-Event-Id</code> header.</td></tr>
    <tr><td><code class="inline">type</code></td><td>One of the types below</td></tr>
    <tr><td><code class="inline">created_at</code></td><td>When the event happened (ISO 8601)</td></tr>
    <tr><td><code class="inline">data</code></td><td>A <em>Code</em> object for <code class="inline">code.*</code> events, a <em>Transaction</em> object for <code class="inline">transaction.*</code> events</td></tr>
  </tbody>
</table></div>
<p>Headers: <code class="inline">Content-Type: application/json</code>, <code class="inline">Offline-Signature</code> (see <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'verifying']) }}">Verifying Signatures</a>) and <code class="inline">Offline-Event-Id</code>.</p>

<h2 id="types">Event types</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Type</th><th>When</th><th><code class="inline">data</code></th></tr></thead>
  <tbody>
    <tr><td><code class="inline">code.redeemed</code></td><td>A valid call was verified. The money has <em>not</em> moved yet.</td><td>Code (<code class="inline">state: "redeemed"</code>)</td></tr>
    <tr><td><code class="inline">transaction.settled</code></td><td>Your system captured the funds</td><td>Transaction (<code class="inline">status: "settled"</code>)</td></tr>
    <tr><td><code class="inline">transaction.failed</code></td><td>Capture was declined, or your system reported the hold released</td><td>Transaction (<code class="inline">status: "failed"</code>)</td></tr>
    <tr><td><code class="inline">code.expired</code></td><td>A code reached its deadline unused; its hold was released</td><td>Code (<code class="inline">state: "expired"</code>)</td></tr>
    <tr><td><code class="inline">webhook.test</code></td><td>You pressed "Send test" in the portal (always delivered to that endpoint only)</td><td><code class="inline">{ "message": … }</code></td></tr>
  </tbody>
</table></div>
<p>There is no event for a cancelled code — you cancel through the API, so you already know.</p>

<h2 id="payloads">Payloads</h2>
<p><strong>Code</strong> (<code class="inline">code.redeemed</code>, <code class="inline">code.expired</code>):</p>
<x-docs.code lang="json">{
  "id": "5e9c1f0a-…",
  "type": "code.redeemed",
  "created_at": "2026-10-09T15:03:21+00:00",
  "data": {
    "id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
    "state": "redeemed",
    "amount_minor": 250000,
    "currency": "NGN",
    "expires_at": "2026-10-09T15:10:00+00:00"
  }
}</x-docs.code>
<p><strong>Transaction</strong> (<code class="inline">transaction.settled</code>, <code class="inline">transaction.failed</code>):</p>
<x-docs.code lang="json">{
  "id": "a7c0…",
  "type": "transaction.failed",
  "created_at": "2026-10-09T15:03:25+00:00",
  "data": {
    "id": "6b1f2a90-3e0c-4c7a-8d11-52f7a4b0c9de",
    "code_id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
    "reference": "TXN-9K2LQ7ZP4XMA",
    "status": "failed",
    "amount_minor": 250000,
    "currency": "NGN",
    "settlement_reference": null,
    "failure_reason": "Insufficient funds."
  }
}</x-docs.code>

<h2 id="ordering">Ordering</h2>
<p>For one payment the order is <code class="inline">code.redeemed</code> → <code class="inline">transaction.settled</code> <em>or</em> <code class="inline">transaction.failed</code>. Retries and network timing mean events can still <strong>arrive out of order</strong>. Don't rely on arrival order: use the state in <code class="inline">data</code> (final states never go backwards), and when in doubt call <code class="inline">GET /transactions/{id}</code>.</p>
<p>A short delay between <code class="inline">code.redeemed</code> and the final event is normal. If your system didn't answer the capture call, the platform resolves it by <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'lifecycle']) }}">reconciliation</a> within minutes.</p>
