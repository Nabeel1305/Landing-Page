<div class="docs-eyebrow">Getting Started</div>
<h1>Rate Limits</h1>

<p>Limits exist at two layers. The HTTP layer (below) is a backstop keyed by IP or user; the <a href="{{ route('docs.show', ['section' => 'platform', 'page' => 'fraud-engine']) }}">fraud engine</a> adds business limits (PIN attempts, OTP sends, velocity) on top.</p>

<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Limiter</th><th>Limit</th><th>Keyed by</th><th>Applies to</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">auth-attempts</code></td><td>10 / minute</td><td>IP</td><td><code class="inline">/auth/register</code>, <code class="inline">/auth/login</code></td></tr>
    <tr><td><code class="inline">otp</code></td><td>5 / 10 minutes</td><td>IP</td><td><code class="inline">/auth/register/verify</code>, <code class="inline">/auth/forgot-pin</code>, <code class="inline">/auth/reset-pin</code>, <code class="inline">/auth/verify-device</code>, <code class="inline">/settings/security/change-pin/verify</code></td></tr>
    <tr><td><code class="inline">pin</code></td><td>10 / minute</td><td>User</td><td><code class="inline">/auth/verify-pin</code></td></tr>
    <tr><td><code class="inline">payment</code></td><td>20 / minute</td><td>User</td><td>All <code class="inline">/pay/*</code></td></tr>
    <tr><td><code class="inline">gate-connect</code></td><td>6 / minute</td><td>User</td><td><code class="inline">/gates/connect</code> (8-digit share codes are guessable secrets)</td></tr>
    <tr><td><code class="inline">api</code></td><td>120 / minute</td><td>User (IP if anonymous)</td><td>Every authenticated route</td></tr>
    <tr><td>Webhooks</td><td>60 / minute</td><td>IP</td><td><code class="inline">/webhook/smile-id</code>, <code class="inline">/webhook/youverify</code></td></tr>
  </tbody>
</table>
</div>
<p>Limits stack: a payment call is counted against both <code class="inline">api</code> (120/min) and <code class="inline">payment</code> (20/min).</p>

<h2 id="business-limits">Business-level limits</h2>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Limit</th><th>Value</th><th>Error code</th></tr></thead>
  <tbody>
    <tr><td>Failed PINs before lock</td><td>5 → PIN locked 15 min and account flagged locked</td><td><code class="inline">pin_brute_force</code> / <code class="inline">PIN_LOCKED</code></td></tr>
    <tr><td>Failed logins before lockout</td><td>5 → locked 15 min per phone</td><td>429</td></tr>
    <tr><td>Wrong OTP attempts</td><td>3 → code invalidated</td><td><code class="inline">otp_brute_force</code></td></tr>
    <tr><td>OTP sends</td><td>3 per phone per hour</td><td><code class="inline">otp_resend_abuse</code></td></tr>
    <tr><td>Sends per hour</td><td>10</td><td><code class="inline">velocity_hourly_count</code></td></tr>
    <tr><td>Single transfer</td><td>₦50,000</td><td><code class="inline">velocity_single_limit</code></td></tr>
    <tr><td>Daily sending volume</td><td>₦200,000</td><td><code class="inline">velocity_daily_volume</code></td></tr>
    <tr><td>QR decodes</td><td>20 per 10 min per user</td><td><code class="inline">qr_decode_rate_limit</code></td></tr>
  </tbody>
</table>
</div>

<h2 id="exceeding-a-limit">When you exceed a limit</h2>
<p>HTTP throttles return <code class="inline">429 Too Many Requests</code> with a <code class="inline">Retry-After</code> header (seconds).</p>
<x-docs.code lang="bash">HTTP/1.1 429 Too Many Requests
Retry-After: 42
X-RateLimit-Limit: 20
X-RateLimit-Remaining: 0</x-docs.code>
<x-docs.callout type="note" title="Building a client">
  <p>Back off using <code class="inline">Retry-After</code> rather than a fixed delay. Do not auto-retry OTP and PIN endpoints — repeated failures count towards lockouts.</p>
</x-docs.callout>
