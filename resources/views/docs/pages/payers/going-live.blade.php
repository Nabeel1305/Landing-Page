<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>Sandbox &amp; Going Live</h1>

<h2 id="sandbox">Sandbox</h2>
<p>Every organisation starts in the <strong>sandbox</strong> (the portal header shows a yellow <em>Sandbox</em> badge). In the sandbox:</p>
<ul>
  <li>the settlement adapter is the simulator — nothing real moves;</li>
  <li>PakaPay can make holds or captures fail on request so you can test every path;</li>
  <li>everything else — codes, calls, webhooks, the portal — behaves exactly as in production.</li>
</ul>

<h2 id="checklist">Before going live</h2>
<ol>
  <li><strong>Settlement integration.</strong> Your system exposes what the adapter needs (<a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'settlement']) }}">hold, capture, release, status</a>), and PakaPay builds and reviews the adapter for it. Credentials for it live in the platform's secret manager, with TLS (or mutual TLS), timeouts, and no logging of account data.</li>
  <li><strong>Idempotent capture and honest status.</strong> Verified in the sandbox against failure injection.</li>
  <li><strong>Webhook receiver hardened:</strong> signature verified, de-duplicated by event id, alerting on failures.</li>
  <li><strong>Your own checks in place:</strong> KYC, limits and fraud rules enforced in your <code class="inline">hold</code> decision.</li>
  <li><strong>Voice number provisioned</strong> with the telephony provider and tested with a real call (own number, or a short code on the shared pool).</li>
  <li><strong>Operational readiness:</strong> someone watches the portal dashboard for pending transactions and failed deliveries, and knows what <code class="inline">transaction.needs_review</code> means.</li>
  <li><strong>Team and security:</strong> at least two owners with two-factor on; keys held by your servers only.</li>
</ol>

<h2 id="switch">The switch</h2>
<p>Going live is done <strong>by PakaPay</strong> — you cannot do it yourself. PakaPay moves the account to <code class="inline">live</code> and assigns your real adapter in the same step. A live account can't use the sandbox adapter, and the engine independently refuses to run a live account on the simulator.</p>
<x-docs.callout type="warn" title="Today">
  <p>No real settlement adapter exists yet, so accounts can't be made live. Once your system's API is available, the adapter is built against it, tested in your sandbox, then switched on.</p>
</x-docs.callout>

<h2 id="differences">What changes</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th></th><th>Sandbox</th><th>Live</th></tr></thead>
  <tbody>
    <tr><td>Money</td><td>Simulated</td><td>Real holds and transfers in your system</td></tr>
    <tr><td>API keys</td><td colspan="2">Keys belong to the account, not to an environment: switching the account live changes what your existing keys do. Create fresh keys for production at go-live and revoke the sandbox ones.</td></tr>
    <tr><td>Hold/capture failures</td><td>Only when PakaPay injects them</td><td>Whatever your system really answers</td></tr>
    <tr><td>Endpoints, payloads, webhooks, states</td><td colspan="2">Identical</td></tr>
  </tbody>
</table></div>
<p>An account has exactly one environment at a time; to run a test alongside production, ask for a second (sandbox) organisation.</p>
