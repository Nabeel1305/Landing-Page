<div class="docs-eyebrow">API Reference</div>
<h1>Transactions</h1>

<p>A <strong>transaction</strong> is created the moment a code is successfully redeemed by a phone call. It records the outcome of moving the money. Every redeemed code has exactly one.</p>

<h2 id="read">Read a transaction</h2>
<x-docs.endpoint method="GET" path="/transactions/{id}" />
<p><code class="inline">id</code> is the transaction's UUID — the <code class="inline">data.id</code> of the <code class="inline">transaction.*</code> webhooks. (A code's id is not a transaction id; use <code class="inline">code_id</code> in the response to go back.)</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/transactions/6b1f2a90-… -H "Authorization: Bearer $KEY"</x-docs.code>
<x-docs.code lang="json">{
  "id": "6b1f2a90-3e0c-4c7a-8d11-52f7a4b0c9de",
  "code_id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
  "reference": "TXN-9K2LQ7ZP4XMA",
  "status": "settled",
  "amount_minor": 250000,
  "currency": "NGN",
  "settlement_reference": "settle_2f6a…",
  "failure_reason": null
}</x-docs.code>
<x-docs.try-it method="GET" path="/transactions/{id}" :mutating="false" />

<h2 id="fields">Fields</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">id</code></td><td>UUID</td></tr>
    <tr><td><code class="inline">code_id</code></td><td>The payment code this transaction belongs to</td></tr>
    <tr><td><code class="inline">reference</code></td><td><code class="inline">TXN-</code> + 12 characters. This is the key the platform sends your system on <strong>capture</strong> and on every status query — make capture idempotent on it.</td></tr>
    <tr><td><code class="inline">status</code></td><td><code class="inline">pending</code> | <code class="inline">settled</code> | <code class="inline">failed</code></td></tr>
    <tr><td><code class="inline">amount_minor</code>, <code class="inline">currency</code></td><td>Copied from the code</td></tr>
    <tr><td><code class="inline">settlement_reference</code></td><td>The reference your system returned when it captured; <code class="inline">null</code> until settled</td></tr>
    <tr><td><code class="inline">failure_reason</code></td><td>Your system's decline reason, or "The tenant released the hold."; <code class="inline">null</code> unless failed</td></tr>
  </tbody>
</table></div>

<h2 id="statuses">Statuses</h2>
<ul>
  <li><strong>pending</strong> — the call was verified and your system has been (or is being) asked to capture. A short pending period is normal. If the capture call never answered, the platform asks your system what happened every five minutes (<a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'lifecycle']) }}">reconciliation</a>) and settles or fails the transaction on a clear answer.</li>
  <li><strong>settled</strong> — final. The webhook <code class="inline">transaction.settled</code> was (or will be) sent.</li>
  <li><strong>failed</strong> — final. The hold has been released. <code class="inline">transaction.failed</code> was sent.</li>
</ul>
<p>Prefer webhooks over polling; read this endpoint when you need the current truth, for instance after missing a webhook.</p>

<p>There is no list endpoint. Keep your own record of each payment, keyed by the transaction <code class="inline">id</code> from the webhooks.</p>
