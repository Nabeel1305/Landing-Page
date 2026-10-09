<div class="docs-eyebrow">Payment Points</div>
<h1>Overview &amp; Management</h1>

<p>A <strong>Payment Point</strong> (called a <em>gate</em> in the API paths and database) is a named receiving point attached to one of a business user's bank accounts — "Till 2", "Front desk", "Online orders". Each has its own QR code, so the owner can see exactly where each naira came from. Money still lands in the same bank account; the point only tags and reports it.</p>

<x-docs.callout type="warn" title="Business accounts only">
  <p>Every endpoint on this page requires <code class="inline">account_type: "business"</code> (chosen at <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}#register">registration</a>). A personal account gets <strong>403</strong> with the business-account-required message. Ids look like <code class="inline">gate-Q3w9ZkLm</code>.</p>
</x-docs.callout>

<h2 id="lifecycle">Lifecycle</h2>
<x-docs.code lang="text">create ─▶ active ◀──▶ archived
              │           │
              └── delete ─┘   (only when it has no transactions)</x-docs.code>
<ul>
  <li><strong>Active</strong> points can generate QR codes and receive payments.</li>
  <li><strong>Archived</strong> points keep their history but their QR codes stop working ("This payment point is no longer available.").</li>
  <li>A point with any transaction can only be archived, never deleted.</li>
</ul>

<h2 id="create">Create</h2>
<x-docs.endpoint method="POST" path="/gates" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">account_id</code></td><td>Required. A bank account the caller owns (404 <code class="inline">Bank account not found.</code> otherwise)</td></tr>
    <tr><td><code class="inline">name</code></td><td>Required, 2–100 chars</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">// 201
{ "status": true, "message": "Payment point created.", "data": { "id": "gate-Q3w9ZkLm", "name": "Till 2", "status": "active" } }</x-docs.code>
<x-docs.try-it method="POST" path="/gates" :fields="[
    ['name' => 'account_id', 'in' => 'body', 'required' => true, 'placeholder' => 'acc-xxxxxxxx'],
    ['name' => 'name', 'in' => 'body', 'required' => true, 'placeholder' => 'Till 2'],
]" warning="Creates a real payment point." />

<h2 id="list">List points on an account</h2>
<x-docs.endpoint method="GET" path="/gates" />
<p>Query: <code class="inline">account_id</code> (required), <code class="inline">active_only</code> (boolean). Returns the caller's own points on that account.</p>
<x-docs.code lang="json">{
  "status": true,
  "data": [ { "id": "gate-Q3w9ZkLm", "name": "Till 2", "status": "active", "qr_id": "48201937", "created_at": "2026-10-02T10:00:00.000000Z" } ]
}</x-docs.code>
<x-docs.try-it method="GET" path="/gates" :mutating="false" :fields="[
    ['name' => 'account_id', 'in' => 'query', 'required' => true],
    ['name' => 'active_only', 'in' => 'query', 'type' => 'select', 'options' => ['', 'true', 'false']],
]" />

<h2 id="mine">List everything I can use</h2>
<x-docs.endpoint method="GET" path="/gates/mine" />
<p>Every point the caller <strong>owns</strong> plus every point they have been <strong>connected</strong> to by share code (see <a href="{{ route('docs.show', ['section' => 'payment-points', 'page' => 'sharing']) }}">Sharing</a>), with totals. Query: <code class="inline">active_only</code>.</p>
<x-docs.code lang="json">{
  "status": true,
  "data": [
    {
      "id": "gate-Q3w9ZkLm", "name": "Till 2", "status": "active", "is_owner": true,
      "bank": "Guaranty Trust Bank", "last4": "6789", "qr_id": "48201937",
      "total_received": 90000.0, "payment_count": 30
    }
  ]
}</x-docs.code>
<x-docs.try-it method="GET" path="/gates/mine" :mutating="false" />

<h2 id="manage">Rename, archive, reactivate, delete</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Method</th><th>Path</th><th>Body</th><th>Success message</th></tr></thead>
  <tbody>
    <tr><td>PATCH</td><td><code class="inline">/gates/{id}/rename</code></td><td><code class="inline">name</code> (2–100)</td><td><code class="inline">Payment point renamed.</code> + <code class="inline">data</code></td></tr>
    <tr><td>PATCH</td><td><code class="inline">/gates/{id}/archive</code></td><td>—</td><td><code class="inline">Payment point archived.</code></td></tr>
    <tr><td>PATCH</td><td><code class="inline">/gates/{id}/reactivate</code></td><td>—</td><td><code class="inline">Payment point reactivated.</code></td></tr>
    <tr><td>DELETE</td><td><code class="inline">/gates/{id}</code></td><td>—</td><td><code class="inline">Payment point deleted.</code> (422 if it has history)</td></tr>
  </tbody>
</table></div>
<p>Only the <strong>owner</strong> can manage a point; connected users can generate QR codes and see reporting but not change it. Unknown or foreign ids → 404 <code class="inline">Payment point not found.</code></p>
<x-docs.try-it method="PATCH" path="/gates/{id}/rename" :fields="[['name' => 'name', 'in' => 'body', 'required' => true]]" warning="Renames a real payment point." />
<x-docs.try-it method="PATCH" path="/gates/{id}/archive" warning="Disables the point's QR code." />
<x-docs.try-it method="PATCH" path="/gates/{id}/reactivate" />
<x-docs.try-it method="DELETE" path="/gates/{id}" :high-stakes="true" warning="Permanently deletes the point (only if it has no transactions)." />
