<div class="docs-eyebrow">Offline Payments</div>
<h1>How Offline Payments Work</h1>

<p>The offline rail lets a customer pay with <strong>no mobile data</strong> — only a phone call. The app, which already holds a secret key for that device, builds a 31-digit signed code and dials PakaPay's voice number with the code as DTMF tones. PakaPay verifies the code, moves the money, and texts both parties. The phone never needs to talk to the API at payment time.</p>

<x-docs.code lang="text">  Payer's phone (no data)                      Africa's Talking                    PakaPay
  ───────────────────────                      ────────────────                    ───────
  1. builds 31-digit code  ───── dials tel:+234700…,,,CODE# ──▶  call arrives
                                                    │  POST /webhook/africastalking/voice
                                                    └───────────────────────────────▶ 2. verify + settle
                                                    ◀── XML: "Thank you… SMS shortly" ─┘
  3. SMS "DEBIT ALERT…"  ◀───────────────────────────────── Kudi SMS ◀──────────────── 4. notify both users</x-docs.code>

<h2 id="prerequisites">Prerequisites</h2>
<ol>
  <li>The user is signed in on a bound device and has called <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'device-keys']) }}"><code class="inline">POST /device/offline-key</code></a> once, while online, to receive their device key and the voice number.</li>
  <li><code class="inline">offline_payments_enabled</code> is on (<a href="{{ route('docs.show', ['section' => 'security', 'page' => 'settings']) }}">Security Settings</a>).</li>
  <li>The call is made <strong>from the phone number registered on the account</strong> (the caller ID is the first identity check).</li>
  <li>The receiver has a linked bank account (its 8-digit <code class="inline">qr_id</code> is in their QR — see <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'receiving']) }}#qr-payload">QR payload</a>).</li>
</ol>

<h2 id="dialing">The dial string</h2>
<x-docs.code lang="text">tel:{voice_number},,,{31-digit code}#</x-docs.code>
<p>The commas insert pauses so the code is sent after the line connects; the trailing <code class="inline">#</code> is the "finish" key the voice flow listens for. <code class="inline">voice_number</code> is an E.164 number such as <code class="inline">+2347000000000</code>, returned by the device-key endpoints.</p>

<h2 id="pipeline">Server verification pipeline</h2>
<p>When the call's digits arrive, <code class="inline">OfflinePaymentService</code> runs these checks in a deliberate order (cheap identity checks first, money last). Any failure stops processing; the caller is told nothing specific on the call — only the SMS channel reports outcomes.</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>#</th><th>Check</th><th>On failure</th></tr></thead>
  <tbody>
    <tr><td>1</td><td>Parse: exactly 31 digits</td><td><code class="inline">offline_malformed_code</code> (WARN)</td></tr>
    <tr><td>2</td><td>Caller number matches a registered user (several number formats tried)</td><td><code class="inline">offline_unknown_caller</code></td></tr>
    <tr><td>3</td><td>The user has a bound device</td><td><code class="inline">offline_no_bound_device</code></td></tr>
    <tr><td>4</td><td>Offline payments enabled for the user</td><td><code class="inline">offline_disabled</code> (INFO)</td></tr>
    <tr><td>5</td><td>User row locked (<code class="inline">FOR UPDATE</code>) so concurrent calls serialize</td><td>—</td></tr>
    <tr><td>6</td><td>This <code class="inline">counter</code> already seen? Completed → resend alerts, idempotently; otherwise "already submitted"</td><td>no double spend</td></tr>
    <tr><td>7</td><td>Account not locked</td><td><code class="inline">offline_locked_account</code> (HARD_BLOCK)</td></tr>
    <tr><td>8</td><td>Signature valid (±1 five-minute window)</td><td><code class="inline">offline_bad_signature</code>; 5 in 15 min <strong>lock the account</strong></td></tr>
    <tr><td>9</td><td>Counter strictly greater than the last accepted one</td><td><code class="inline">offline_replay</code> (HARD_BLOCK)</td></tr>
    <tr><td>10</td><td>Claim the counter (unique constraint), resolve sender account (by index) and receiver (by <code class="inline">qr_id</code>), reject self-payment and zero amount</td><td><code class="inline">offline_payment_failed</code></td></tr>
    <tr><td>11</td><td>Offline velocity: ≤ ₦20,000 per payment, ≤ ₦50,000 per day</td><td><code class="inline">offline_velocity_*</code></td></tr>
    <tr><td>12</td><td>Create sent + received transactions (source <code class="inline">offline_dtmf</code>), KMS-sign, audit-log, notify</td><td>—</td></tr>
  </tbody>
</table></div>
<p>The caps are intentionally tighter than online (the phone can't check anything in real time) and are configurable: <code class="inline">OFFLINE_MAX_SINGLE_TXN</code>, <code class="inline">OFFLINE_MAX_DAILY_VOLUME</code>.</p>

<h2 id="outcome">What each party receives</h2>
<p>Both users get an SMS (and, where available, a push notification and an email if their email is verified):</p>
<x-docs.code lang="text">DEBIT ALERT: You sent NGN 2,500.00 to AMAKA OKONKWO (**6789) on 08/10/2026 at 3:14 PM. Ref: TXN-9K2LQ7ZP4XMA. PakaPay
CREDIT ALERT: You received NGN 2,500.00 from CHIDI EZE (**4321) on 08/10/2026 at 3:14 PM. Ref: TXN-8H1KP3MQ7YNB-RCV. PakaPay</x-docs.code>
<p>On the call itself the caller always hears the same line — <em>"Thank you for using PakaPay. You will receive an SMS shortly."</em> — whether the payment succeeded or not. That is deliberate: it denies an attacker an oracle for guessing codes.</p>

<x-docs.callout type="warn" title="Cannot be reversed by the payer">
  <p>An accepted code is final. Only operators can reverse a completed transaction. The code's 5-minute signature window and strictly increasing counter make a captured code useless to anyone else.</p>
</x-docs.callout>
