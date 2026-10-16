<div class="docs-eyebrow">Offline Phone Codes</div>
<h1>Enrolling Phones</h1>

<p>A phone can only build valid codes after it has been <strong>enrolled</strong> for a subscriber. Enrolment gives it a secret key. The platform does not store that key: it re-derives it from a platform secret whenever it has to verify a code, so a database leak exposes no keys.</p>

<x-docs.callout type="note" title="Prerequisites">
  <p>Offline phone codes must be switched on for your organisation (otherwise every call below answers <code class="inline">403 device_codes_disabled</code>), and the subscriber must have a <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'subscribers']) }}">bank account on file</a>.</p>
</x-docs.callout>

<h2 id="enrol">Enrol a phone</h2>
<x-docs.endpoint method="POST" path="/subscribers/{reference}/devices" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">device_id</code></td><td>Required, 8–191 characters. <strong>Your</strong> identifier for this installation of your app (for example a UUID the app generates). Unique per subscriber.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/subscribers/cust-42/devices \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "device_id": "5f0c1e3a-9d1b-4f7e-8c0a-2b6a1d4e9f30" }'</x-docs.code>
<x-docs.code lang="json">// 201 Created (200 OK when the device was already enrolled)
{
  "device_id": "5f0c1e3a-9d1b-4f7e-8c0a-2b6a1d4e9f30",
  "short_id": "48201937",
  "subscriber_reference": "cust-42",
  "status": "active",
  "key_version": 1,
  "last_counter": 0,
  "last_used_at": null,
  "device_key": "MDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWY=",
  "currency": "NGN",
  "code_length": 40,
  "window_seconds": 300
}</x-docs.code>
<ul>
  <li><code class="inline">device_key</code> is the phone's <strong>secret</strong> — base64 of 32 random-looking bytes. Hand it to the app over your own authenticated channel and store it in the phone's secure hardware. Never log it, never put it in a backup.</li>
  <li><code class="inline">short_id</code> is what the phone puts in every code. It is not secret.</li>
  <li><code class="inline">last_counter</code> is the highest counter the platform has accepted. After a reinstall the app must resume from <code class="inline">last_counter + 1</code>.</li>
  <li><strong>Repeating the call is safe</strong> and returns the same key (it is derived, not stored). That makes recovery after a reinstall simple.</li>
  <li>A subscriber can have at most 5 active devices (a platform setting).</li>
</ul>
<x-docs.try-it method="POST" path="/subscribers/{reference}/devices" :fields="[
    ['name' => 'device_id', 'in' => 'body', 'required' => true, 'placeholder' => 'at least 8 characters'],
]" warning="Enrols a device and returns its secret key. Needs the feature enabled for your account." />

<h2 id="list">List a subscriber's devices</h2>
<x-docs.endpoint method="GET" path="/subscribers/{reference}/devices" />
<x-docs.code lang="json">{ "data": [ { "device_id": "5f0c…", "short_id": "48201937", "subscriber_reference": "cust-42", "status": "active", "key_version": 1, "last_counter": 42, "last_used_at": "2026-10-16T09:12:44+00:00" } ] }</x-docs.code>
<p>Keys are never included. Use <code class="inline">last_used_at</code> to spot devices nobody uses any more.</p>
<x-docs.try-it method="GET" path="/subscribers/{reference}/devices" :mutating="false" />

<h2 id="revoke">Revoke a phone</h2>
<x-docs.endpoint method="DELETE" path="/subscribers/{reference}/devices/{deviceId}" />
<p>Use it when a phone is lost, sold or compromised. It takes effect immediately: the next code from that phone is ignored. Repeating the call is harmless.</p>
<x-docs.code lang="json">{ "device_id": "5f0c…", "short_id": "48201937", "subscriber_reference": "cust-42", "status": "revoked", "key_version": 1, "last_counter": 42, "last_used_at": "…" }</x-docs.code>
<x-docs.try-it method="DELETE" path="/subscribers/{reference}/devices/{deviceId}" :high-stakes="true" warning="Stops this phone from making offline payments." />
<p><strong>Re-enrolling a revoked device</strong> (same <code class="inline">device_id</code>) issues a <strong>new key</strong> (<code class="inline">key_version</code> goes up) and resets the counter to 0. Codes built with the old key stay worthless.</p>

<h2 id="merchant-short-id">The merchant side</h2>
<x-docs.endpoint method="GET" path="/merchants/{reference}" />
<p>A phone needs the merchant's <code class="inline">short_id</code>, <code class="inline">account_number</code> and <code class="inline">bank_code</code> to build a code. Every merchant gets an 8-digit <code class="inline">short_id</code> when it is created (also returned by <code class="inline">PUT /merchants/{reference}</code>); it never changes. Put these three values in the merchant's QR code, so the payer's phone learns them by scanning.</p>
<x-docs.code lang="json">{ "reference": "shop-7", "short_id": "70011234", "name": "Corner Shop", "account_number": "3000000001", "bank_code": "011", "account_reference": null }</x-docs.code>
<x-docs.try-it method="GET" path="/merchants/{reference}" :mutating="false" />
<x-docs.callout type="warn" title="If a merchant's account changes">
  <p>The signature covers the merchant's account number and bank code. If you change them with <code class="inline">PUT /merchants</code>, codes phones built against the <em>old</em> account fail verification — which is deliberate: what the payer saw on screen is what they authorised. Re-publish the merchant's QR code after changing its account.</p>
</x-docs.callout>
