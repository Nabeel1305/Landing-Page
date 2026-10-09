<div class="docs-eyebrow">Payments</div>
<h1>Sending Money</h1>

<p>Sending is a short pipeline: <strong>decode</strong> the receiver's QR (optional), <strong>initiate</strong> a payment (which stages a pending transaction for 10 minutes), then <strong>confirm</strong> it with the PIN. Users who enable <em>Quick Pay</em> skip the confirm step — <code class="inline">initiate</code> then completes the payment immediately.</p>
<x-docs.code lang="text">decode-qr ─▶ initiate ─┬─▶ (quick_pay = false) ─▶ confirm (PIN) ─▶ completed
                      └─▶ (quick_pay = true)  ─────────────────────▶ completed</x-docs.code>

<x-docs.callout type="warn" title="Both parties must be on PakaPay">
  <p>The recipient account is looked up among accounts linked to PakaPay users. A payment to an account nobody has linked is rejected with <code class="inline">Invalid QR code payload.</code> Every payment creates two transactions: a <code class="inline">sent</code> entry for the payer and a <code class="inline">received</code> entry (reference suffixed <code class="inline">-RCV</code>) for the payee.</p>
</x-docs.callout>

<h2 id="decode-qr">Decode a QR code</h2>
<x-docs.endpoint method="POST" path="/pay/decode-qr" note="Throttle: payment · 20 QR decodes / 10 min per user" />
<p>Validates and resolves a scanned QR so the app can show "Pay Amaka Okonkwo — GTBank (…6789)" before the user commits. <code class="inline">payload</code> is the raw JSON string from the QR. The QR must carry a valid HMAC <code class="inline">signature</code>; tampered or foreign QR codes are rejected.</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/pay/decode-qr \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "payload": "{\"bank_code\":\"058\",\"account_no\":\"0123456789\",\"name\":\"AMAKA OKONKWO\",\"bank\":\"Guaranty Trust Bank\",\"qr_id\":\"48201937\",\"signature\":\"...\"}" }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "bank": "Guaranty Trust Bank",
    "account_no": "0123456789",
    "last4": "6789",
    "name": "AMAKA OKONKWO",
    "source_account_id": "acc-p9d2x1aa",
    "recipient_account_id": "acc-k3j2h4g5",
    "recipient_gate_id": null
  }
}</x-docs.code>
<x-docs.try-it method="POST" path="/pay/decode-qr" :fields="[
    ['name' => 'payload', 'in' => 'body', 'required' => true, 'placeholder' => 'raw QR JSON string'],
]" :mutating="false" />
<p><code class="inline">source_account_id</code> is the caller's active account (or newest linked account). <code class="inline">recipient_gate_id</code> is set when the QR is for a <a href="{{ route('docs.show', ['section' => 'payment-points', 'page' => 'overview']) }}">Payment Point</a>. Errors: <code class="inline">qr_missing_field</code>, <code class="inline">qr_invalid_signature</code> (WARN → 422), <code class="inline">qr_decode_rate_limit</code> (SOFT_BLOCK → 429), <code class="inline">This payment point is no longer available.</code> (archived point).</p>

<h2 id="initiate">Initiate a payment</h2>
<x-docs.endpoint method="POST" path="/pay/initiate" note="Throttle: payment" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Type</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">amount</code></td><td>number</td><td>Required. ₦1 – ₦9,999,999.99</td></tr>
    <tr><td><code class="inline">from_account_id</code></td><td>string</td><td>Required. One of the caller's linked accounts (e.g. <code class="inline">acc-p9d2x1aa</code>)</td></tr>
    <tr><td><code class="inline">recipient_type</code></td><td>string</td><td>Required: <code class="inline">qr_scan</code> or <code class="inline">manual</code></td></tr>
    <tr><td><code class="inline">qr_payload</code></td><td>string</td><td>Required when <code class="inline">qr_scan</code>: the raw QR JSON</td></tr>
    <tr><td><code class="inline">recipient_bank</code></td><td>string</td><td>Required when <code class="inline">manual</code>, max 100</td></tr>
    <tr><td><code class="inline">recipient_account_no</code></td><td>string</td><td>Required when <code class="inline">manual</code>, exactly 10 digits</td></tr>
    <tr><td><code class="inline">recipient_name</code></td><td>string</td><td>Optional, max 100 (informational; the stored name always comes from the receiver's account)</td></tr>
    <tr><td><code class="inline">narration</code></td><td>string</td><td>Optional, max 255</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/pay/initiate \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{
    "amount": 2500,
    "from_account_id": "acc-p9d2x1aa",
    "recipient_type": "qr_scan",
    "qr_payload": "{...raw QR json...}",
    "narration": "Lunch"
  }'</x-docs.code>
<p><strong>Staged (Quick Pay off)</strong> — the transaction is <code class="inline">pending</code> and expires in 10 minutes:</p>
<x-docs.code lang="json">{
  "status": true,
  "quick_pay": false,
  "message": "Payment staged. Please confirm with your PIN.",
  "data": {
    "transaction_ref": "TXN-9K2LQ7ZP4XMA",
    "amount": "₦2,500.00",
    "recipient": "AMAKA OKONKWO • Guaranty Trust Bank",
    "recipient_account_no": "0123456789",
    "expires_in": "10 minutes"
  }
}</x-docs.code>
<p><strong>Quick Pay on</strong> — the payment completes in this call; <code class="inline">data</code> has the same shape as the <a href="#confirm">confirm</a> response:</p>
<x-docs.code lang="json">{ "status": true, "quick_pay": true, "message": "Payment sent!", "data": { "reference": "TXN-9K2LQ7ZP4XMA", "...": "..." } }</x-docs.code>
<p><strong>PIN re-entry required</strong> — three payments inside 90 seconds trip the rapid-fire check. The call returns <code class="inline">202</code> and nothing is staged; ask for the PIN (<code class="inline">/auth/verify-pin</code>) and retry:</p>
<x-docs.code lang="json">HTTP/1.1 202
{ "require_pin": true }</x-docs.code>
<x-docs.try-it method="POST" path="/pay/initiate" :highStakes="true" :fields="[
    ['name' => 'amount', 'in' => 'body', 'type' => 'number', 'required' => true, 'placeholder' => '100'],
    ['name' => 'from_account_id', 'in' => 'body', 'required' => true, 'placeholder' => 'acc-xxxxxxxx'],
    ['name' => 'recipient_type', 'in' => 'body', 'type' => 'select', 'options' => ['qr_scan', 'manual'], 'required' => true, 'default' => 'qr_scan'],
    ['name' => 'qr_payload', 'in' => 'body'],
    ['name' => 'recipient_bank', 'in' => 'body'],
    ['name' => 'recipient_account_no', 'in' => 'body'],
    ['name' => 'narration', 'in' => 'body'],
]" warning="With Quick Pay enabled this moves real money immediately." />

<h3 id="idempotency">Idempotency</h3>
<p>Send an <code class="inline">Idempotency-Key</code> header (or an <code class="inline">idempotency_key</code> body field) on <code class="inline">initiate</code> (Quick Pay) and <code class="inline">confirm</code>. A retry with a key that already produced a <em>completed</em> payment returns that payment instead of creating a second one — essential on flaky mobile networks. Use a fresh UUID per user action and reuse it only for retries of that action.</p>

<h3 id="limits-checked">What is checked, in order</h3>
<ol>
  <li>PIN lock (<code class="inline">PIN_LOCKED</code>, 403).</li>
  <li>Velocity: single transfer ≤ ₦50,000; new accounts (&lt; 7 days) ≤ ₦10,000 per transfer and ₦20,000 per day; ≤ 10 sends/hour; ≤ ₦200,000/day.</li>
  <li>Amount anomaly (flags only — never blocks).</li>
  <li>The user's own limits: <code class="inline">spending_per_transaction</code> (default ₦50,000) and <code class="inline">spending_daily_limit</code> (default ₦200,000) — see <a href="{{ route('docs.show', ['section' => 'security', 'page' => 'pin-management']) }}">Spending Limits</a>. Violations return 422 with a message like <code class="inline">Amount exceeds your per-transaction limit of ₦50,000.00</code>.</li>
</ol>

<h2 id="confirm">Confirm a payment</h2>
<x-docs.endpoint method="POST" path="/pay/confirm" note="Throttle: payment" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">transaction_ref</code></td><td>Required. A <em>pending</em> transaction belonging to the caller. Unknown/expired/not-yours → <code class="inline">Invalid or expired transaction reference.</code></td></tr>
    <tr><td><code class="inline">pin</code></td><td>Required, 4 digits</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/pay/confirm \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -H "Idempotency-Key: 5b0f3c1e-7d1a-4a52-9d84-2f0e6c1b9a10" \
  -d '{ "transaction_ref": "TXN-9K2LQ7ZP4XMA", "pin": "4821" }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "message": "Payment sent successfully!",
  "data": {
    "reference": "TXN-9K2LQ7ZP4XMA",
    "type": "sent",
    "amount": "₦2,500.00",
    "amount_raw": "2500.00",
    "recipient": "AMAKA OKONKWO • Guaranty Trust Bank",
    "recipient_account": "0123456789",
    "from": "From Guaranty Trust Bank (...6789)",
    "status": "completed",
    "completed_at": "Oct 08, 2026 • 3:14 PM"
  }
}</x-docs.code>
<x-docs.try-it method="POST" path="/pay/confirm" :highStakes="true" :fields="[
    ['name' => 'transaction_ref', 'in' => 'body', 'required' => true, 'placeholder' => 'TXN-...'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
]" warning="Completes a real payment from your account." />
<p>On success, in one database transaction: the pending row is marked <code class="inline">completed</code>, signed (KMS), written to the audit log, mirrored as the receiver's <code class="inline">-RCV</code> transaction, and SMS, email (receiver, if verified) and push notifications are sent. A wrong PIN returns 422 <code class="inline">Incorrect PIN.</code> and counts toward the 5-failure PIN lock.</p>

<h2 id="cancel">Cancel a pending payment</h2>
<x-docs.endpoint method="DELETE" path="/pay/{ref}/cancel" />
<p>Cancels a <code class="inline">pending</code> payment you staged. 404 if it isn't pending or isn't yours.</p>
<x-docs.code lang="json">{ "status": true, "message": "Payment cancelled." }</x-docs.code>
<x-docs.try-it method="DELETE" path="/pay/{ref}/cancel" warning="Cancels a pending payment." />

<h2 id="transaction-states">Transaction states</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Status</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">pending</code></td><td>Staged, awaiting PIN. Expires 10 minutes after creation.</td></tr>
    <tr><td><code class="inline">completed</code></td><td>Settled and signed.</td></tr>
    <tr><td><code class="inline">cancelled</code></td><td>Cancelled by the payer (or an operator).</td></tr>
  </tbody>
</table></div>
<p>Operators can also reverse completed transactions from the <a href="{{ route('docs.show', ['section' => 'platform', 'page' => 'admin-dashboard']) }}">admin dashboard</a>.</p>
