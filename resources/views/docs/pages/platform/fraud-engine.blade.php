<div class="docs-eyebrow">Platform (Internal)</div>
<h1>Fraud Engine</h1>

<p><code class="inline">App\Util\FraudService</code> is the business-rule layer behind the HTTP throttles. It keeps counters in cache, records noteworthy events in the <code class="inline">fraud_events</code> table and throws a <code class="inline">FraudException</code> carrying an <code class="inline">eventType</code> and a <code class="inline">severity</code>. Controllers convert those into API errors where <code class="inline">code</code> = event type.</p>

<h2 id="severity">Severity → HTTP</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Severity</th><th>HTTP</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">INFO</code></td><td>—</td><td>Logged only (never blocks)</td></tr>
    <tr><td><code class="inline">WARN</code></td><td>422</td><td>Blocks this request, user can correct and retry</td></tr>
    <tr><td><code class="inline">SOFT_BLOCK</code></td><td>429</td><td>Blocks until a window passes (hour / day)</td></tr>
    <tr><td><code class="inline">HARD_BLOCK</code></td><td>403</td><td>Security-grade event; account may be locked</td></tr>
  </tbody>
</table></div>

<h2 id="events">Event types</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Event type</th><th>Severity</th><th>Trigger</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">pin_brute_force</code></td><td>WARN → HARD_BLOCK</td><td>Wrong PIN. At 5 within 15 min: PIN entry locks for 15 min <strong>and the account's <code class="inline">is_locked</code> flag is set</strong> — <code class="inline">ACCOUNT_LOCKED</code> until an operator unlocks it.</td></tr>
    <tr><td><code class="inline">otp_brute_force</code></td><td>WARN</td><td>3 wrong OTPs for a phone within 10 min; OTP invalidated</td></tr>
    <tr><td><code class="inline">otp_resend_abuse</code></td><td>WARN</td><td>More than 3 OTP sends per phone per hour</td></tr>
    <tr><td><code class="inline">velocity_single_limit</code></td><td>WARN</td><td>Amount &gt; ₦50,000</td></tr>
    <tr><td><code class="inline">new_account_single_limit</code></td><td>WARN</td><td>Source bank account linked &lt; 7 days ago and amount &gt; ₦10,000</td></tr>
    <tr><td><code class="inline">new_account_daily_limit</code></td><td>WARN</td><td>Same young account sending &gt; ₦20,000 in a day</td></tr>
    <tr><td><code class="inline">velocity_hourly_count</code></td><td>SOFT_BLOCK</td><td>More than 10 sends in the hour</td></tr>
    <tr><td><code class="inline">velocity_daily_volume</code></td><td>SOFT_BLOCK</td><td>Daily total &gt; ₦200,000</td></tr>
    <tr><td><code class="inline">velocity_rapid_fire</code></td><td>WARN (flag)</td><td>3 payments in 90 seconds → next <code class="inline">/pay/initiate</code> returns 202 <code class="inline">require_pin</code></td></tr>
    <tr><td><code class="inline">velocity_repeat_recipient</code></td><td>INFO</td><td>5+ payments to the same recipient in an hour</td></tr>
    <tr><td><code class="inline">amount_anomaly_5x</code>, <code class="inline">amount_anomaly_round_new_account</code></td><td>INFO</td><td>Amount ≥ 5× the user's 30-day average / round-number on a young account</td></tr>
    <tr><td><code class="inline">qr_missing_field</code>, <code class="inline">qr_invalid_signature</code></td><td>WARN</td><td>Malformed or forged QR</td></tr>
    <tr><td><code class="inline">qr_decode_rate_limit</code></td><td>SOFT_BLOCK</td><td>&gt; 20 QR decodes per 10 min per user</td></tr>
    <tr><td><code class="inline">qr_harvest_detected</code></td><td>WARN (flag)</td><td>One QR scanned by &gt; 10 distinct users per hour (gate QRs exempt)</td></tr>
    <tr><td><code class="inline">device_mismatch</code>, <code class="inline">concurrent_sessions</code></td><td>WARN (flag)</td><td>Request fingerprint differs from stored; ≥ 2 fingerprints active in an hour</td></tr>
    <tr><td><code class="inline">offline_malformed_code</code>, <code class="inline">offline_unknown_caller</code>, <code class="inline">offline_no_bound_device</code></td><td>WARN</td><td>Identity checks failing on the offline rail</td></tr>
    <tr><td><code class="inline">offline_disabled</code></td><td>INFO</td><td>Call from a user who hasn't enabled offline payments</td></tr>
    <tr><td><code class="inline">offline_bad_signature</code></td><td>WARN → HARD_BLOCK</td><td>5 bad signatures in 15 min <strong>lock the account</strong></td></tr>
    <tr><td><code class="inline">offline_replay</code>, <code class="inline">offline_locked_account</code></td><td>HARD_BLOCK</td><td>Counter ≤ last accepted; locked account tried to pay</td></tr>
    <tr><td><code class="inline">offline_velocity_single_limit</code></td><td>WARN</td><td>Offline amount above the single cap (₦20,000 default)</td></tr>
    <tr><td><code class="inline">offline_velocity_daily_limit</code></td><td>SOFT_BLOCK</td><td>Offline daily total above ₦50,000 default</td></tr>
    <tr><td><code class="inline">offline_payment_failed</code></td><td>WARN</td><td>Unknown account/recipient, self-payment, or an unexpected error</td></tr>
  </tbody>
</table></div>

<h2 id="storage">What is stored</h2>
<p>Each event row holds <code class="inline">user_id</code>, <code class="inline">event_type</code>, <code class="inline">severity</code>, a redacted <code class="inline">payload</code> JSON, <code class="inline">ip_address</code> and <code class="inline">device_fingerprint</code>, plus review fields used by operators (status, resolver, escalation). Counters live in cache (<code class="inline">fraud_*</code> keys), so a cache flush resets all limits — use a shared persistent cache (Redis) in production.</p>

<h2 id="tuning">Tuning</h2>
<p>Most thresholds are class constants in <code class="inline">FraudService</code> (PIN, OTP, velocity, new-account, QR). Only the offline caps are environment-driven (<code class="inline">OFFLINE_MAX_SINGLE_TXN</code>, <code class="inline">OFFLINE_MAX_DAILY_VOLUME</code>). Changing a constant needs a deploy.</p>
