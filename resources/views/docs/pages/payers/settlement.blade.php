<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>Settlement Adapter</h1>

<p>The platform <strong>never holds funds</strong>. It decides <em>when</em> money should move; your core system actually moves it. The connection between the two is a <strong>settlement adapter</strong> — a small piece of platform code that translates four operations into calls to your system's API.</p>

<x-docs.callout type="warn" title="Status today">
  <p>Only the <strong>sandbox</strong> adapter exists — it simulates a core system and stores nothing. A real adapter is built once your system's API is available (see <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'going-live']) }}">Going Live</a>). Until then an account cannot be switched to live.</p>
</x-docs.callout>

<h2 id="operations">The four operations</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Operation</th><th>When the platform calls it</th><th>Receives</th><th>Must return</th></tr></thead>
  <tbody>
    <tr>
      <td><code class="inline">hold</code></td>
      <td>Inside <code class="inline">POST /codes</code>, before a code exists</td>
      <td>A reference for the hold (the code's UUID), <code class="inline">source_account_reference</code>, amount in minor units, currency</td>
      <td>OK + a <strong>hold reference</strong>, or rejected + a reason (shown to you as <code class="inline">settlement_rejected</code>)</td>
    </tr>
    <tr>
      <td><code class="inline">capture</code></td>
      <td>Right after a call is verified</td>
      <td>The hold reference, the merchant's <code class="inline">account_reference</code>, the <strong>transaction reference</strong> (<code class="inline">TXN-…</code>)</td>
      <td>OK + a <strong>settlement reference</strong> (→ <code class="inline">transaction.settled</code>), or rejected + a reason (→ <code class="inline">transaction.failed</code>)</td>
    </tr>
    <tr>
      <td><code class="inline">release</code></td>
      <td>On cancel, expiry, or failed capture</td>
      <td>The hold reference</td>
      <td>OK. Failures are logged; the code's state has already changed.</td>
    </tr>
    <tr>
      <td><code class="inline">status</code></td>
      <td>During <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'lifecycle']) }}">reconciliation</a>, when a capture never reported back</td>
      <td>The transaction reference</td>
      <td><code class="inline">captured</code> (+ reference), <code class="inline">released</code>, <code class="inline">held</code>, or <code class="inline">unknown</code></td>
    </tr>
  </tbody>
</table></div>

<h2 id="requirements">What your system must guarantee</h2>
<ul>
  <li><strong>Capture is idempotent on the transaction reference.</strong> The platform may ask twice (a timeout, a reconciliation retry). The second call must return the original result and must never move money twice.</li>
  <li><strong>Status answers from your own records.</strong> After a capture call that never answered, the platform asks <code class="inline">status</code>. Answer truthfully; say <code class="inline">unknown</code> if you can't tell. The platform acts only on a clear answer.</li>
  <li><strong>Holds are real reservations</strong> that the payer can't spend elsewhere until released or captured — and expire safely on your side if the platform never releases them.</li>
  <li><strong>Timeouts.</strong> Capture runs outside the platform's database lock but within the payer's call handling; answer promptly. A slow or failing system leaves the transaction <code class="inline">pending</code> for reconciliation rather than losing it.</li>
  <li><strong>Your limits live here.</strong> The platform enforces only an optional per-account maximum amount. Per-customer daily limits, KYC tiers, velocity and fraud rules belong in your <code class="inline">hold</code> decision: reject the hold and the code is never created.</li>
</ul>

<h2 id="state-diagram">How the calls line up</h2>
<x-docs.code lang="text">POST /codes ────────── hold ─────────▶  reserve funds        → code: issued
payer's call ─────── (verify) ─────────                          → code: redeemed, txn: pending
                      capture ─────────▶  move to merchant       → txn: settled  / code: settled
                      (declined) ──────▶  –                      → txn: failed   / code: failed  ── release ▶
cancel / expiry ───── release ─────────▶  free the hold          → code: cancelled / expired
no answer to capture ─ status ─────────▶  captured | released | held | unknown   (every 5 min)</x-docs.code>

<h2 id="sandbox">The sandbox adapter</h2>
<p>For development and sandbox accounts the platform simulates your system. Nothing is stored, and PakaPay can make it misbehave by setting these on your account, so you can test failure handling end to end:</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Setting</th><th>Effect</th></tr></thead>
  <tbody>
    <tr><td>(default)</td><td>Hold → <code class="inline">hold_&lt;uuid&gt;</code>. Capture → <code class="inline">settle_&lt;uuid&gt;</code>. Release always succeeds.</td></tr>
    <tr><td><code class="inline">sandbox.fail_hold</code></td><td><code class="inline">POST /codes</code> is rejected: "Insufficient funds (sandbox)."</td></tr>
    <tr><td><code class="inline">sandbox.fail_capture</code></td><td>Capture is declined: "Capture declined (sandbox)." → <code class="inline">transaction.failed</code></td></tr>
    <tr><td><code class="inline">sandbox.status</code></td><td>What <code class="inline">status</code> answers during reconciliation (<code class="inline">captured</code>, <code class="inline">released</code>, <code class="inline">held</code>, otherwise <code class="inline">unknown</code>)</td></tr>
  </tbody>
</table></div>
<p>Ask your PakaPay contact to turn these on for your sandbox account. The simulator is <strong>refused</strong> for live accounts: a live account configured with it fails every call rather than pretend to settle real money.</p>
