<div class="docs-eyebrow">Account &amp; Profile</div>
<h1>Current User</h1>

<h2 id="me">Get the signed-in user</h2>
<x-docs.endpoint method="GET" path="/user/me" />
<p>Returns the account's identity, flags and KYC standing. Call it after login and whenever the app resumes — it is the source of truth for <code class="inline">is_locked</code>, <code class="inline">account_type</code> and the daily limit.</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/user/me -H "Authorization: Bearer 2|Zp0q..."</x-docs.code>
<x-docs.try-it method="GET" path="/user/me" :mutating="false" />
<x-docs.code lang="json">{
  "status": true,
  "message": "User profile retrieved.",
  "code": 200,
  "data": {
    "id": 1,
    "name": "Amaka Okonkwo",
    "phone_number": "+2348012345678",
    "account_type": "personal",
    "avatar": null,
    "address": null,
    "role": "user",
    "is_active": true,
    "is_locked": false,
    "email": "amaka@example.com",
    "email_verified": true,
    "kyc": {
      "status": "verified",
      "tier": 2,
      "daily_limit": 200000,
      "has_bvn": true,
      "verified_at": "2026-10-01 09:12:44",
      "rejection_reason": null
    }
  }
}</x-docs.code>

<h2 id="fields">Fields</h2>
<div class="docs-table-wrap">
<table class="docs-table">
  <thead><tr><th>Field</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">account_type</code></td><td><code class="inline">personal</code> | <code class="inline">business</code>. Set at registration. Gates Payment Points and financial reports.</td></tr>
    <tr><td><code class="inline">is_locked</code></td><td>Operator lock. When true, most routes return 403 <code class="inline">ACCOUNT_LOCKED</code>.</td></tr>
    <tr><td><code class="inline">kyc.status</code></td><td><code class="inline">none</code> | <code class="inline">pending</code> | <code class="inline">verified</code> | <code class="inline">failed</code> | <code class="inline">suspended</code></td></tr>
    <tr><td><code class="inline">kyc.tier</code></td><td>1, 2 or 3. Determines <code class="inline">daily_limit</code>: ₦50,000 / ₦200,000 / ₦5,000,000.</td></tr>
    <tr><td><code class="inline">kyc.has_bvn</code></td><td>Whether a BVN is on file. The BVN itself is encrypted and never returned.</td></tr>
    <tr><td><code class="inline">kyc.rejection_reason</code></td><td>Only populated when status is <code class="inline">failed</code> or <code class="inline">suspended</code>.</td></tr>
    <tr><td><code class="inline">role</code></td><td>Account role string; informational for clients.</td></tr>
  </tbody>
</table>
</div>
<p>The login and registration responses embed a similar <code class="inline">user</code> object (without <code class="inline">address</code>/<code class="inline">email</code>, with <code class="inline">created_at</code>/<code class="inline">updated_at</code>).</p>

<h2 id="device-token">Register a push token</h2>
<x-docs.endpoint method="POST" path="/device/token" />
<p>Stores the device's FCM token so PakaPay can send push notifications (payment received, etc.). Call on login and whenever the token refreshes.</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/device/token \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "fcm_token": "fcm-device-token" }'</x-docs.code>
<x-docs.code lang="json">{ "success": true }</x-docs.code>
<x-docs.try-it method="POST" path="/device/token" :fields="[
    ['name' => 'fcm_token', 'in' => 'body', 'required' => true],
]" warning="Replaces the push token stored for your account." />
