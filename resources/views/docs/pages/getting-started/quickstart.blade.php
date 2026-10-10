<div class="docs-eyebrow">Getting Started</div>
<h1>Quickstart</h1>

<p>From nothing to a settled sandbox payment in six steps. Use a <strong>sandbox</strong> account: the platform simulates your core system, so nothing real moves.</p>

<h2 id="before-you-start">Before you start</h2>
<ul>
  <li>An <strong>API key</strong> (<code class="inline">opk_…</code>) for a <strong>sandbox</strong> account. PakaPay gives you the first one; you can create more in the <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'developers']) }}">developer portal</a>.</li>
  <li>A public <strong>HTTPS</strong> address that can receive webhooks.</li>
</ul>

<h2 id="step-1">1. Register a payer and a merchant</h2>
<p>Each needs a bank account: the payer's is where funds are <strong>held</strong>, the merchant's is where they are <strong>credited</strong>.</p>
<p>These calls are upserts — repeat them safely whenever your data changes. (Registering the merchant is optional: you can also create it on the spot when you issue the code in step 3 by adding a <code class="inline">merchant</code> object — see <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'codes']) }}#merchant-on-the-fly">Payment Codes</a>.)</p>
<x-docs.code lang="bash">export API={{ config('docs.api_base') }}
export KEY=opk_1a2b3c4d_…

curl -X PUT $API/subscribers/cust-42 \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "account_number": "2000000001", "bank_code": "058", "phone": "+2348012345678" }'

curl -X PUT $API/merchants/shop-7 \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "name": "Corner Shop", "account_number": "3000000001", "bank_code": "011" }'</x-docs.code>

<h2 id="step-2">2. Register a webhook endpoint</h2>
<x-docs.code lang="bash">curl $API/webhook-endpoints \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "url": "https://you.example/hooks/offline" }'</x-docs.code>
<x-docs.code lang="json">{ "id": 1, "url": "https://you.example/hooks/offline", "events": null, "secret": "whsec_9f3c…" }</x-docs.code>
<x-docs.callout type="warn" title="Save the secret now">
  <p>The signing secret is returned <strong>once</strong>. Store it; you need it to <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'verifying']) }}">verify every delivery</a>. If you lose it, rotate it in the portal.</p>
</x-docs.callout>

<h2 id="step-3">3. Issue a code</h2>
<x-docs.code lang="bash">curl $API/codes \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -H "Idempotency-Key: $(uuidgen)" \
  -d '{
    "subscriber_reference": "cust-42",
    "merchant_reference": "shop-7",
    "amount_minor": 250000,
    "currency": "NGN"
  }'</x-docs.code>
<x-docs.code lang="json">{
  "id": "0d3c6a1e-6f4a-4b50-9d3e-3a1f1c2b9e10",
  "state": "issued",
  "amount_minor": 250000,
  "currency": "NGN",
  "expires_at": "2026-10-09T15:10:00+00:00",
  "code": "482019377104",
  "voice_number": "+2347000000001",
  "dial_string": "+2347000000001,,,482019377104#",
  "dial_uri": "tel:+2347000000001,,,482019377104%23"
}</x-docs.code>
<p><code class="inline">amount_minor</code> is in the smallest unit (kobo), so <code class="inline">250000</code> is ₦2,500.00. The <code class="inline">code</code> and the dialling fields (<code class="inline">voice_number</code>, <code class="inline">dial_string</code>, <code class="inline">dial_uri</code>) are shown only in this response — they cannot be fetched again.</p>

<h2 id="step-4">4. The payer dials</h2>
<p>In production the payer phones the voice number and keys <code class="inline">482019377104#</code>. For your sandbox, ask your PakaPay contact for a <strong>test voice number</strong> and call it from the phone registered to your subscriber (or have them post to the number's callback URL, which only PakaPay knows). See <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'voice']) }}">The Payer's Call</a>.</p>

<h2 id="step-5">5. Receive the webhooks</h2>
<p>Your endpoint gets <code class="inline">code.redeemed</code> immediately, then <code class="inline">transaction.settled</code> (or <code class="inline">transaction.failed</code>) once your system answers the capture. Verify the signature, answer <code class="inline">2xx</code>, and tell your customer.</p>

<h2 id="step-6">6. Read the result</h2>
<x-docs.code lang="bash">curl $API/transactions/<transaction-id> -H "Authorization: Bearer $KEY"</x-docs.code>
<x-docs.code lang="json">{
  "id": "6b1f…", "code_id": "0d3c6a1e-…", "reference": "TXN-9K2LQ7ZP4XMA",
  "status": "settled", "amount_minor": 250000, "currency": "NGN",
  "settlement_reference": "settle_2f6a…", "failure_reason": null
}</x-docs.code>

<h2 id="try-the-first-call">Try the first call</h2>
<p>Set your API key (top right) and try registering a payer. Panels act on your real <em>sandbox</em> account.</p>
<x-docs.endpoint method="PUT" path="/subscribers/{reference}" />
<x-docs.try-it method="PUT" path="/subscribers/{reference}" :fields="[
    ['name' => 'account_number', 'in' => 'body', 'required' => true, 'default' => '2000000001'],
    ['name' => 'bank_code', 'in' => 'body', 'required' => true, 'default' => '058'],
    ['name' => 'phone', 'in' => 'body', 'placeholder' => '+2348012345678'],
]" warning="Creates or updates a subscriber on the account your key belongs to." />

<h2 id="next">Next</h2>
<ul>
  <li><a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'verifying']) }}">Verify webhook signatures</a> — do this before going further.</li>
  <li><a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'going-live']) }}">What changes when you go live</a>.</li>
</ul>
