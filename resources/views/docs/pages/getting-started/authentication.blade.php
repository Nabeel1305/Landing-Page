<div class="docs-eyebrow">Getting Started</div>
<h1>Authentication</h1>

<p>PakaPay uses <a href="https://laravel.com/docs/sanctum" target="_blank">Laravel Sanctum</a> bearer tokens. Identity is a <strong>phone number + 4-digit PIN</strong>; there are no passwords. Registration and new-device sign-in are confirmed by a 6-digit SMS OTP.</p>

<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Flow</th><th>Calls</th></tr></thead>
  <tbody>
    <tr><td>Create an account</td><td><code class="inline">POST /auth/register</code> → <code class="inline">POST /auth/register/verify</code> (returns token)</td></tr>
    <tr><td>Sign in</td><td><code class="inline">POST /auth/login</code> (returns token)</td></tr>
    <tr><td>Sign in from a new device</td><td><code class="inline">POST /auth/login</code> → <code class="inline">requires_device_verification</code> → <code class="inline">POST /auth/verify-device</code> (returns token)</td></tr>
    <tr><td>Forgot PIN</td><td><code class="inline">POST /auth/forgot-pin</code> → <code class="inline">POST /auth/reset-pin</code></td></tr>
    <tr><td>Sign out</td><td><code class="inline">POST /auth/logout</code></td></tr>
  </tbody>
</table>
</div>

<h2 id="register">1. Register</h2>
<x-docs.endpoint method="POST" path="/auth/register" :auth="false" note="Throttle: auth-attempts (10/min per IP)" />
<p>Step one of a two-step flow. It validates the input and sends an OTP to the phone — <strong>but only when the phone/email is not already registered</strong>. The response is identical either way, so the endpoint can't be used to discover who has an account. No account exists until step two.</p>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Field</th><th>Type</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">name</code></td><td>string</td><td>Required, max 255</td></tr>
    <tr><td><code class="inline">phone_number</code></td><td>string</td><td>Required. <code class="inline">^\+?[0-9]{7,15}$</code></td></tr>
    <tr><td><code class="inline">pin</code></td><td>string</td><td>Required. Exactly 4 digits</td></tr>
    <tr><td><code class="inline">pin_confirmation</code></td><td>string</td><td>Required. Must equal <code class="inline">pin</code></td></tr>
    <tr><td><code class="inline">email</code></td><td>string</td><td>Optional. Valid email, max 255</td></tr>
    <tr><td><code class="inline">account_type</code></td><td>string</td><td>Optional: <code class="inline">personal</code> (default) or <code class="inline">business</code>. Chosen once, here — business accounts unlock <a href="{{ route('docs.show', ['section' => 'payment-points', 'page' => 'overview']) }}">Payment Points</a> and financial reports.</td></tr>
  </tbody>
</table>
</div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Amaka Okonkwo",
    "phone_number": "+2348012345678",
    "pin": "4821",
    "pin_confirmation": "4821",
    "email": "amaka@example.com",
    "account_type": "personal"
  }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "message": "If this phone number is not already registered, we've sent a verification code to it. Enter the code to continue."
}</x-docs.code>
<x-docs.try-it method="POST" path="/auth/register" :auth="false" :fields="[
    ['name' => 'name', 'in' => 'body', 'required' => true, 'default' => 'Amaka Okonkwo'],
    ['name' => 'phone_number', 'in' => 'body', 'required' => true, 'placeholder' => '+2348012345678'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
    ['name' => 'pin_confirmation', 'in' => 'body', 'type' => 'password', 'required' => true],
    ['name' => 'email', 'in' => 'body', 'type' => 'email'],
    ['name' => 'account_type', 'in' => 'body', 'type' => 'select', 'options' => ['personal', 'business'], 'default' => 'personal'],
]" warning="Sends a real SMS. Pending signups expire after 10 minutes." />

<x-docs.callout type="warn" title="Breaking change from the single-step flow">
  <p><code class="inline">/auth/register</code> no longer returns a token. Clients written against the old flow must call <code class="inline">/auth/register/verify</code> next.</p>
</x-docs.callout>

<h2 id="verify-registration">2. Verify registration</h2>
<x-docs.endpoint method="POST" path="/auth/register/verify" :auth="false" note="Throttle: otp (5 per 10 min per IP)" />
<p>Confirms the OTP, creates the account and returns the first token. A wrong or expired code returns <strong>422</strong> <code class="inline">Invalid or expired code.</code>; repeated wrong codes invalidate the pending signup (<strong>429</strong>).</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/auth/register/verify \
  -H "Content-Type: application/json" \
  -d '{ "phone_number": "+2348012345678", "otp": "123456" }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "message": "Account created successfully.",
  "data": {
    "user": {
      "id": 1, "name": "Amaka Okonkwo", "phone_number": "+2348012345678",
      "account_type": "personal", "avatar": null, "role": "user",
      "is_active": true, "is_locked": false,
      "kyc": { "status": "none", "tier": 1, "daily_limit": 50000, "has_bvn": false, "verified_at": null },
      "created_at": "2026-10-08 15:14:02", "updated_at": "2026-10-08 15:14:02"
    },
    "token": "1|k3j2h4g5f6..."
  }
}</x-docs.code>
<x-docs.try-it method="POST" path="/auth/register/verify" :auth="false" :fields="[
    ['name' => 'phone_number', 'in' => 'body', 'required' => true, 'placeholder' => '+2348012345678'],
    ['name' => 'otp', 'in' => 'body', 'required' => true, 'placeholder' => '123456'],
]" warning="Creates a real account." />

<h2 id="login">Login</h2>
<x-docs.endpoint method="POST" path="/auth/login" :auth="false" note="Throttle: auth-attempts (10/min per IP) + 5-attempt account lockout" />
<p>Send the phone number and PIN. Send the device's stable identifier in the <code class="inline">X-Device-Id</code> header so PakaPay can bind the account to it.</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/auth/login \
  -H "Content-Type: application/json" \
  -H "X-Device-Id: 7f3c9a52-device-uuid" \
  -d '{ "phone_number": "+2348012345678", "pin": "4821" }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "message": "Login successful.",
  "data": { "user": { "id": 1, "name": "Amaka Okonkwo", "...": "..." }, "token": "2|Zp0q..." }
}</x-docs.code>
<x-docs.try-it method="POST" path="/auth/login" :auth="false" :fields="[
    ['name' => 'X-Device-Id', 'in' => 'header', 'placeholder' => 'optional device identifier'],
    ['name' => 'phone_number', 'in' => 'body', 'required' => true, 'placeholder' => '+2348012345678'],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
]" :mutating="false" warning="A successful login signs out every other session on the account (all other tokens are deleted)." />
<ul>
  <li><strong>Wrong phone or PIN</strong> → 401 <code class="inline">Invalid phone number or PIN.</code> (same message for both, so numbers can't be probed).</li>
  <li><strong>5 failed attempts</strong> lock that phone number out for 15 minutes → 429 with the seconds remaining.</li>
  <li><strong>Single session:</strong> each login/verification deletes all previous tokens. A user is signed in on one device at a time.</li>
  <li>If the user has a verified email, a "new login" alert email is queued.</li>
</ul>

<h2 id="device-binding">Device binding &amp; new-device verification</h2>
<p>The first login that sends <code class="inline">X-Device-Id</code> binds the account to that device. If a later login arrives from a <em>different</em> device, no token is issued. Instead:</p>
<x-docs.code lang="json">HTTP/1.1 401
{
  "status": false,
  "message": "New device detected. Enter the verification code sent to your phone to continue.",
  "requires_device_verification": true
}</x-docs.code>
<p>A 6-digit OTP (valid 10 minutes) has been texted to the account's phone. Submit it with the new device id:</p>
<x-docs.endpoint method="POST" path="/auth/verify-device" :auth="false" note="Throttle: otp" />
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/auth/verify-device \
  -H "Content-Type: application/json" \
  -d '{ "phone_number": "+2348012345678", "otp": "123456", "device_id": "new-device-uuid" }'</x-docs.code>
<x-docs.code lang="json">{
  "status": true,
  "message": "Device verified. Your previous session has been signed out.",
  "data": { "user": { "...": "..." }, "token": "3|Ab9..." }
}</x-docs.code>
<x-docs.try-it method="POST" path="/auth/verify-device" :auth="false" :fields="[
    ['name' => 'phone_number', 'in' => 'body', 'required' => true],
    ['name' => 'otp', 'in' => 'body', 'required' => true],
    ['name' => 'device_id', 'in' => 'body', 'required' => true],
]" warning="Re-binds the account to the device id you enter." />
<x-docs.callout type="note" title="Beyond login">
  <p>Every authenticated request is also fingerprinted (<code class="inline">X-Device-Id</code> + <code class="inline">User-Agent</code> + <code class="inline">X-App-Version</code>) and checked against the bound device and for concurrent sessions. Changing the device id mid-session will be flagged by the <a href="{{ route('docs.show', ['section' => 'platform', 'page' => 'fraud-engine']) }}">fraud engine</a>.</p>
</x-docs.callout>

<h2 id="using-the-token">Using the token</h2>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/user/me \
  -H "Authorization: Bearer 2|Zp0q..." \
  -H "X-Device-Id: 7f3c9a52-device-uuid"</x-docs.code>
<ul>
  <li><strong>Inactivity timeout:</strong> a token unused for 30 minutes is revoked and the next call returns 401 <code class="inline">SESSION_EXPIRED</code>. Send the user back to login.</li>
  <li><strong>Absolute lifetime:</strong> tokens also expire 30 days after issue.</li>
  <li><strong>Account states:</strong> locked accounts get 403 <code class="inline">ACCOUNT_LOCKED</code>; suspended devices get 403 <code class="inline">DEVICE_SUSPENDED</code>; while the operator kill switch is on, every authenticated route returns 503 <code class="inline">SERVICE_SUSPENDED</code>. See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'errors']) }}">Errors</a>.</li>
</ul>

<h2 id="forgot-pin">Forgot PIN</h2>
<x-docs.endpoint method="POST" path="/auth/forgot-pin" :auth="false" note="Throttle: otp" />
<p>Sends an OTP (valid 10 minutes) if the number is registered. The response is generic either way.</p>
<x-docs.code lang="json">{ "status": true, "message": "If this phone number is registered, an OTP has been sent to it. Valid for 10 minutes." }</x-docs.code>
<x-docs.try-it method="POST" path="/auth/forgot-pin" :auth="false" :fields="[
    ['name' => 'phone_number', 'in' => 'body', 'required' => true],
]" warning="Sends a real SMS if the number is registered." />

<x-docs.endpoint method="POST" path="/auth/reset-pin" :auth="false" note="Throttle: otp" />
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/auth/reset-pin \
  -H "Content-Type: application/json" \
  -d '{ "phone_number": "+2348012345678", "otp": "123456", "pin": "9034", "pin_confirmation": "9034" }'</x-docs.code>
<x-docs.code lang="json">{ "status": true, "message": "PIN reset successfully. Please log in with your new PIN." }</x-docs.code>
<p>All existing tokens for the account are deleted on success. OTP limits: 3 wrong attempts invalidate the code; at most 3 OTP sends per phone per hour.</p>
<x-docs.try-it method="POST" path="/auth/reset-pin" :auth="false" :fields="[
    ['name' => 'phone_number', 'in' => 'body', 'required' => true],
    ['name' => 'otp', 'in' => 'body', 'required' => true],
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
    ['name' => 'pin_confirmation', 'in' => 'body', 'type' => 'password', 'required' => true],
]" :high-stakes="true" warning="Really changes the PIN and signs out every session." />

<h2 id="verify-pin">Verify PIN</h2>
<x-docs.endpoint method="POST" path="/auth/verify-pin" note="Throttle: pin (10/min per user)" />
<p>Re-proves the PIN of the signed-in user — use it as a gate before showing sensitive screens. Wrong PINs are counted by the fraud engine (5 failures lock PIN entry for 15 minutes and flag the account as locked until an operator unlocks it).</p>
<x-docs.code lang="json">// 200
{ "status": true, "message": "PIN verified successfully." }

// 422
{ "success": false, "message": "Incorrect PIN.", "attempts_remaining": 3 }</x-docs.code>
<x-docs.try-it method="POST" path="/auth/verify-pin" :fields="[
    ['name' => 'pin', 'in' => 'body', 'type' => 'password', 'required' => true],
]" :mutating="false" />

<h2 id="logout">Logout</h2>
<x-docs.endpoint method="POST" path="/auth/logout" />
<p>Deletes the current token.</p>
<x-docs.code lang="json">{ "status": true, "message": "Logged out successfully." }</x-docs.code>
<x-docs.try-it method="POST" path="/auth/logout" warning="Revokes the token stored in this browser — you'll need to log in again." />
