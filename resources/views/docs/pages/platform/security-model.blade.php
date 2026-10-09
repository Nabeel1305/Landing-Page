<div class="docs-eyebrow">Platform (Internal)</div>
<h1>Security Model</h1>

<p>An overview of the controls around the API, written for engineers and reviewers. Operator-only surfaces are covered in <a href="{{ route('docs.show', ['section' => 'platform', 'page' => 'admin-dashboard']) }}">Admin Dashboard</a>.</p>

<h2 id="identity">Identity & sessions</h2>
<ul>
  <li><strong>Credentials:</strong> phone + 4-digit PIN (bcrypt). PIN brute force is rate limited per IP, per phone (5 fails → 15-minute lockout) and per user (<code class="inline">PIN_LOCKED</code>).</li>
  <li><strong>No account enumeration:</strong> registration and forgot-PIN return identical bodies whether or not the number exists; login returns one message for "no such user" and "wrong PIN".</li>
  <li><strong>Tokens:</strong> Sanctum, 30-day absolute expiry, 30-minute inactivity timeout enforced by <code class="inline">EnsureSessionActive</code>. One active session per user: every login, device verification, PIN reset and PIN change deletes all prior tokens.</li>
  <li><strong>Device binding:</strong> the account binds to its first <code class="inline">X-Device-Id</code>. A different device must pass an SMS OTP, which re-binds and signs out the old one. Per-request fingerprint (<code class="inline">sha256(X-Device-Id | User-Agent | X-App-Version)</code>) feeds binding and concurrent-session checks.</li>
</ul>

<h2 id="middleware">Middleware stack on authenticated routes</h2>
<x-docs.code lang="text">auth:sanctum
  → EnsureSessionActive   401 SESSION_EXPIRED after 30 min idle (revokes token)
  → CheckKillSwitch       503 SERVICE_SUSPENDED while the global switch is on (cached 5s)
  → CheckForFraudLock     403 ACCOUNT_LOCKED / DEVICE_SUSPENDED; device-binding and concurrent-session checks
  → throttle:api          120/min per user

CheckForFraudLock is omitted on /kyc/*, /device/token and /device/offline-voice-number.</x-docs.code>

<h2 id="data-protection">Data protection</h2>
<ul>
  <li><strong>BVN</strong> is stored encrypted and never serialized; only <code class="inline">has_bvn</code> is exposed.</li>
  <li><strong>Selfie retention:</strong> no selfie URL is kept — the <code class="inline">kyc_selfie_url</code> column was dropped (migration <code class="inline">2026_07_22_090100</code>).</li>
  <li><strong>Log redaction:</strong> <code class="inline">LogRedactor::scrub()</code> is applied to webhook payloads, audit metadata and unhandled-exception logs. Uncaught errors on <code class="inline">/api/*</code> return a generic 500 and log the detail only.</li>
  <li><strong>Transport:</strong> production forces HTTPS (301) and sends HSTS (1 year, includeSubDomains, preload). Proxy headers are trusted from <code class="inline">*</code> because the app sits behind Cloudflare.</li>
  <li><strong>QR integrity:</strong> account QR codes carry an HMAC signature; altered QRs are rejected at decode.</li>
</ul>

<h2 id="transaction-signing">Transaction signing</h2>
<p>Every completed transaction is signed by AWS KMS before it is marked <code class="inline">completed</code>. The signed string is:</p>
<x-docs.code lang="text">reference|user_id|bank_account_id|amount(2dp)|recipient_account_no|recipient_bank</x-docs.code>
<p>Stored with the row: <code class="inline">signature</code> (base64), <code class="inline">signature_alg</code>, <code class="inline">signing_key_id</code>, <code class="inline">signed_at</code>. Because the key id is recorded per transaction, keys can be rotated without invalidating history.</p>

<h2 id="audit-log">Immutable audit log</h2>
<p><code class="inline">audit_logs</code> is a hash chain: each row stores <code class="inline">prev_hash</code> and <code class="inline">hash = sha256(prev_hash | event_type | actor_type | actor_id | subject_type | subject_id | reference | description | metadata_json)</code>. Writes happen inside a locked DB transaction; the model throws on <code class="inline">update()</code>/<code class="inline">delete()</code>. <code class="inline">AuditLogService::verifyChain()</code> walks the table and returns the ids of any row whose hash or link doesn't match.</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Event type</th><th>Written when</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">transaction.completed</code></td><td>Online confirm, Quick Pay, or offline payment completes</td></tr>
    <tr><td><code class="inline">device_key.issued</code></td><td>Offline device key issued</td></tr>
    <tr><td><code class="inline">admin.pii_viewed</code> / <code class="inline">admin.pii_exported</code></td><td>Operator opens or exports user-identifying data (users, transactions, reports, accounts)</td></tr>
    <tr><td><code class="inline">admin.user_locked</code> / <code class="inline">admin.user_unlocked</code></td><td>Account suspended / unlocked</td></tr>
    <tr><td><code class="inline">admin.device_suspended</code> / <code class="inline">admin.device_resumed</code></td><td>Per-device stop-switch</td></tr>
    <tr><td><code class="inline">admin.kill_switch_activated</code> / <code class="inline">…_deactivated</code></td><td>Global stop-switch</td></tr>
    <tr><td><code class="inline">admin.transaction_reversed</code> / <code class="inline">…_cancelled</code></td><td>Operator reverses or cancels a transaction</td></tr>
    <tr><td><code class="inline">admin.bank_account_added</code> / <code class="inline">…_removed</code> / <code class="inline">…_activated</code></td><td>Operator edits a user's bank accounts</td></tr>
  </tbody>
</table></div>
<x-docs.callout type="warn" title="Defence in depth, not a substitute">
  <p>The model-level immutability guard doesn't stop someone with raw database access. Revoke <code class="inline">UPDATE</code> and <code class="inline">DELETE</code> on <code class="inline">audit_logs</code> for the application's DB user.</p>
</x-docs.callout>

<h2 id="stop-switches">Stop-switches</h2>
<ul>
  <li><strong>Lock account</strong> — <code class="inline">is_locked</code> + all tokens revoked.</li>
  <li><strong>Suspend device</strong> — <code class="inline">device_suspended_at</code>; lighter than a lock. Requests get <code class="inline">DEVICE_SUSPENDED</code>.</li>
  <li><strong>Kill switch</strong> — single <code class="inline">system_kill_switches</code> row, cached 5 seconds, blocks every authenticated API route with 503. A red banner shows on every admin page while it is on.</li>
</ul>

<h2 id="open-items">Known open items (from internal reviews)</h2>
<ul>
  <li>mTLS + IP allow-listing to the bank is an infrastructure task, not implemented in code (flagged in <code class="inline">ForceHttps</code>).</li>
  <li>Hosting region still defaults to <code class="inline">us-east-1</code>; moving to an African/Nigerian region is a business decision.</li>
  <li>Voice-callback token travels in the URL (see <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'voice-webhook']) }}">Voice Webhook</a>).</li>
  <li>The security notes from 22 July 2026 state the changes were written without a PHP runtime and not exercised; run migrations and the test suite before relying on them.</li>
</ul>
