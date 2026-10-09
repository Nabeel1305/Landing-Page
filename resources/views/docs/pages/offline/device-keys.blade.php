<div class="docs-eyebrow">Offline Payments</div>
<h1>Device Keys</h1>

<p>Each phone gets its own symmetric <strong>device key</strong>, used to sign offline payment codes. The key is derived inside AWS KMS (<code class="inline">GenerateMac</code>, HMAC-SHA-256, over the string <code class="inline">"{user_id}:{device_id}"</code>) from a master secret that never leaves KMS. The server doesn't store device keys — it re-derives them on demand to verify a code. The phone must keep its copy in secure storage (Keychain / Android Keystore).</p>

<h2 id="provision">Provision a key</h2>
<x-docs.endpoint method="POST" path="/device/offline-key" note="Auth + fraud-lock middleware · requires PIN re-proof" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">device_id</code></td><td>Required, 8–191 chars. The same stable id sent as <code class="inline">X-Device-Id</code>. It becomes the account's bound device.</td></tr>
    <tr><td><code class="inline">pin</code></td><td>Required. Re-proves the 4-digit PIN even though a token is present.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/device/offline-key \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "device_id": "7f3c9a52-device-uuid", "pin": "4821" }'</x-docs.code>
<x-docs.code lang="json">{
  "device_key": "m9z0Qb7lq1...base64-of-32-bytes...=",
  "version": 1,
  "voice_number": "+2347000000000"
}</x-docs.code>
<p>This response has <strong>no</strong> <code class="inline">status</code>/<code class="inline">data</code> envelope. Behaviour:</p>
<ul>
  <li><strong>First call</strong> binds the device (<code class="inline">bound_device_id</code>), stamps <code class="inline">device_key_issued_at</code> and writes a <code class="inline">device_key.issued</code> audit entry.</li>
  <li><strong>Repeat call, same device</strong> returns the same key again (it is deterministic) — safe after a reinstall or lost local storage.</li>
  <li><code class="inline">422 Incorrect PIN.</code> counts towards the PIN lock; <code class="inline">423 Account temporarily locked. Try again later.</code> when locked.</li>
  <li><code class="inline">409 A different device is already bound to this account. Verify the new device first.</code> — complete <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}#device-binding">new-device verification</a> (which re-binds the account) and call again.</li>
</ul>
<x-docs.callout type="danger" title="Treat the device key like a password">
  <p>Anyone holding it (plus the device id) can mint valid codes for the account, up to the offline limits. Never log it, never put it in backups, and never send it anywhere but secure storage. If a phone is lost, an operator can suspend the device.</p>
</x-docs.callout>
<x-docs.try-it method="POST" path="/device/offline-key" :high-stakes="true" :fields="[
    ['name' => 'device_id', 'in' => 'body', 'required' => true, 'placeholder' => 'min 8 chars'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
]" warning="Binds your account to the device id you enter and returns a live signing key." />

<h2 id="voice-number">Get the voice number</h2>
<x-docs.endpoint method="GET" path="/device/offline-voice-number" />
<p>Not secret and not PIN-gated: lets an already-provisioned device backfill <code class="inline">voice_number</code> if it was unset server-side when the key was issued.</p>
<x-docs.code lang="json">{ "voice_number": "+2347000000000" }</x-docs.code>
<x-docs.try-it method="GET" path="/device/offline-voice-number" :mutating="false" />
