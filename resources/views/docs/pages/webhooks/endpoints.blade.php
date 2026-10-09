<div class="docs-eyebrow">Webhooks</div>
<h1>Webhook Endpoints</h1>

<p>Webhooks are how the platform tells <em>your server</em> what happened to a payment — the payer's phone call can't tell them, so your webhook is the source of truth for notifying the customer. You register one or more HTTPS addresses; each event is delivered to every endpoint that subscribes to it.</p>

<h2 id="register">Register an endpoint</h2>
<x-docs.endpoint method="POST" path="/webhook-endpoints" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">url</code></td><td>Required. A valid URL, max 2,048 characters, <strong>https</strong>, resolving to a <strong>public</strong> address.</td></tr>
    <tr><td><code class="inline">events</code></td><td>Optional array of at least one of <code class="inline">code.redeemed</code>, <code class="inline">transaction.settled</code>, <code class="inline">transaction.failed</code>, <code class="inline">code.expired</code>. Omit to receive <strong>every</strong> event, including any added later.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/webhook-endpoints \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "url": "https://you.example/hooks/offline", "events": ["transaction.settled", "transaction.failed"] }'</x-docs.code>
<x-docs.code lang="json">// 201 Created
{
  "id": 3,
  "url": "https://you.example/hooks/offline",
  "events": ["transaction.settled", "transaction.failed"],
  "secret": "whsec_8a41c0e7d2b94f6a1c35e07b9d2f48a6c1e3b5d70f92a4c6"
}</x-docs.code>
<x-docs.callout type="warn" title="The secret is shown once">
  <p>It is stored encrypted and cannot be retrieved. If you lose it, rotate it in the <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'developers']) }}">portal</a>, then update your server — the old secret stops working immediately.</p>
</x-docs.callout>
<x-docs.try-it method="POST" path="/webhook-endpoints" :fields="[
    ['name' => 'url', 'in' => 'body', 'required' => true, 'placeholder' => 'https://you.example/hooks/offline'],
]" warning="Creates a real endpoint on your account and shows its secret once." />

<h2 id="url-rules">What the URL may be</h2>
<ul>
  <li><strong>https only.</strong> Plain http is refused.</li>
  <li><strong>Public addresses only.</strong> The host must resolve, and every address it resolves to must be public — private ranges (10.x, 192.168.x, 172.16–31.x), loopback and reserved addresses are rejected. This stops a webhook being pointed at the platform's own network.</li>
  <li>The check runs again <strong>before every delivery</strong>, and the platform connects to the address it just checked, so DNS changes between check and call can't redirect it.</li>
  <li><strong>Redirects are not followed.</strong> Answer directly at the registered URL; a <code class="inline">301/302</code> counts as a failed attempt.</li>
</ul>
<p>Validation failures return <code class="inline">422</code> with <code class="inline">{ message, errors: { url: [...] } }</code>, for example "Webhook URLs must use https."</p>

<h2 id="managing">Managing endpoints</h2>
<p>The API creates endpoints; it does not list, edit or delete them. Everything else is done in the <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'developers']) }}">portal</a> (owner or developer role):</p>
<ul>
  <li>change the address or subscribed events,</li>
  <li>pause and re-enable an endpoint (paused endpoints receive no new events),</li>
  <li>rotate the signing secret,</li>
  <li>delete an endpoint (past deliveries stay in the log),</li>
  <li>send a <strong>test event</strong> (<code class="inline">webhook.test</code>) to check your receiver and signature code.</li>
</ul>
<p>You can have several endpoints — for example one for production and one for a staging system — each with its own secret.</p>
