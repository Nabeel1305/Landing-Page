<div class="docs-eyebrow">Getting Started</div>
<h1>Errors &amp; Rate Limits</h1>

<h2 id="shape">Error shape</h2>
<p>Errors use real HTTP status codes and a JSON body:</p>
<x-docs.code lang="json">{
  "error": {
    "code": "settlement_rejected",
    "message": "Insufficient funds."
  }
}</x-docs.code>
<p>Branch on <code class="inline">error.code</code>, not on the message. <strong>Validation errors</strong> use Laravel's standard shape instead:</p>
<x-docs.code lang="json">{
  "message": "The amount minor field must be at least 1.",
  "errors": { "amount_minor": ["The amount minor field must be at least 1."] }
}</x-docs.code>

<h2 id="codes">Status codes</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Status</th><th><code class="inline">error.code</code></th><th>Meaning / what to do</th></tr></thead>
  <tbody>
    <tr><td>200 / 201</td><td>—</td><td>Success (201 when something was created, including an upserted subscriber or merchant that didn't exist before)</td></tr>
    <tr><td>401</td><td><code class="inline">unauthenticated</code></td><td>Missing, wrong or revoked key</td></tr>
    <tr><td>403</td><td><code class="inline">tenant_suspended</code></td><td>Your organisation is suspended</td></tr>
    <tr><td>404</td><td><code class="inline">not_found</code></td><td>Unknown id or reference — or it belongs to another organisation (deliberately indistinguishable)</td></tr>
    <tr><td>409</td><td><code class="inline">not_cancellable</code></td><td>Only a code in state <code class="inline">issued</code> can be cancelled</td></tr>
    <tr><td>409</td><td><code class="inline">request_in_progress</code></td><td>The first copy of this idempotent request is still running — retry shortly</td></tr>
    <tr><td>422</td><td><code class="inline">idempotency_key_required</code>, <code class="inline">idempotency_key_reused</code></td><td>See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'idempotency']) }}">Idempotency</a></td></tr>
    <tr><td>422</td><td><code class="inline">settlement_rejected</code></td><td>Your system refused the hold (the message is its reason), or the amount is above your account's limit</td></tr>
    <tr><td>422</td><td>(validation)</td><td><code class="inline">{ message, errors }</code> listing the bad fields</td></tr>
    <tr><td>429</td><td><code class="inline">too_many_attempts</code> / (throttle)</td><td>Slow down; honour the <code class="inline">Retry-After</code> header</td></tr>
    <tr><td>5xx</td><td>—</td><td>Our side. Safe to retry with the same <code class="inline">Idempotency-Key</code></td></tr>
  </tbody>
</table></div>

<h2 id="rate-limits">Rate limits</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Limit</th><th>Default</th><th>Notes</th></tr></thead>
  <tbody>
    <tr><td>API requests</td><td>300 / minute <strong>per organisation</strong></td><td>One busy tenant cannot starve another. Configurable by the operator (<code class="inline">PLATFORM_API_PER_MINUTE</code>).</td></tr>
    <tr><td>Invalid keys</td><td>30 / minute per source address</td><td>Protects the key lookup; legitimate traffic never hits it.</td></tr>
  </tbody>
</table></div>
<p>Over the limit you get <code class="inline">429 Too Many Requests</code> with <code class="inline">Retry-After</code> (seconds). Back off with that value rather than a fixed delay.</p>

<h2 id="payer-side">What the payer never sees</h2>
<p>Failures during the <em>phone call</em> are never explained to the caller — wrong code, expired, already used, wrong caller number and rate-limited calls all end with the same spoken thank-you, so the voice line can't be used to guess codes. You find out what happened from your webhooks and the portal. See <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'voice']) }}">The Payer's Call</a>.</p>
