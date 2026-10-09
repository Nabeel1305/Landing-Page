<div class="docs-eyebrow">Getting Started</div>
<h1>Introduction</h1>

<p>PakaPay is a bank-account-based payments product for Nigeria. Users link their existing bank accounts, then pay or get paid by scanning a QR code — there is no wallet balance to top up. This documentation covers the backend that powers the PakaPay mobile app: the REST API the app calls, the webhooks the backend receives, the offline (phone-call) payment rail, and the security model around them.</p>

<x-docs.callout type="note" title="Base URL">
  <p>All endpoints in these docs are relative to:</p>
  <p><code class="inline">{{ config('docs.api_base') }}</code></p>
  <p>For example, <code class="inline">POST /auth/login</code> is <code class="inline">POST {{ config('docs.api_base') }}/auth/login</code>. HTTPS is mandatory in production; plain-HTTP requests are redirected.</p>
</x-docs.callout>

<h2 id="who-this-is-for">Who this is for</h2>
<ul>
  <li><strong>Mobile and client developers</strong> — <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}">Authentication</a>, <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'sending']) }}">Payments</a>, <a href="{{ route('docs.show', ['section' => 'account', 'page' => 'bank-accounts']) }}">Bank Accounts</a> and <a href="{{ route('docs.show', ['section' => 'security', 'page' => 'kyc']) }}">KYC</a>.</li>
  <li><strong>Partners and integrators</strong> — <a href="{{ route('docs.show', ['section' => 'integrations', 'page' => 'webhooks']) }}">Inbound Webhooks</a> and the <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'overview']) }}">Offline Payments</a> rail.</li>
  <li><strong>Internal engineers and operators</strong> — the <em>Platform (Internal)</em> section: security model, fraud engine, admin dashboard and configuration.</li>
</ul>

<h2 id="how-it-fits-together">How it fits together</h2>
<ul>
  <li><strong>Accounts.</strong> A user registers with a phone number and a 4-digit PIN (an SMS OTP confirms the number), then links one or more Nigerian bank accounts. One linked account is the <em>active</em> (default) account.</li>
  <li><strong>Online payments.</strong> The receiver shows a signed QR code. The sender scans it (<code class="inline">decode-qr</code>), stages a payment (<code class="inline">initiate</code>) and confirms it with their PIN (<code class="inline">confirm</code>). The payment is recorded between the two users' linked bank accounts as a signed transaction; PakaPay does not hold a wallet balance. Both sender and receiver must have a linked PakaPay account.</li>
  <li><strong>Payment Points.</strong> Business accounts can create named sub-points ("Till 2", "Front desk") on a bank account, each with its own QR code and reporting, and share them with staff by an 8-digit code.</li>
  <li><strong>Offline payments.</strong> With no data connection, the app builds a 31-digit signed code and dials PakaPay's voice number. The server verifies it and settles the transfer; both parties get an SMS.</li>
  <li><strong>Trust and safety.</strong> KYC tiers set daily limits; a fraud engine applies velocity, PIN and OTP limits; every sensitive event goes to a tamper-evident audit log; an operator kill switch can halt the API.</li>
</ul>

<h2 id="request-basics">Request basics</h2>
<ul>
  <li>Send JSON bodies with <code class="inline">Content-Type: application/json</code> and <code class="inline">Accept: application/json</code>. Profile-photo upload is the one <code class="inline">multipart/form-data</code> endpoint.</li>
  <li>Authenticated calls carry <code class="inline">Authorization: Bearer &lt;token&gt;</code>.</li>
  <li>Mobile clients should also send <code class="inline">X-Device-Id</code> (and, ideally, <code class="inline">X-App-Version</code>) on every call — they feed device binding and fraud checks. See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}#device-binding">Device binding</a>.</li>
  <li>Phone numbers are accepted in international form (<code class="inline">+2348012345678</code>); the backend normalizes them for SMS delivery.</li>
</ul>

<x-docs.callout type="warn" title="Read the response format first">
  <p>PakaPay does not use HTTP status codes the way most REST APIs do: many failures — including every form-validation error — come back as <strong>HTTP 200</strong> with <code class="inline">"status": false</code>. Read <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'response-format']) }}">Response Format</a> before writing a client.</p>
</x-docs.callout>

<h2 id="try-it">Try it</h2>
<p>Many endpoints have a <strong>Try it</strong> panel that sends a real request to <code class="inline">{{ config('docs.api_base') }}</code>. Log in, paste the <code class="inline">data.token</code> into <em>Set Bearer Token</em> (top right), and the panels will use it. Panels that change data or move money are labelled and require explicit confirmation — they act on a real account, not a sandbox.</p>
