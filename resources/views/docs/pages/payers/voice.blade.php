<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>The Payer's Call</h1>

<p>The payer needs only a phone — any phone, no data, no app. They call the voice number you show them, key in the code and press <code class="inline">#</code>.</p>

<h2 id="the-call">What the payer experiences</h2>
<ol>
  <li>They dial the voice number (shown by your app next to the code).</li>
  <li>A voice says: <em>"Please enter your payment code, then press hash."</em></li>
  <li>They key the digits and <code class="inline">#</code>. They have 30 seconds.</li>
  <li>A voice says: <em>"Thank you. You will receive a confirmation shortly."</em> — and the call ends.</li>
</ol>
<p>That thank-you is spoken <strong>whatever the outcome</strong>: success, wrong code, expired, already used, wrong phone, too many attempts. This is deliberate. If the line said "wrong code", an attacker could dial thousands of guesses and learn which were close. The confirmation (or the failure) has to come from <strong>you</strong>, triggered by your <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'events']) }}">webhook</a>, by SMS, push or in-app message.</p>

<h2 id="checks">What the platform checks</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Check</th><th>If it fails</th></tr></thead>
  <tbody>
    <tr><td>The dialled number exists, is active, and the URL token matches</td><td>Call is refused (<code class="inline">403</code> to the telephony provider); counts against a per-address limit</td></tr>
    <tr><td>A tenant can be determined (own number, or short-code prefix on a shared number)</td><td>Silently ignored</td></tr>
    <tr><td>Attempt limits: at most <strong>5 wrong guesses per caller</strong> in 10 minutes. Callers who are not registered subscribers also share a small per-minute budget, so a flood of guesses from made-up numbers cannot crowd out registered payers</td><td>Rejected; even a correct code is ignored while the caller is locked out or the shared budget is used up</td></tr>
    <tr><td>The code exists for your organisation and is in state <code class="inline">issued</code></td><td>Rejected (unknown, used, cancelled)</td></tr>
    <tr><td>The code has not expired</td><td>Rejected</td></tr>
    <tr><td>If caller binding is on: the caller's number matches the subscriber's phone</td><td>Rejected</td></tr>
  </tbody>
</table></div>
<p>When every check passes, in one database transaction the code becomes <code class="inline">redeemed</code> and a <code class="inline">pending</code> transaction is created; then <code class="inline">code.redeemed</code> is sent and your system is asked to <strong>capture</strong>. A code can be redeemed <strong>once</strong>: if twelve calls arrive at the same instant, exactly one wins.</p>

<h2 id="telephony">Telephony</h2>
<p>Calls are handled by the platform's telephony provider (Africa's Talking voice); you never integrate with it directly.</p>

<h2 id="show-the-code">Showing the code in your app</h2>
<ul>
  <li>Display the <code class="inline">code</code> exactly as returned, grouped for readability if you like (the platform strips everything but digits).</li>
  <li>Show the voice number next to it, and a countdown to <code class="inline">expires_at</code>.</li>
  <li>If the app is a smartphone app, a tap-to-dial link such as <code class="inline">tel:+2347000000000,,,482019377104#</code> (commas are pauses) dials and keys the code for them.</li>
  <li>Offer "Cancel" which calls <code class="inline">POST /codes/{id}/cancel</code>.</li>
</ul>
