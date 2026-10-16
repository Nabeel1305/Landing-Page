<div class="docs-eyebrow">Offline Phone Codes</div>
<h1>Limits &amp; Risk</h1>

<p>An issued code is backed by a <strong>hold</strong>: the money is reserved before the payer even dials. An offline phone code can't be — nothing is connected when the phone builds it. The platform therefore holds <em>and</em> captures only when the call arrives, and the customer may by then have spent the money elsewhere. Plan for that.</p>

<h2 id="caps">Caps</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Cap</th><th>Default</th><th>Effect</th></tr></thead>
  <tbody>
    <tr><td>Per payment</td><td>₦20,000</td><td>A genuine code above it is refused.</td></tr>
    <tr><td>Per subscriber per day</td><td>₦50,000</td><td>Counts all of that day's offline phone payments that did not fail, across all their phones.</td></tr>
    <tr><td>Active phones per subscriber</td><td>5</td><td>Enrolment is refused beyond it.</td></tr>
  </tbody>
</table></div>
<p>PakaPay sets these per organisation when it switches the feature on. Your own system should add its own checks inside <code class="inline">hold</code>: a declined hold simply fails the transaction.</p>

<h2 id="failed-payments">When a payment can't be completed</h2>
<p>The caller always hears the same thank-you, so <strong>you</strong> must tell the customer. Two signals:</p>
<ul>
  <li><code class="inline">transaction.failed</code> — the code was valid but your system declined the hold or capture (for example insufficient funds). <code class="inline">failure_reason</code> carries your system's reason.</li>
  <li><code class="inline">code.rejected</code> — the code was <strong>genuine</strong> (its signature checked out) but the platform refused it before asking your system. <code class="inline">data.reason</code> is one of:
    <div class="docs-table-wrap"><table class="docs-table">
      <thead><tr><th><code class="inline">reason</code></th><th>Meaning</th></tr></thead>
      <tbody>
        <tr><td><code class="inline">amount_limit</code></td><td>Above the per-payment cap</td></tr>
        <tr><td><code class="inline">daily_limit</code></td><td>Would take the subscriber over the daily cap</td></tr>
        <tr><td><code class="inline">counter_reused</code></td><td>The counter was already used (a repeat of a code, or a stale one)</td></tr>
        <tr><td><code class="inline">caller_mismatch</code></td><td>Caller binding is on and the call did not come from the subscriber's registered phone</td></tr>
        <tr><td><code class="inline">device_revoked</code></td><td>The phone was revoked while the call was in progress</td></tr>
        <tr><td><code class="inline">amount_invalid</code></td><td>Amount of zero</td></tr>
      </tbody>
    </table></div>
    The payload has <code class="inline">origin</code>, <code class="inline">reason</code>, <code class="inline">device_id</code>, <code class="inline">subscriber_reference</code>, <code class="inline">merchant_reference</code>, <code class="inline">amount_minor</code> and <code class="inline">currency</code>. A refusal does <strong>not</strong> use up the counter (except <code class="inline">counter_reused</code>, which means it was already spent).</li>
</ul>
<p>A code with a <em>wrong</em> signature, an unknown device or an unknown merchant produces <strong>no event at all</strong>: it proves nothing about who sent it, and an event would let anyone who can dial spam your webhook.</p>

<h2 id="threats">What an attacker can and can't do</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Attempt</th><th>Outcome</th></tr></thead>
  <tbody>
    <tr><td>Read a code from a call log and replay it</td><td>Refused: the counter is already used. Before it has been used, it is good only for the same payee and amount, within the window — and with caller binding on, only from the subscriber's phone.</td></tr>
    <tr><td>Change the amount, merchant or counter in a captured code</td><td>The signature no longer matches.</td></tr>
    <tr><td>Guess a signature</td><td>1 in 10<sup>10</sup> per try; five failures lock the calling number out, and unregistered callers share a small per-minute budget.</td></tr>
    <tr><td>Point a code at a different account</td><td>The signature covers both accounts as the platform holds them.</td></tr>
    <tr><td>Steal a phone</td><td>Can spend up to the caps until you <strong>revoke</strong> the device. Protect the key with the phone's secure hardware and require PIN or biometrics before building a code.</td></tr>
    <tr><td>Extract a key from a rooted phone</td><td>Same exposure as a stolen phone, for that one device. Revoke, re-enrol, and keep the caps low.</td></tr>
    <tr><td>Spoof the caller number</td><td>Defeated only if caller binding is on <em>and</em> the attacker also holds a valid code. Turn binding on.</td></tr>
  </tbody>
</table></div>

<h2 id="recommendations">Recommendations</h2>
<ul>
  <li>Keep caps small at first and raise them as you see real behaviour.</li>
  <li>Turn on <strong>caller binding</strong>, with each subscriber's registered phone number, so a code only works from the right handset number.</li>
  <li>Require a PIN or biometric in your app before it builds a code, and rate-limit builds on the phone.</li>
  <li>Have a "lost phone" action wired to <code class="inline">DELETE …/devices/{deviceId}</code>.</li>
  <li>Notify the customer (SMS or push) on <em>every</em> <code class="inline">transaction.settled</code> and <code class="inline">code.rejected</code> for offline payments, so fraud is noticed quickly.</li>
  <li>Make your settlement system's <code class="inline">hold</code> and <code class="inline">capture</code> <strong>idempotent</strong> (keyed by the code id and the transaction reference); the platform retries them after a lost answer.</li>
</ul>
