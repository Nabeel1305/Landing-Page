<div class="docs-eyebrow">API Reference</div>
<h1>Payment Codes</h1>

<p>A <strong>payment code</strong> is a one-time string of digits that stands for "pay this merchant this amount from this account". It is the only thing the payer has to key in on the phone.</p>

<h2 id="issue">Issue a code</h2>
<x-docs.endpoint method="POST" path="/codes" note="Requires an Idempotency-Key header" />
<p>The platform first asks your system to <strong>hold</strong> the funds on the <strong>subscriber's registered bank account</strong> (its <code class="inline">account_number</code> and <code class="inline">bank_code</code>). Only if the hold succeeds is a code created. If anything fails after the hold, the hold is released. When the payer later dials, the funds are credited to the <strong>merchant's registered account</strong>. Both accounts are frozen on the code when it is issued, so editing a subscriber or merchant afterwards never changes a payment already in flight.</p>

<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Type</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">subscriber_reference</code></td><td>string</td><td>Required. An existing <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'subscribers']) }}">subscriber</a> (max 191).</td></tr>
    <tr><td><code class="inline">merchant_reference</code></td><td>string</td><td>Required (max 191). A <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'merchants']) }}">merchant</a> you registered — or one created on the spot with <code class="inline">merchant</code> below.</td></tr>
    <tr><td><code class="inline">merchant</code></td><td>object</td><td>Optional. <code class="inline">{ "name", "account_number", "bank_code" }</code> (and an optional <code class="inline">account_reference</code> label) — <strong>all three are required</strong> when the object is present. If <code class="inline">merchant_reference</code> doesn't exist yet it is <strong>created from these details</strong> together with the code. See <a href="#merchant-on-the-fly">below</a>.</td></tr>
    <tr><td><code class="inline">amount_minor</code></td><td>integer</td><td>Required. 1 to 999,999,999,999, in the smallest unit (kobo). <code class="inline">250000</code> = ₦2,500.00. Above your account's configured maximum → <code class="inline">settlement_rejected</code>.</td></tr>
    <tr><td><code class="inline">currency</code></td><td>string</td><td>Required. Exactly 3 letters (<code class="inline">NGN</code>); stored upper-case.</td></tr>
    <tr><td><code class="inline">source_account_reference</code></td><td>string</td><td>Optional (max 191). Your own label for the paying account, passed to your system with the hold. The funds are held on the subscriber's registered account either way.</td></tr>
  </tbody>
</table></div>

<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/codes \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -H "Idempotency-Key: 6f1c0a52-3b8e-4d57-9a41-0c9d7b2e5f13" \
  -d '{
    "subscriber_reference": "cust-42",
    "merchant_reference": "shop-7",
    "amount_minor": 250000,
    "currency": "NGN"
  }'</x-docs.code>
<x-docs.code lang="json">// 201 Created
{
  "id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
  "state": "issued",
  "amount_minor": 250000,
  "currency": "NGN",
  "expires_at": "2026-10-09T15:10:00+00:00",
  "redeemed_at": null,
  "merchant_created": false,
  "code": "482019377104",
  "voice_number": "+2347000000001",
  "dial_string": "+2347000000001,,,482019377104#",
  "dial_uri": "tel:+2347000000001,,,482019377104%23"
}</x-docs.code>
<ul>
  <li><code class="inline">code</code> is the digits the payer dials. It appears <strong>only in this response</strong> (and in an idempotent replay of it). It is stored as a keyed hash and can never be read back — not by you, not by PakaPay staff.</li>
  <li>Code length is 12 digits by default (set per platform). On a <em>shared</em> voice number the code is prefixed with your 4-digit short code; see <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'numbers']) }}">Voice Numbers</a>. Always show the payer the <code class="inline">code</code> exactly as returned.</li>
  <li><strong>Dialling help.</strong> Three more fields spare you assembling the call yourself: <code class="inline">voice_number</code> (the number to ring, in international form), <code class="inline">dial_string</code> (that number, three pauses, the code and a closing <code class="inline">#</code>, ready for a dialler) and <code class="inline">dial_uri</code> (the same as a <code class="inline">tel:</code> link, with the <code class="inline">#</code> percent-encoded, which most phones need). The commas are pauses so the call connects before the digits are sent. They hold the code, so like <code class="inline">code</code> they appear <strong>only in this response</strong>. If no voice number has been set up for your account yet, all three are <code class="inline">null</code> — show the code and ask PakaPay for a number.</li>
  <li><code class="inline">expires_at</code> is now plus your account's code lifetime (default 10 minutes).</li>
</ul>
<x-docs.try-it method="POST" path="/codes" :fields="[
    ['name' => 'Idempotency-Key', 'in' => 'header', 'placeholder' => 'leave empty to generate one'],
    ['name' => 'subscriber_reference', 'in' => 'body', 'required' => true, 'default' => 'cust-42'],
    ['name' => 'merchant_reference', 'in' => 'body', 'required' => true, 'default' => 'shop-7'],
    ['name' => 'amount_minor', 'in' => 'body', 'type' => 'number', 'required' => true, 'default' => '250000'],
    ['name' => 'currency', 'in' => 'body', 'required' => true, 'default' => 'NGN'],
    ['name' => 'source_account_reference', 'in' => 'body', 'placeholder' => 'optional label'],
]" warning="Places a hold through your settlement system. On a sandbox account this is simulated; on a live account it reserves real funds." />

<h3 id="merchant-on-the-fly">Creating the merchant while issuing</h3>
<p>You don't have to register a merchant in a separate call first. Add a <code class="inline">merchant</code> object and an unknown <code class="inline">merchant_reference</code> is registered as part of the same request:</p>
<x-docs.code lang="json">{
  "subscriber_reference": "cust-42",
  "merchant_reference": "shop-9",
  "merchant": { "name": "Fresh Shop", "account_number": "3000000009", "bank_code": "011" },
  "amount_minor": 250000,
  "currency": "NGN"
}</x-docs.code>
<ul>
  <li>The response has <code class="inline">"merchant_created": true</code> when this request registered the merchant, otherwise <code class="inline">false</code>.</li>
  <li>The merchant is created <strong>only if the hold succeeds</strong>. A refused hold, or any later failure, leaves nothing behind.</li>
  <li>Send the same <code class="inline">merchant</code> details on every call if that's easier — once the merchant exists they are simply checked, not applied.</li>
  <li><strong>An existing merchant is never changed this way.</strong> Its name is left alone, and if <code class="inline">merchant.account_number</code> or <code class="inline">merchant.bank_code</code> differs from the account on file the request is refused with <code class="inline">422</code> (<code class="inline">merchant.account_number</code> in <code class="inline">errors</code>). The account that gets credited must not be rewritable by a payment request; change it deliberately with <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'merchants']) }}"><code class="inline">PUT /merchants/{reference}</code></a>.</li>
  <li>Without <code class="inline">merchant</code>, an unknown <code class="inline">merchant_reference</code> is still <code class="inline">404</code>.</li>
  <li>Safe to retry: a retry with the same <code class="inline">Idempotency-Key</code> doesn't create it twice, and two simultaneous requests for a new merchant end up with one row.</li>
</ul>

<h3 id="issue-errors">Errors</h3>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Status</th><th>Why</th></tr></thead>
  <tbody>
    <tr><td>404 <code class="inline">not_found</code></td><td>The subscriber isn't registered, or the merchant isn't and no <code class="inline">merchant</code> details were sent</td></tr>
    <tr><td>422 validation</td><td><code class="inline">merchant</code> sent without <code class="inline">name</code>, <code class="inline">account_number</code> or <code class="inline">bank_code</code> (or in the wrong format), or with an account that differs from the registered merchant's</td></tr>
    <tr><td>422 validation</td><td>The subscriber or merchant has <strong>no bank account on file</strong> (registered before accounts were required) — register it with <code class="inline">PUT</code> first</td></tr>
    <tr><td>422 <code class="inline">settlement_rejected</code></td><td>Your system refused the hold (e.g. insufficient funds — the message is its reason), or the amount is above the account maximum</td></tr>
    <tr><td>422 validation</td><td>A field is missing or malformed</td></tr>
    <tr><td>422 <code class="inline">idempotency_key_required</code> / <code class="inline">idempotency_key_reused</code></td><td>See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'idempotency']) }}">Idempotency</a></td></tr>
    <tr><td>403 <code class="inline">tenant_suspended</code></td><td>Your organisation is suspended</td></tr>
  </tbody>
</table></div>

<h2 id="read">Read a code</h2>
<x-docs.endpoint method="GET" path="/codes/{id}" />
<p>Returns the code's current state — <strong>never its digits</strong>.</p>
<x-docs.code lang="json">{
  "id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
  "state": "redeemed",
  "amount_minor": 250000,
  "currency": "NGN",
  "expires_at": "2026-10-09T15:10:00+00:00",
  "redeemed_at": "2026-10-09T15:03:21+00:00"
}</x-docs.code>
<x-docs.try-it method="GET" path="/codes/{id}" :mutating="false" />

<h2 id="cancel">Cancel a code</h2>
<x-docs.endpoint method="POST" path="/codes/{id}/cancel" note="Requires an Idempotency-Key header" />
<p>Cancels a code nobody has dialled yet and releases its hold. Use it when the customer abandons the payment.</p>
<x-docs.code lang="bash">curl -X POST {{ config('docs.api_base') }}/codes/0d3c6a1e-…/cancel \
  -H "Authorization: Bearer $KEY" -H "Idempotency-Key: $(uuidgen)"</x-docs.code>
<x-docs.code lang="json">// 200 OK — the code, now cancelled
{ "id": "0d3c6a1e-…", "state": "cancelled", "amount_minor": 250000, "currency": "NGN", "expires_at": "…", "redeemed_at": null }</x-docs.code>
<ul>
  <li><code class="inline">409 not_cancellable</code> if the code is already redeemed, settled, failed, expired or cancelled.</li>
  <li>There is no webhook for cancellation — you caused it, so you already know.</li>
</ul>
<x-docs.try-it method="POST" path="/codes/{id}/cancel" :fields="[
    ['name' => 'Idempotency-Key', 'in' => 'header', 'placeholder' => 'leave empty to generate one'],
]" warning="Cancels the code and releases its hold." />

<h2 id="states">States</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>State</th><th>Meaning</th><th>Next</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">issued</code></td><td>Created, hold placed, waiting for the call</td><td>redeemed, cancelled, expired</td></tr>
    <tr><td><code class="inline">redeemed</code></td><td>A valid call arrived; capture is in progress</td><td>settled, failed</td></tr>
    <tr><td><code class="inline">settled</code></td><td>Your system captured the funds</td><td>— (final)</td></tr>
    <tr><td><code class="inline">failed</code></td><td>Capture was declined or the hold was released</td><td>— (final)</td></tr>
    <tr><td><code class="inline">cancelled</code></td><td>You cancelled it</td><td>— (final)</td></tr>
    <tr><td><code class="inline">expired</code></td><td>The deadline passed unused; hold released</td><td>— (final)</td></tr>
  </tbody>
</table></div>
<p>More detail, including what happens when a capture result goes missing, is in <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'lifecycle']) }}">Code Lifecycle &amp; Reconciliation</a>.</p>
