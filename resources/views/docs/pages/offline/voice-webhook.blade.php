<div class="docs-eyebrow">Offline Payments</div>
<h1>Voice Webhook</h1>

<p>These endpoints are called by <a href="https://africastalking.com" target="_blank">Africa's Talking</a> (AT), not by app clients. They are public routes (no bearer token) living outside the authenticated groups.</p>

<h2 id="setup">Africa's Talking setup</h2>
<p>In the AT dashboard, on the voice number (Call → your number → Callback Config), set <strong>two</strong> URLs that point at <em>your server</em>:</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>URL</th><th>Purpose</th></tr></thead>
  <tbody>
    <tr><td>Callback URL</td><td><code class="inline">{{ config('docs.api_base') }}/webhook/africastalking/voice?token=&lt;AT_WEBHOOK_SECRET&gt;</code></td><td>Interactive — AT waits for XML back</td></tr>
    <tr><td>Events URL</td><td><code class="inline">{{ config('docs.api_base') }}/webhook/africastalking/voice/events?token=&lt;AT_WEBHOOK_SECRET&gt;</code></td><td>Fire-and-forget call lifecycle events</td></tr>
  </tbody>
</table></div>
<p>Settings: <code class="inline">AT_USERNAME</code>, <code class="inline">AT_API_KEY</code>, <code class="inline">AT_VOICE_NUMBER</code> (full E.164), <code class="inline">AT_WEBHOOK_SECRET</code>, <code class="inline">AT_BASE_URL</code>. The first three plus the secret can be overridden at runtime from the admin <em>Service API Keys</em> screen.</p>
<x-docs.callout type="danger" title="Authentication of the callbacks">
  <p>AT does not sign voice callbacks, so the only guard is a shared <code class="inline">token</code> query parameter. The route definition in this build (<code class="inline">nunu</code>) does <strong>not</strong> attach the token-verification middleware; the newer <code class="inline">dashboard-and-api</code> build adds <code class="inline">VerifyAfricasTalkingWebhook</code> plus a 600/min throttle. Make sure the deployed build enforces the token, and scrub query strings from access logs (the token and, on the main callback, the dialed digits travel in the request).</p>
</x-docs.callout>

<h2 id="callback">POST /webhook/africastalking/voice</h2>
<x-docs.endpoint method="POST" path="/webhook/africastalking/voice" :auth="false" note="Called twice per payment call" />
<p>AT posts form-encoded fields; the ones PakaPay uses are <code class="inline">callerNumber</code>, <code class="inline">sessionId</code> and <code class="inline">dtmfDigits</code>. The response is always XML (<code class="inline">Content-Type: application/xml</code>).</p>
<ol>
  <li><strong>Call arrives</strong> — no <code class="inline">dtmfDigits</code> yet. PakaPay answers with a <code class="inline">GetDigits</code> instruction:
<x-docs.code lang="xml">
<Response>
  <GetDigits timeout="30" finishOnKey="#" callbackUrl="https://app.pakapay.ng/api/webhook/africastalking/voice">
    <Say voice="woman">Processing your PakaPay payment.</Say>
  </GetDigits>
</Response></x-docs.code>
  </li>
  <li><strong>Digits collected</strong> (caller finished with <code class="inline">#</code>) — AT posts again with <code class="inline">dtmfDigits</code>. PakaPay runs the <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'overview']) }}#pipeline">verification pipeline</a> and replies, regardless of outcome:
<x-docs.code lang="xml">
<Response>
  <Say voice="woman">Thank you for using PakaPay. You will receive an SMS shortly.</Say>
</Response></x-docs.code>
  </li>
</ol>
<x-docs.callout type="note" title="Hard-coded callback host">
  <p>The <code class="inline">callbackUrl</code> in the <code class="inline">GetDigits</code> XML is a literal string in the controller (<code class="inline">https://app.pakapay.ng/...</code>) and carries no token. Update it if the API moves to another host, and note the follow-up post must still pass whatever authentication the route enforces.</p>
</x-docs.callout>

<h2 id="events">POST /webhook/africastalking/voice/events</h2>
<x-docs.endpoint method="POST" path="/webhook/africastalking/voice/events" :auth="false" />
<p>Receives call lifecycle notifications; PakaPay logs them (<code class="inline">Africa's Talking voice call event</code>) and returns an empty <code class="inline">200</code>.</p>

<h2 id="testing">Testing</h2>
<x-docs.code lang="bash"># Simulate AT delivering digits (replace the 31-digit code with a real, signed one)
curl -X POST "{{ config('docs.api_base') }}/webhook/africastalking/voice?token=$AT_WEBHOOK_SECRET" \
  -d "callerNumber=+2348012345678" \
  -d "sessionId=ATVId_test_1" \
  -d "dtmfDigits=1100042002500482019377710294358"</x-docs.code>
<p>The response will be the thank-you XML even for a bad code. Check the audit log, the <code class="inline">offline_payment_attempts</code> table and the fraud log to see what actually happened.</p>
