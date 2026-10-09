<div class="docs-eyebrow">Security &amp; Identity</div>
<h1>KYC Verification</h1>

<p>Identity verification raises a user's daily limit. It has two parts: a <strong>BVN check</strong>, then a <strong>liveness (selfie) check</strong> run in a Youverify web view. The final verdict arrives asynchronously via the <a href="{{ route('docs.show', ['section' => 'integrations', 'page' => 'webhooks']) }}#youverify">Youverify webhook</a>.</p>

<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Tier</th><th>Daily limit (KYC)</th><th>How reached</th></tr></thead>
  <tbody>
    <tr><td>1</td><td>₦50,000</td><td>Default on registration</td></tr>
    <tr><td>2</td><td>₦200,000</td><td>BVN + liveness approved</td></tr>
    <tr><td>3</td><td>₦5,000,000</td><td>Granted by a higher-assurance Smile ID result (code 1012)</td></tr>
  </tbody>
</table></div>
<p><code class="inline">kyc_status</code> moves <code class="inline">none → pending → verified</code> or <code class="inline">failed</code> (with a <code class="inline">rejection_reason</code>); operators can set <code class="inline">suspended</code>. Read it from <a href="{{ route('docs.show', ['section' => 'account', 'page' => 'current-user']) }}">/user/me</a>.</p>

<h2 id="flow">Flow</h2>
<ol>
  <li><code class="inline">POST /kyc/check-enrollment</code> with the BVN. Valid BVN → stored encrypted.</li>
  <li><code class="inline">POST /kyc/youverify/liveness-session</code> → open the Youverify SDK in a web view with the returned tokens.</li>
  <li>When the SDK's <code class="inline">onSuccess</code> fires, call <code class="inline">POST /kyc/youverify/liveness-submitted</code> → status becomes <code class="inline">pending</code> (or resolves immediately if Youverify already has a result).</li>
  <li>Poll <code class="inline">/user/me</code> (or react to the push notification) until <code class="inline">kyc.status</code> is <code class="inline">verified</code> or <code class="inline">failed</code>.</li>
</ol>

<h2 id="check-enrollment">Check BVN / enrollment</h2>
<x-docs.endpoint method="POST" path="/kyc/check-enrollment" />
<p>Body: <code class="inline">bvn</code> — exactly 11 digits (inline validation, so errors are Laravel 422). The BVN is verified with Youverify; if not found the response is 422 <code class="inline">BVN could not be verified. Please check the number and try again.</code> The BVN is encrypted at rest and never returned.</p>
<x-docs.code lang="json">{
  "status": true,
  "message": "BVN verified. Please complete liveness check.",
  "code": 200,
  "data": {
    "is_liveness_taken": false,
    "kyc_status": "none",
    "kyc_tier": 1,
    "daily_limit": 50000,
    "has_bvn": true,
    "youverify_user_id": "user-1"
  }
}</x-docs.code>
<p><code class="inline">message</code> reflects state: <em>BVN is valid and enrollment is complete</em>, <em>Verification is in progress</em>, <em>a previous verification attempt failed. Please try again</em>, or <em>Please complete liveness check</em>.</p>
<x-docs.try-it method="POST" path="/kyc/check-enrollment" :fields="[['name' => 'bvn', 'in' => 'body', 'type' => 'password', 'required' => true, 'placeholder' => '11 digits']]" warning="Submits a real BVN to Youverify and stores it (encrypted) on your account." />

<h2 id="liveness-session">Start a liveness session</h2>
<x-docs.endpoint method="POST" path="/kyc/youverify/liveness-session" />
<x-docs.code lang="json">{
  "status": true,
  "message": "Liveness session created.",
  "code": 200,
  "data": {
    "auth_token": "yv-auth-token",
    "session_id": "yv-session-id",
    "public_merchant_key": "pk_...",
    "youverify_user_id": "user-1",
    "is_sandbox": false
  }
}</x-docs.code>
<p>Pass these straight into the Youverify JS SDK. <code class="inline">youverify_user_id</code> (<code class="inline">user-&lt;id&gt;</code>) must be sent as the SDK's <code class="inline">metadata.user_id</code> so the webhook can attribute the result. If Youverify is unreachable: 503 <code class="inline">Could not start verification right now. Please try again shortly.</code></p>
<x-docs.try-it method="POST" path="/kyc/youverify/liveness-session" />

<h2 id="liveness-submitted">Liveness submitted</h2>
<x-docs.endpoint method="POST" path="/kyc/youverify/liveness-submitted" />
<p>Body: <code class="inline">session_id</code>. Call from the SDK's success callback. The server looks up the session; a pass approves tier 2, a fail rejects with <code class="inline">Liveness check failed. Please ensure your face is clearly visible and try again.</code>, no answer yet marks the account <code class="inline">pending</code> ("under review"). Already-verified accounts get the current status back unchanged.</p>
<x-docs.code lang="json">{ "status": true, "message": "Marked as under review.", "code": 200, "data": { "kyc_status": "pending" } }</x-docs.code>
<x-docs.try-it method="POST" path="/kyc/youverify/liveness-submitted" :fields="[['name' => 'session_id', 'in' => 'body']]" warning="Moves your KYC status." />

<x-docs.callout type="note" title="Smile ID">
  <p>An earlier Smile ID based implementation (<code class="inline">checkEnrollment_SMILE_ID</code>) remains in the code but is not routed. Smile ID's webhook endpoint is still live — see <a href="{{ route('docs.show', ['section' => 'integrations', 'page' => 'webhooks']) }}#smile-id">Inbound Webhooks</a>.</p>
</x-docs.callout>
