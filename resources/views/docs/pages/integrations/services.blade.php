<div class="docs-eyebrow">Partners &amp; Webhooks</div>
<h1>Third-party Services</h1>

<p>PakaPay calls these providers server-side. Credentials are resolved by <code class="inline">ServiceKeyStore</code>: an active row in the <code class="inline">service_api_keys</code> table (editable in <em>Admin → Settings → API keys</em>) wins; otherwise the matching <code class="inline">.env</code> variable is used. Rotating a key therefore needs no deploy.</p>

<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Service</th><th>Used for</th><th>Where in code</th></tr></thead>
  <tbody>
    <tr><td>Paystack</td><td>Bank list; resolving account numbers to account names</td><td><code class="inline">PaymentUtil</code>, <code class="inline">AccountService</code> (<code class="inline">config/paystack.php</code>)</td></tr>
    <tr><td>Kudi SMS</td><td>OTP generation/verification and transaction SMS alerts; phone normalization</td><td><code class="inline">KudiSmsService</code> (<code class="inline">config/sms.php</code>)</td></tr>
    <tr><td>Twilio</td><td>Alternate SMS/OTP provider</td><td><code class="inline">TwilioService</code></td></tr>
    <tr><td>Youverify</td><td>BVN lookup; liveness token, session and result</td><td><code class="inline">YouverifyService</code></td></tr>
    <tr><td>Smile ID</td><td>Legacy identity enrolment/webhook (not routed for new checks)</td><td><code class="inline">SmileIdService</code></td></tr>
    <tr><td>Africa's Talking</td><td>Inbound voice calls for the offline rail</td><td><code class="inline">config/africastalking.php</code></td></tr>
    <tr><td>Firebase Cloud Messaging</td><td>Push notifications ("Payment Sent / Received")</td><td><code class="inline">PushNotificationService</code> (service-account JSON)</td></tr>
    <tr><td>AWS KMS</td><td>Transaction signing (asymmetric); offline device-key derivation (HMAC key)</td><td><code class="inline">TransactionSigningService</code>, <code class="inline">DeviceKeyService</code></td></tr>
    <tr><td>Resend / SMTP</td><td>Transactional email: receipts, OTPs, login alerts</td><td><code class="inline">app/Mail/*</code></td></tr>
    <tr><td>Sentry</td><td>Error reporting (after redaction)</td><td><code class="inline">config/sentry.php</code></td></tr>
    <tr><td>Slack</td><td>Operational alerts</td><td><code class="inline">config/slack.php</code></td></tr>
    <tr><td>Laravel Pulse</td><td>Performance dashboard</td><td><code class="inline">config/pulse.php</code></td></tr>
  </tbody>
</table></div>

<h2 id="otp">OTP delivery</h2>
<p>OTPs (registration, forgot PIN, PIN change, phone change) are 6 digits and valid 10 minutes. <code class="inline">KudiSmsService::sendOtp()</code> returns a <code class="inline">reference</code> that is stored server-side (for registration and PIN reset, keyed by normalized phone in cache) and later passed to <code class="inline">verifyOtp(reference, otp)</code>. Device-verification OTPs are generated locally, bcrypt-hashed in cache and sent as plain SMS. Resends are capped at 3 per phone per hour.</p>

<h2 id="phone-format">Phone normalization</h2>
<p><code class="inline">normalizePhone()</code> converts local and international forms to the provider's required format. Cache keys, rate limits and lookups all use the normalized form, so <code class="inline">0801…</code>, <code class="inline">234801…</code> and <code class="inline">+234801…</code> share a single OTP budget.</p>

<h2 id="kms">KMS usage</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Purpose</th><th>Key type</th><th>Operation</th><th>Env</th></tr></thead>
  <tbody>
    <tr><td>Transaction signing</td><td>Asymmetric, <code class="inline">SIGN_VERIFY</code> (RSASSA_PKCS1_V1_5_SHA_256)</td><td><code class="inline">Sign</code> a SHA-256 digest of the canonical transaction string; <code class="inline">Verify</code> to audit</td><td><code class="inline">TRANSACTION_SIGNING_KMS_*</code></td></tr>
    <tr><td>Offline device keys</td><td>HMAC, <code class="inline">GENERATE_VERIFY_MAC</code> (HMAC_256)</td><td><code class="inline">GenerateMac("{userId}:{deviceId}")</code></td><td><code class="inline">DEVICE_KEY_KMS_*</code></td></tr>
  </tbody>
</table></div>
<p>If KMS fails, payment completion aborts — a payment is never completed unsigned. A <code class="inline">TRANSACTION_SIGNING_LOCAL_DEV</code> mode exists for local development only and refuses to boot unless <code class="inline">APP_ENV=local</code>.</p>
