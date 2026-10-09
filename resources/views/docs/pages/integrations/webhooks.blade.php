<div class="docs-eyebrow">Partners &amp; Webhooks</div>
<h1>Inbound Webhooks</h1>

<p>PakaPay <em>receives</em> webhooks from its identity and telephony partners; it does not currently send webhooks to third parties. All three endpoints are public (no bearer token, no CSRF) and rely on signatures or a shared secret instead.</p>

<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Partner</th><th>Endpoint</th><th>Authentication</th><th>Throttle</th></tr></thead>
  <tbody>
    <tr><td>Youverify</td><td><code class="inline">POST /webhook/youverify</code></td><td>HMAC-SHA256 in <code class="inline">x-youverify-signature</code></td><td>60/min per IP</td></tr>
    <tr><td>Smile ID</td><td><code class="inline">POST /webhook/smile-id</code></td><td>Signature + timestamp in the JSON body</td><td>60/min per IP</td></tr>
    <tr><td>Africa's Talking</td><td><code class="inline">POST /webhook/africastalking/voice[/events]</code></td><td>Shared <code class="inline">token</code> query param — see <a href="{{ route('docs.show', ['section' => 'offline', 'page' => 'voice-webhook']) }}">Voice Webhook</a></td><td>none in nunu</td></tr>
  </tbody>
</table></div>

<h2 id="youverify">Youverify (KYC liveness)</h2>
<x-docs.endpoint method="POST" path="/webhook/youverify" :auth="false" />
<p>Sent when a liveness/verification job finishes. The payload must be signed: PakaPay computes <code class="inline">HMAC-SHA256(rawBody, YOUVERIFY_WEBHOOK_SECRET)</code> and accepts either the hex digest or the base64 digest in <code class="inline">x-youverify-signature</code> (constant-time comparison).</p>
<x-docs.code lang="json">{
  "event": "liveness.completed",
  "data": {
    "type": "liveness",
    "status": "completed",
    "selfieValidation": true,
    "sessionId": "yv-session-id",
    "metadata": { "user_id": "user-1" }
  }
}</x-docs.code>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Condition</th><th>Result</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">selfieValidation === true</code></td><td>KYC approved, tier 2</td></tr>
    <tr><td><code class="inline">status</code> is <code class="inline">failed</code> / <code class="inline">not_found</code></td><td>Rejected: "Verification failed. Please ensure your details are correct and try again."</td></tr>
    <tr><td><code class="inline">selfieValidation === false</code></td><td>Rejected: "Liveness check failed. Please ensure your face is clearly visible and try again."</td></tr>
    <tr><td>Anything else</td><td><code class="inline">kyc_status = pending</code></td></tr>
  </tbody>
</table></div>
<p>Responses: <code class="inline">200 {"status":"received"}</code>; <code class="inline">200 {"status":"ignored"}</code> when there is no <code class="inline">metadata.user_id</code> (e.g. a plain BVN-check event); <code class="inline">400</code> missing signature; <code class="inline">401</code> bad signature; <code class="inline">404</code> unknown user. The user is identified by <code class="inline">metadata.user_id</code> = <code class="inline">user-&lt;id&gt;</code>, which the app must pass to the SDK (it is returned by the liveness-session endpoint).</p>

<h2 id="smile-id">Smile ID</h2>
<x-docs.endpoint method="POST" path="/webhook/smile-id" :auth="false" />
<p>Body includes <code class="inline">signature</code> and <code class="inline">timestamp</code> (verified against the Smile ID partner key), <code class="inline">ResultCode</code>, <code class="inline">ResultText</code>, <code class="inline">SmileJobID</code> and <code class="inline">PartnerParams.user_id</code> (<code class="inline">user-&lt;id&gt;</code>).</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th><code class="inline">ResultCode</code></th><th>Action</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">0810</code>, <code class="inline">1020</code></td><td>Approve, tier 2</td></tr>
    <tr><td><code class="inline">1012</code></td><td>Approve, tier 3</td></tr>
    <tr><td><code class="inline">1220</code></td><td>Approve tier 2 (unless already verified)</td></tr>
    <tr><td><code class="inline">1021</code></td><td>Mark pending</td></tr>
    <tr><td><code class="inline">1022</code></td><td>Reject: details do not match government records</td></tr>
    <tr><td><code class="inline">2207</code></td><td>Reject: liveness failed</td></tr>
    <tr><td>other</td><td>Reject with "Verification could not be completed (&lt;ResultText&gt;). Please contact support." and log a warning</td></tr>
  </tbody>
</table></div>
<p>Responses: <code class="inline">200 {"status":"received"}</code>, <code class="inline">400</code> (missing signature/timestamp, or user unresolvable), <code class="inline">401</code> bad signature, <code class="inline">404</code> unknown user.</p>

<h2 id="handling">Operational notes</h2>
<ul>
  <li>Return 2xx quickly — handlers are synchronous and only update the user's KYC fields.</li>
  <li>Handlers are idempotent in effect (they set state), so provider retries are safe.</li>
  <li>Request bodies are passed through <code class="inline">LogRedactor::scrub()</code> before logging; do not add logging that bypasses it.</li>
  <li>Test signatures locally by computing the HMAC with the secret from <em>Admin → Settings → API keys</em> (or <code class="inline">.env</code>).</li>
</ul>
