<div class="docs-eyebrow">Developer Portal</div>
<h1>Keys, Webhooks &amp; Logs</h1>

<p>Your team gets a web portal at <code class="inline">/portal</code> on your platform address. For developers it is the place to manage the pieces of your integration that the API deliberately doesn't expose. You sign in with an invitation link from PakaPay (or an owner on your team), a password of at least 12 characters and an authenticator-app code.</p>

<h2 id="keys">API keys</h2>
<ul>
  <li>See every key with its name, visible prefix (<code class="inline">opk_1a2b3c4d_…</code>), creation date and <strong>last used</strong> time — handy for spotting keys nothing uses any more.</li>
  <li><strong>Create</strong> a key by naming what will use it. The full key is shown <strong>once</strong>, with a copy button.</li>
  <li><strong>Revoke</strong> a key after a confirmation. Anything using it gets <code class="inline">401</code> immediately.</li>
  <li>Use one key per system so you can revoke one without stopping the rest.</li>
</ul>

<h2 id="webhooks">Webhook endpoints</h2>
<p>The API can create an endpoint; the portal does the rest:</p>
<ul>
  <li><strong>Edit</strong> the URL or the subscribed events.</li>
  <li><strong>Pause / enable</strong> an endpoint — paused endpoints receive no new events.</li>
  <li><strong>Rotate the secret</strong> — the new one replaces the old immediately; update your server first.</li>
  <li><strong>Send a test event</strong> — a signed <code class="inline">webhook.test</code> goes to that endpoint and its delivery record opens so you can see your response.</li>
  <li><strong>Delete</strong> — the endpoint stops receiving events.</li>
</ul>

<h2 id="deliveries">Delivery log</h2>
<p>Every event we tried to send, newest first, filterable by status, event type or endpoint. Open one to see its status, attempts, the last HTTP status and error, the next attempt time and the <strong>exact JSON payload</strong> sent. <strong>Retry now</strong> re-queues one that isn't delivered yet. Finished deliveries are kept for 30 days. See <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'delivery']) }}">Delivery &amp; Retries</a>.</p>

<h2 id="data">Transactions and codes</h2>
<p>Developers don't see payment amounts in the portal. Roles with money access (owner, viewer) can browse and export transactions and codes; ask an owner on your team if you need a figure. Payer phone numbers are always masked.</p>
