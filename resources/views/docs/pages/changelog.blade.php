<div class="docs-eyebrow">Reference</div>
<h1>API Changelog</h1>

<p>Notable behaviour changes to the PakaPay API, most recent first. This changelog was reconstructed from the repository's migrations and security notes; dates are those recorded in the migration filenames and the <code class="inline">SECURITY_CHANGES_2026-07-22</code> document.</p>

<h2 id="undated">Present in this build (date not recorded)</h2>
<ul>
  <li>KYC runs on Youverify (BVN + liveness); the earlier Smile ID flow is no longer routed but its webhook remains.</li>
  <li>Business-only reporting: <code class="inline">/settings/activity/report</code> and <code class="inline">/settings/activity/payment-points</code>; <code class="inline">payment_point_only</code> filter on history.</li>
</ul>

<h2 id="2026-09">September 2026</h2>
<ul>
  <li><strong>Multi-profile schema dropped</strong> (<code class="inline">2026_09_06</code>): the short-lived per-user profiles model was removed; <code class="inline">account_type</code> (<code class="inline">personal</code> | <code class="inline">business</code>) on the user is authoritative.</li>
  <li>Device binding: the global uniqueness on <code class="inline">bound_device_id</code> was removed (<code class="inline">2026_09_24</code>), so one physical device can be bound to more than one account.</li>
</ul>

<h2 id="2026-08">August 2026</h2>
<ul>
  <li><strong>Payment Points</strong> (<code class="inline">/gates</code>) introduced for business accounts, with per-point QR codes, share codes and reporting; <code class="inline">account_type</code> added at registration.</li>
  <li><strong>Profile editing</strong>: name/address, avatar, and OTP-verified phone and email changes under <code class="inline">/settings/profile</code>.</li>
  <li>Bank account uniqueness tightened: one account number can be linked to only one PakaPay user.</li>
</ul>

<h2 id="2026-07">July 2026</h2>
<ul>
  <li><strong>Registration is now two-step</strong> (<code class="inline">/auth/register</code> then <code class="inline">/auth/register/verify</code>) and returns a generic response to prevent account enumeration. <em>Breaking:</em> <code class="inline">/auth/register</code> no longer returns a token.</li>
  <li><strong>Sessions:</strong> 30-minute inactivity timeout (<code class="inline">SESSION_EXPIRED</code>) and a 30-day absolute token lifetime.</li>
  <li><strong>Kill switch</strong> and <strong>device suspension</strong> (<code class="inline">SERVICE_SUSPENDED</code>, <code class="inline">DEVICE_SUSPENDED</code>).</li>
  <li><strong>KMS transaction signing</strong> replaced the HMAC-with-shared-secret scheme; each transaction records <code class="inline">signing_key_id</code>.</li>
  <li><strong>Tamper-evident audit log</strong> and access logging on all admin screens that expose user data.</li>
  <li><strong>Selfie retention removed</strong> (<code class="inline">kyc_selfie_url</code> dropped).</li>
  <li><strong>Offline (DTMF) payment rail</strong>: <code class="inline">/device/offline-key</code>, <code class="inline">/device/offline-voice-number</code>, Africa's Talking voice webhooks, 31-digit signed codes.</li>
  <li><strong>Idempotency</strong> on payment confirmation (<code class="inline">Idempotency-Key</code>) and device binding at login (<code class="inline">X-Device-Id</code>, <code class="inline">requires_device_verification</code>).</li>
</ul>

<x-docs.callout type="note" title="Scope">
  <p>This changelog covers the mobile/partner API. Admin dashboard changes are only mentioned where they affect API behaviour.</p>
</x-docs.callout>
