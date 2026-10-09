<div class="docs-eyebrow">Account &amp; Profile</div>
<h1>Profile &amp; Contact Details</h1>

<p>All endpoints are under <code class="inline">/settings/profile</code>. Changing a phone number or email is a two-step flow: request a code, then verify it. Nothing changes until the code is confirmed.</p>

<h2 id="show">Get profile</h2>
<x-docs.endpoint method="GET" path="/settings/profile" />
<x-docs.code lang="json">{
  "status": true,
  "data": {
    "id": 1, "name": "Amaka Okonkwo", "phone_number": "+2348012345678",
    "email": "amaka@example.com", "email_verified": true,
    "address": "12 Marina, Lagos", "avatar": "https://app.pakapay.ng/storage/avatars/abc.jpg"
  }
}</x-docs.code>
<x-docs.try-it method="GET" path="/settings/profile" :mutating="false" />

<h2 id="update">Update name / address</h2>
<x-docs.endpoint method="PATCH" path="/settings/profile" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">name</code></td><td>Optional. String, 2–255 chars</td></tr>
    <tr><td><code class="inline">address</code></td><td>Optional, nullable. Max 500 chars</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl -X PATCH {{ config('docs.api_base') }}/settings/profile \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "name": "Amaka N. Okonkwo", "address": "12 Marina, Lagos" }'</x-docs.code>
<x-docs.code lang="json">{ "status": true, "message": "Profile updated successfully.", "data": { "name": "Amaka N. Okonkwo", "address": "12 Marina, Lagos" } }</x-docs.code>
<x-docs.try-it method="PATCH" path="/settings/profile" :fields="[
    ['name' => 'name', 'in' => 'body'],
    ['name' => 'address', 'in' => 'body'],
]" warning="Updates your real profile." />

<h2 id="avatar">Upload profile photo</h2>
<x-docs.endpoint method="POST" path="/settings/profile/avatar" note="multipart/form-data" />
<p>Field <code class="inline">avatar</code>: required image, <code class="inline">jpg</code>, <code class="inline">jpeg</code>, <code class="inline">png</code> or <code class="inline">webp</code>, max 5 MB. The previous photo is deleted.</p>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/settings/profile/avatar \
  -H "Authorization: Bearer 2|Zp0q..." \
  -F "avatar=@/path/to/photo.jpg"</x-docs.code>
<x-docs.code lang="json">{ "status": true, "message": "Profile picture updated successfully.", "data": { "avatar": "https://app.pakapay.ng/storage/avatars/abc.jpg" } }</x-docs.code>

<h2 id="phone">Change phone number</h2>
<x-docs.endpoint method="POST" path="/settings/profile/phone" />
<p>Sends a 6-digit OTP by SMS to the <em>new</em> number. <code class="inline">phone_number</code> must match <code class="inline">^\+?[0-9]{7,15}$</code> and not belong to another user. Valid for 10 minutes.</p>
<x-docs.code lang="json">{
  "status": true,
  "message": "A verification code has been sent to +2348099998888.",
  "data": { "pending_phone_number": "+2348099998888", "expires_in": "10 minutes" }
}</x-docs.code>
<x-docs.try-it method="POST" path="/settings/profile/phone" :fields="[
    ['name' => 'phone_number', 'in' => 'body', 'required' => true],
]" warning="Sends a real SMS." />
<x-docs.endpoint method="POST" path="/settings/profile/phone/verify" />
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/settings/profile/phone/verify \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "otp": "123456" }'</x-docs.code>
<x-docs.code lang="json">{ "status": true, "message": "Phone number updated successfully.", "data": { "phone_number": "+2348099998888" } }</x-docs.code>
<x-docs.try-it method="POST" path="/settings/profile/phone/verify" :fields="[
    ['name' => 'otp', 'in' => 'body', 'required' => true],
]" :high-stakes="true" warning="Your login phone number changes to the pending one." />
<p>Errors (422): <code class="inline">No pending phone number change found. Please request a new code.</code>, <code class="inline">This code has expired. Please request a new one.</code>, <code class="inline">Invalid verification code.</code></p>

<h2 id="email">Add or change email</h2>
<x-docs.endpoint method="POST" path="/settings/profile/email" />
<p><code class="inline">email</code> must pass RFC + DNS validation and be unique. A 6-digit code (valid 15 minutes) is emailed to the new address. Submitting the address that is already verified returns 422 <code class="inline">That is already your verified email address.</code></p>
<x-docs.code lang="json">{
  "status": true,
  "message": "A 6-digit verification code has been sent to amaka@new.com. It expires in 15 minutes.",
  "data": { "pending_email": "amaka@new.com", "expires_in": "15 minutes" }
}</x-docs.code>
<x-docs.try-it method="POST" path="/settings/profile/email" :fields="[
    ['name' => 'email', 'in' => 'body', 'type' => 'email', 'required' => true],
]" warning="Sends a real email." />
<x-docs.endpoint method="POST" path="/settings/profile/email/verify" />
<x-docs.code lang="json">{ "status": true, "message": "Email address updated successfully.", "data": { "email": "amaka@new.com", "email_verified": true } }</x-docs.code>
<x-docs.try-it method="POST" path="/settings/profile/email/verify" :fields="[
    ['name' => 'otp', 'in' => 'body', 'required' => true],
]" warning="Replaces your account email." />

<h2 id="remove-email">Remove email</h2>
<x-docs.endpoint method="DELETE" path="/settings/profile/email" />
<p>Clears the email and any pending change. The user stops receiving email notifications and login alerts.</p>
<x-docs.code lang="json">{ "status": true, "message": "Email address removed. You will no longer receive email notifications." }</x-docs.code>
<x-docs.try-it method="DELETE" path="/settings/profile/email" :high-stakes="true" warning="Really removes your email address." />
