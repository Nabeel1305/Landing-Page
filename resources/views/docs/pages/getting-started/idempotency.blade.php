<div class="docs-eyebrow">Getting Started</div>
<h1>Idempotency</h1>

<p>Networks fail. If <code class="inline">POST /codes</code> times out you don't know whether the code was issued — and issuing twice would place two holds. The <code class="inline">Idempotency-Key</code> header makes retries safe.</p>

<h2 id="which-endpoints">Which endpoints need it</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Endpoint</th><th><code class="inline">Idempotency-Key</code></th></tr></thead>
  <tbody>
    <tr><td><code class="inline">POST /codes</code></td><td><strong>Required</strong></td></tr>
    <tr><td><code class="inline">POST /codes/{id}/cancel</code></td><td><strong>Required</strong></td></tr>
    <tr><td><code class="inline">PUT /subscribers/{reference}</code>, <code class="inline">PUT /merchants/{reference}</code></td><td>Not needed — upserts are naturally repeatable</td></tr>
    <tr><td><code class="inline">POST /webhook-endpoints</code></td><td>Not supported — a retry creates another endpoint (and another secret)</td></tr>
    <tr><td>All <code class="inline">GET</code> requests</td><td>Not needed</td></tr>
  </tbody>
</table></div>

<h2 id="how-it-works">How it works</h2>
<ol>
  <li>Generate a fresh value (a UUID is ideal, max 100 characters) for each <em>intended</em> operation — one per payment attempt.</li>
  <li>Send it as <code class="inline">Idempotency-Key: &lt;value&gt;</code>.</li>
  <li>If you must retry (timeout, connection reset, 5xx), send the <strong>same key and the same body</strong>. You get the original response back, with the header <code class="inline">Idempotent-Replayed: true</code> — and the same code, never a second one.</li>
</ol>

<h2 id="rules">Rules</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Situation</th><th>Result</th></tr></thead>
  <tbody>
    <tr><td>Header missing or longer than 100 characters</td><td><code class="inline">422 idempotency_key_required</code></td></tr>
    <tr><td>Same key, <strong>different</strong> request (path, method or body)</td><td><code class="inline">422 idempotency_key_reused</code> — a key is bound to one request</td></tr>
    <tr><td>Same key while the first copy is still running</td><td><code class="inline">409 request_in_progress</code> — wait a moment and retry</td></tr>
    <tr><td>First attempt failed with a <code class="inline">5xx</code></td><td>Not remembered: retrying with the same key runs the request again</td></tr>
    <tr><td>First attempt returned a <code class="inline">4xx</code> (validation, settlement rejected…)</td><td>Remembered: the same error is replayed. Use a <strong>new key</strong> after fixing the request.</td></tr>
    <tr><td>Key age</td><td>Kept for 24 hours, then pruned</td></tr>
  </tbody>
</table></div>

<x-docs.callout type="note" title="Keys are scoped to your organisation">
  <p>Your keys never collide with another tenant's. The stored copy of a replayed response is encrypted at rest, because the first response to <code class="inline">POST /codes</code> contains the live code.</p>
</x-docs.callout>

<h2 id="example">Example</h2>
<x-docs.code lang="js">// Node, using the official client (it generates a key if you don't pass one)
const key = crypto.randomUUID();                 // store this with your payment attempt
try {
  code = await client.issueCode(params, key);
} catch (e) {
  if (e.status >= 500 || e.name !== 'ApiError') {
    code = await client.issueCode(params, key);  // same key: same code, never two
  } else { throw e; }
}</x-docs.code>
