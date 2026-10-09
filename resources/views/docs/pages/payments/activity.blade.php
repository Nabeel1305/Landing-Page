<div class="docs-eyebrow">Payments</div>
<h1>Activity &amp; Reports</h1>

<p>Read-only endpoints under <code class="inline">/settings/activity</code> that power the history and insight screens. They only ever return the caller's <code class="inline">completed</code> transactions. Inline validation failures here are standard Laravel <strong>422</strong> responses.</p>

<h2 id="transactions">Transaction history</h2>
<x-docs.endpoint method="GET" path="/settings/activity/transactions" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Query param</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">type</code></td><td><code class="inline">sent</code> | <code class="inline">received</code></td></tr>
    <tr><td><code class="inline">date_from</code>, <code class="inline">date_to</code></td><td>Dates; <code class="inline">date_to</code> ≥ <code class="inline">date_from</code></td></tr>
    <tr><td><code class="inline">payment_point_only</code></td><td>Boolean — only payments that came through a Payment Point</td></tr>
    <tr><td><code class="inline">per_page</code></td><td>5–100 (default 20)</td></tr>
    <tr><td><code class="inline">page</code></td><td>Standard Laravel pagination</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">{
  "status": true,
  "data": [
    {
      "type": "received",
      "name": "CHIDI EZE",
      "bank": "Access Bank",
      "time": "Today • 3:14 PM",
      "amount": "+₦2,500.00",
      "status": "completed",
      "reference": "TXN-9K2LQ7ZP4XMA-RCV",
      "narration": "Lunch",
      "channel": "mobile_app",
      "payment_point_name": "Till 2",
      "sender_name": "CHIDI EZE",
      "sender_bank": "Access Bank",
      "sender_account_no": "0987654321",
      "recipient_name": "Amaka Okonkwo",
      "recipient_bank": "Guaranty Trust Bank",
      "recipient_account_no": "0123456789"
    }
  ],
  "meta": { "total": 12, "current_page": 1, "last_page": 1 }
}</x-docs.code>
<p><code class="inline">name</code>/<code class="inline">bank</code> are always the <em>counterparty</em>. <code class="inline">channel</code> is the origin of the payment (<code class="inline">mobile_app</code> for online, an offline-rail value for call payments). <code class="inline">time</code> is a human display string; use <code class="inline">reference</code> for identity.</p>
<x-docs.try-it method="GET" path="/settings/activity/transactions" :mutating="false" :fields="[
    ['name' => 'type', 'in' => 'query', 'type' => 'select', 'options' => ['', 'sent', 'received']],
    ['name' => 'per_page', 'in' => 'query', 'placeholder' => '20'],
    ['name' => 'page', 'in' => 'query', 'placeholder' => '1'],
]" />

<h2 id="analytics">Monthly analytics</h2>
<x-docs.endpoint method="GET" path="/settings/activity/analytics" />
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "spent_this_month": "₦48,200.00",
    "received_this_month": "₦120,000.00",
    "transactions_this_month": 14,
    "total_sent_count": 63,
    "total_received_count": 41,
    "month_over_month_change": "12.5%",
    "period": "October 2026"
  }
}</x-docs.code>
<p><code class="inline">month_over_month_change</code> compares spending with last month and is <code class="inline">null</code> when last month had none.</p>
<x-docs.try-it method="GET" path="/settings/activity/analytics" :mutating="false" />

<h2 id="report">Financial report <span class="docs-endpoint-auth is-auth">business only</span></h2>
<x-docs.endpoint method="GET" path="/settings/activity/report" />
<p>Inflow/outflow summary plus a chart series. Personal accounts get 403 <code class="inline">Financial Report is only available on a business account.</code></p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Query param</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">period</code></td><td>Required: <code class="inline">day</code> (hourly buckets), <code class="inline">week</code>, <code class="inline">month</code> (daily buckets), <code class="inline">custom</code></td></tr>
    <tr><td><code class="inline">date_from</code>, <code class="inline">date_to</code></td><td>Required when <code class="inline">custom</code></td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "period": "week",
    "date_from": "2026-10-05",
    "date_to": "2026-10-11",
    "summary": {
      "total_inflow": "NGN 120,000.00",
      "total_outflow": "NGN 48,200.00",
      "total_payment_point": "NGN 90,000.00",
      "net": "NGN 71,800.00",
      "inflow_count": 41,
      "outflow_count": 14,
      "payment_point_count": 30
    },
    "chart": [ { "label": "Oct 5", "inflow": 12000.0, "outflow": 0.0 } ]
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/settings/activity/report" :mutating="false" :fields="[
    ['name' => 'period', 'in' => 'query', 'type' => 'select', 'options' => ['day', 'week', 'month', 'custom'], 'required' => true],
    ['name' => 'date_from', 'in' => 'query'],
    ['name' => 'date_to', 'in' => 'query'],
]" />

<h2 id="payment-points">Payment Point breakdown <span class="docs-endpoint-auth is-auth">business only</span></h2>
<x-docs.endpoint method="GET" path="/settings/activity/payment-points" />
<p>Same <code class="inline">period</code>/<code class="inline">date_from</code>/<code class="inline">date_to</code> parameters; one entry per point that received money in the window.</p>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "period": "month", "date_from": "2026-10-01", "date_to": "2026-10-31",
    "payment_points": [
      {
        "gate_id": "gate-Q3w9ZkLm",
        "gate_name": "Till 2",
        "is_archived": false,
        "total_received": "NGN 90,000.00",
        "transaction_count": 30,
        "average_amount": "NGN 3,000.00",
        "last_received_at": "2026-10-08T14:14:00+00:00",
        "chart": [ { "label": "Oct 1", "amount": 3000.0 } ]
      }
    ]
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/settings/activity/payment-points" :mutating="false" :fields="[
    ['name' => 'period', 'in' => 'query', 'type' => 'select', 'options' => ['day', 'week', 'month', 'custom'], 'required' => true],
    ['name' => 'date_from', 'in' => 'query'],
    ['name' => 'date_to', 'in' => 'query'],
]" />
