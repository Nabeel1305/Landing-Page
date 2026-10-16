<div class="docs-eyebrow">Offline Phone Codes</div>
<h1>How It Works</h1>

<p>With <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'codes']) }}">issued codes</a> your server creates the code, so the payer must be online (or reach your server another way) to get one. <strong>Offline phone codes</strong> remove that step: the payer's <em>phone</em> builds the code itself, with no connection at all, and the platform verifies it when the call arrives.</p>

<x-docs.code lang="text">  ONLINE, once                    OFFLINE, any time                    ONLINE (server side)
  ───────────                     ─────────────────                    ────────────────────
  your app ─ enrol phone ─▶ key   phone builds a 40-digit code         platform verifies the signature,
  (secret key stored in the       signed with its key                  asks your system to hold + capture,
  phone's secure hardware)        payer dials number + code + #        then webhooks tell you the result</x-docs.code>

<h2 id="the-flow">The flow</h2>
<ol>
  <li><strong>Enrol the phone</strong> (online, once) with <code class="inline">POST /subscribers/{reference}/devices</code>. You get a secret <code class="inline">device_key</code> and an 8-digit <code class="inline">short_id</code>. The app stores the key in secure hardware (Keychain / Android Keystore).</li>
  <li><strong>Pick the payee.</strong> The app learns the merchant's <code class="inline">short_id</code>, account number and bank code — typically from the merchant's QR code, which your system generates from <code class="inline">GET /merchants/{reference}</code>.</li>
  <li><strong>Build the code</strong> on the phone, offline. It carries the device, a counter, the amount and the merchant, plus a signature that covers the account details of both sides. See <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'code-format']) }}">Code Format &amp; Signing</a>.</li>
  <li><strong>Dial</strong> the voice number, key the 40 digits and <code class="inline">#</code>. Nothing but a phone call is needed.</li>
  <li><strong>The platform verifies and settles.</strong> If the signature, counter, limits and caller check pass, it asks your system to <strong>hold and capture</strong> the funds in one go, and you receive the usual <code class="inline">code.redeemed</code> then <code class="inline">transaction.settled</code> / <code class="inline">transaction.failed</code> webhooks. The caller hears only a generic thank-you.</li>
</ol>

<h2 id="vs-issued">Compared with issued codes</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th></th><th>Issued code (<code class="inline">POST /codes</code>)</th><th>Offline phone code</th></tr></thead>
  <tbody>
    <tr><td>Who creates the code</td><td>Your server</td><td>The payer's phone</td></tr>
    <tr><td>Needs a connection to start a payment</td><td>Yes</td><td><strong>No</strong></td></tr>
    <tr><td>Funds reserved when the code exists</td><td>Yes — a hold is placed at issue</td><td><strong>No</strong> — held and captured when the call arrives</td></tr>
    <tr><td>Length</td><td>12 digits (+4 on a shared number)</td><td>40 digits (+4 on a shared number)</td></tr>
    <tr><td>Lifetime</td><td>Your account's code lifetime (default 10 min)</td><td>About 5–15 minutes from when the phone built it</td></tr>
    <tr><td>Payee</td><td>Any registered merchant</td><td>Any registered merchant, identified by its short id</td></tr>
    <tr><td>Extra protection against a failed payment</td><td>The hold</td><td>Per-payment and daily caps — see <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'risk']) }}">Limits &amp; Risk</a></td></tr>
  </tbody>
</table></div>
<p>Both kinds work side by side on the same voice number. The platform tells them apart by length. In the portal and in webhooks, every code and transaction carries <code class="inline">origin</code>: <code class="inline">issued</code> or <code class="inline">device</code>.</p>

<h2 id="requirements">What has to be in place</h2>
<ul>
  <li>The feature is <strong>off by default</strong>. PakaPay switches it on per organisation and sets its limits.</li>
  <li>Subscribers and merchants must have their <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'subscribers']) }}">bank accounts registered</a>.</li>
  <li>Your settlement adapter must support a <strong>hold followed immediately by a capture</strong>, keyed so a retry is safe — see <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'settlement']) }}">Settlement Adapter</a>.</li>
  <li>Your mobile app needs a small library to build the code. Reference implementations for JavaScript and PHP are in the <a href="{{ route('docs.show', ['section' => 'clients', 'page' => 'clients']) }}">client libraries</a>; the format is short enough to port to Kotlin, Swift or Dart, with published test vectors.</li>
</ul>

<h2 id="what-the-payer-sees">What the payer experiences</h2>
<p>Nothing different from any other call payment: the app shows "Pay Corner Shop ₦2,500", the payer confirms with their PIN or biometrics, and the app dials the number and keys the code for them (<code class="inline">tel:</code> link with the digits). Your server learns the outcome from webhooks and tells the customer by SMS or push.</p>
