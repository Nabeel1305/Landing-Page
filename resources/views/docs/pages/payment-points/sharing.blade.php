<div class="docs-eyebrow">Payment Points</div>
<h1>Sharing &amp; Reporting</h1>

<p>An owner can give staff or partners access to a point without handing over the bank account. The owner generates an 8-digit <strong>share code</strong>; the other business user redeems it and the point appears in their <code class="inline">/gates/mine</code> list. They can then show that point's QR and see its totals.</p>

<h2 id="share">Get or create a share code</h2>
<x-docs.endpoint method="POST" path="/gates/{id}/share" />
<p>Returns the point's existing code, or creates one on first use. Owner only.</p>
<x-docs.code lang="json">{ "status": true, "message": "Share code generated.", "data": { "code": "48201937" } }</x-docs.code>
<x-docs.try-it method="POST" path="/gates/{id}/share" />

<h2 id="regenerate">Regenerate (revoke the old code)</h2>
<x-docs.endpoint method="POST" path="/gates/{id}/share/regenerate" />
<p>Issues a new code and invalidates the old one. Users already connected stay connected — regenerating only stops <em>new</em> connections with the old code.</p>
<x-docs.code lang="json">{ "status": true, "message": "New share code generated. The old code no longer works.", "data": { "code": "73019284" } }</x-docs.code>
<x-docs.try-it method="POST" path="/gates/{id}/share/regenerate" :high-stakes="true" warning="Invalidates the current code immediately." />

<h2 id="connect">Connect with a code</h2>
<x-docs.endpoint method="POST" path="/gates/connect" note="Throttle: gate-connect (6/min per user)" />
<p>Body: <code class="inline">code</code> — exactly 8 digits. The caller must be a business account and cannot connect to their own point.</p>
<x-docs.code lang="json">{ "status": true, "message": "Connected to payment point.", "data": { "id": "gate-Q3w9ZkLm", "name": "Till 2", "status": "active" } }</x-docs.code>
<x-docs.callout type="danger" title="Brute-force protection">
  <p>The 8-digit code is a shared secret, so redemption is guarded three ways: a 6-per-minute HTTP throttle, a per-user lockout after <strong>5 wrong codes in 15 minutes</strong> (<code class="inline">Too many incorrect payment point codes. Try again in 15 minutes.</code>), and a deliberately identical error for a wrong code and an own-point attempt (<code class="inline">Invalid or expired payment point code.</code>).</p>
</x-docs.callout>
<x-docs.try-it method="POST" path="/gates/connect" :fields="[['name' => 'code', 'in' => 'body', 'required' => true, 'placeholder' => '8 digits']]" warning="Adds the point to your account." />

<h2 id="summary">Summary of one point</h2>
<x-docs.endpoint method="GET" path="/gates/{id}/summary" />
<p>Available to owners and connected users.</p>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "gate_id": "gate-Q3w9ZkLm", "name": "Till 2", "status": "active",
    "total_received": 90000.0, "payment_count": 30, "bank_account_id": "acc-k3j2h4g5"
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/gates/{id}/summary" :mutating="false" />

<h2 id="transactions">Transactions through a point</h2>
<x-docs.endpoint method="GET" path="/gates/{id}/transactions" />
<p>25 per page, newest first. Standard Laravel pagination via <code class="inline">?page=</code>. <code class="inline">data</code> is the raw transaction rows.</p>
<x-docs.code lang="json">{ "status": true, "data": [ { "reference": "TXN-...-RCV", "amount": "2500.00", "status": "completed", "...": "..." } ], "meta": { "total": 30, "current_page": 1, "last_page": 2 } }</x-docs.code>
<x-docs.try-it method="GET" path="/gates/{id}/transactions" :mutating="false" :fields="[['name' => 'page', 'in' => 'query', 'placeholder' => '1']]" />

<h2 id="combined">Combined totals for an account</h2>
<x-docs.endpoint method="GET" path="/gates/combined-totals" />
<p>Query: <code class="inline">account_id</code> (required). Total received on the account across <em>all</em> activity, plus a summary for each of the owner's points on it.</p>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "account_id": "acc-k3j2h4g5",
    "total_received": 150000.0,
    "payment_count": 52,
    "gates": [ { "gate_id": "gate-Q3w9ZkLm", "name": "Till 2", "total_received": 90000.0, "payment_count": 30, "status": "active", "bank_account_id": "acc-k3j2h4g5" } ]
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/gates/combined-totals" :mutating="false" :fields="[['name' => 'account_id', 'in' => 'query', 'required' => true]]" />
<p>For time-boxed reporting (daily/weekly/monthly, with charts) use <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'activity']) }}#payment-points">/settings/activity/payment-points</a>.</p>
