<div class="docs-eyebrow">Payments</div>
<h1>Receiving Money (QR)</h1>

<p>To get paid, a user shows a QR code. The QR encodes the receiving bank account and an HMAC <code class="inline">signature</code> over those fields, so the sender's app (and the server, in <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'sending']) }}#decode-qr">decode-qr</a>) can reject forged or altered codes. You can generate a QR for a plain linked account <em>or</em> for a <a href="{{ route('docs.show', ['section' => 'payment-points', 'page' => 'overview']) }}">Payment Point</a>, optionally locked to a fixed amount.</p>

<h2 id="qr-payload">QR payload</h2>
<x-docs.code lang="json">{
  "bank_code": "058",
  "account_no": "0123456789",
  "name": "AMAKA OKONKWO",
  "bank": "Guaranty Trust Bank",
  "qr_id": "48201937",
  "gate_id": "gate-Q3w9ZkLm",
  "signature": "base64-hmac...",
  "amount": 2500
}</x-docs.code>
<ul>
  <li><code class="inline">qr_id</code> — 8-digit identifier of the receiving account; it is also what <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'overview']) }}">offline payments</a> use to identify the receiver.</li>
  <li><code class="inline">gate_id</code> — present only for Payment Point QRs.</li>
  <li><code class="inline">amount</code> — present only for "Request a specific amount" QRs. It is added <em>after</em> signing and is not part of the signature.</li>
  <li><code class="inline">signature</code> — computed over the identity fields, never over <code class="inline">amount</code>. Treat <code class="inline">amount</code> as a hint, not a guarantee.</li>
</ul>

<h2 id="generate">Generate a QR</h2>
<x-docs.endpoint method="POST" path="/receive/qr" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">account_id</code></td><td>Required unless <code class="inline">gate_id</code> is given. Must be the caller's own linked account.</td></tr>
    <tr><td><code class="inline">gate_id</code></td><td>Required unless <code class="inline">account_id</code> is given. A point the caller owns or is connected to. Must be <code class="inline">active</code>.</td></tr>
    <tr><td><code class="inline">amount</code></td><td>Optional. ₦1 – ₦9,999,999.99</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/receive/qr \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "account_id": "acc-k3j2h4g5", "amount": 2500 }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "qr_base64": "iVBORw0KGgoAAAANSUhEUg...",
    "qr_payload": "{\"bank_code\":\"058\",...}",
    "account": {
      "label": "Guaranty Trust Bank (...6789)",
      "bank": "Guaranty Trust Bank",
      "account_no": "0123456789",
      "last4": "6789"
    },
    "gate": null,
    "share_text": "Send money to Amaka Okonkwo\nBank: Guaranty Trust Bank\nAccount No: 0123456789",
    "amount": "₦2,500.00"
  }
}</x-docs.code>
<p><code class="inline">qr_base64</code> is a 300×300 PNG (error-correction M): render it as <code class="inline">data:image/png;base64,&lt;qr_base64&gt;</code>. For a Payment Point, <code class="inline">account.label</code> becomes <code class="inline">Bank (...1234): Point name</code> and <code class="inline">gate</code> is <code class="inline">{ "id", "name" }</code>.</p>
<x-docs.try-it method="POST" path="/receive/qr" :fields="[
    ['name' => 'account_id', 'in' => 'body', 'placeholder' => 'acc-xxxxxxxx (or use gate_id)'],
    ['name' => 'gate_id', 'in' => 'body', 'placeholder' => 'gate-xxxxxxxx (or use account_id)'],
    ['name' => 'amount', 'in' => 'body', 'type' => 'number'],
]" :mutating="false" />

<h2 id="request-amount">Request a specific amount</h2>
<x-docs.endpoint method="POST" path="/receive/request-amount" />
<p>Same as above but <code class="inline">amount</code> is required, and a <code class="inline">phone</code> (<code class="inline">^\+?[0-9]{7,15}$</code>) is accepted for sending the request to a payer. In the current build the phone is validated but no message is sent from this endpoint — the app shares the QR itself.</p>
<x-docs.code lang="json">{
  "status": true,
  "message": "Amount request QR generated.",
  "data": {
    "qr_base64": "iVBORw0KGgo...",
    "amount": "₦2,500.00",
    "account": "Guaranty Trust Bank (...6789)",
    "gate": null
  }
}</x-docs.code>
<x-docs.try-it method="POST" path="/receive/request-amount" :fields="[
    ['name' => 'account_id', 'in' => 'body'],
    ['name' => 'gate_id', 'in' => 'body'],
    ['name' => 'amount', 'in' => 'body', 'type' => 'number', 'required' => true],
    ['name' => 'phone', 'in' => 'body'],
]" :mutating="false" />

<h2 id="share">Share account details as text</h2>
<x-docs.endpoint method="GET" path="/receive/share/{accountId}" />
<p>Returns a ready-to-send text block for one of the caller's accounts (for WhatsApp/SMS sharing).</p>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "share_text": "Send money to Amaka Okonkwo\nBank: Guaranty Trust Bank\nAccount No: 0123456789",
    "account_no": "0123456789",
    "bank": "Guaranty Trust Bank"
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/receive/share/{accountId}" :mutating="false" />
<p>Errors for all three: 404 <code class="inline">Account or payment point not found.</code>; 422 <code class="inline">This payment point is no longer available.</code> (archived) or <code class="inline">This payment point does not belong to the selected account.</code>; 403 for business-only restrictions.</p>
