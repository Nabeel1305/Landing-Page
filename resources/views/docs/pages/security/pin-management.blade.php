<div class="docs-eyebrow">Security &amp; Identity</div>
<h1>PIN &amp; Spending Limits</h1>

<h2 id="change-pin">Change the PIN</h2>
<p>A two-step flow. Step one proves the <em>current</em> PIN before an OTP is sent, so a stolen session alone can't be used to spam the owner's phone.</p>
<x-docs.endpoint method="POST" path="/settings/security/change-pin" />
<p>Body: <code class="inline">current_pin</code> (4 digits). Sends a 6-digit OTP to the registered phone. Errors (422): <code class="inline">Current PIN is incorrect.</code>, an OTP-resend limit message, <code class="inline">Could not send verification code right now. Please try again shortly.</code></p>
<x-docs.code lang="json">{ "status": true, "message": "A verification code has been sent to your registered number." }</x-docs.code>
<x-docs.try-it method="POST" path="/settings/security/change-pin" :fields="[['name' => 'current_pin', 'in' => 'body', 'type' => 'password', 'required' => true]]" warning="Sends a real SMS." />

<x-docs.endpoint method="POST" path="/settings/security/change-pin/verify" note="Throttle: otp" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">otp</code></td><td>Required, 6 characters</td></tr>
    <tr><td><code class="inline">new_pin</code></td><td>Required, 4 digits</td></tr>
    <tr><td><code class="inline">new_pin_confirmation</code></td><td>Required, must equal <code class="inline">new_pin</code></td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">{ "status": true, "message": "PIN changed successfully. Please log in again." }</x-docs.code>
<p>On success <strong>all tokens are deleted</strong> — the app must send the user back to login. Step two cannot be called without step one (a pending marker, valid for a limited time, is required). Wrong codes count toward OTP limits (3 attempts).</p>
<x-docs.try-it method="POST" path="/settings/security/change-pin/verify" :high-stakes="true" :fields="[
    ['name' => 'otp', 'in' => 'body', 'required' => true],
    ['name' => 'new_pin', 'in' => 'body', 'type' => 'password', 'required' => true],
    ['name' => 'new_pin_confirmation', 'in' => 'body', 'type' => 'password', 'required' => true],
]" warning="Changes your real PIN and signs you out everywhere." />
<p>Forgotten PIN (signed out)? Use <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}#forgot-pin">Forgot PIN</a>.</p>

<h2 id="spending-limits">Spending limits</h2>
<x-docs.endpoint method="POST" path="/settings/security/spending-limits" />
<p>Personal caps, enforced by <code class="inline">/pay/initiate</code> in addition to the system-wide fraud limits and the KYC tier cap. Defaults: ₦50,000 per transaction, ₦200,000 per day.</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">daily_limit</code></td><td>Required, ₦100 – ₦5,000,000</td></tr>
    <tr><td><code class="inline">per_transaction</code></td><td>Required, ₦100 – ₦1,000,000</td></tr>
    <tr><td><code class="inline">pin</code></td><td>Required, 4 digits (wrong → 422 <code class="inline">Incorrect PIN. Limits were not changed.</code>)</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">{ "status": true, "message": "Spending limits updated.", "data": { "daily_limit": "₦150,000.00", "per_transaction": "₦30,000.00" } }</x-docs.code>
<x-docs.callout type="warn" title="Higher caps do not override system limits">
  <p>Setting a limit above the platform's fraud caps (₦50,000 per transfer, ₦200,000 per day) doesn't raise what you can send — the lowest limit wins. See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'rate-limits']) }}#business-limits">Rate Limits</a>.</p>
</x-docs.callout>
<x-docs.try-it method="POST" path="/settings/security/spending-limits" :fields="[
    ['name' => 'daily_limit', 'in' => 'body', 'type' => 'number', 'required' => true, 'default' => '200000'],
    ['name' => 'per_transaction', 'in' => 'body', 'type' => 'number', 'required' => true, 'default' => '50000'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
]" warning="Changes your real spending limits." />
