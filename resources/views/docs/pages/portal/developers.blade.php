<div class="docs-eyebrow">Tenant Portal</div>
<h1>Developer Tools</h1>
<p><em>View: all roles. Change: owner and developer.</em></p>

<h2 id="keys">API keys</h2>
<p>Lists every key with its name, the visible prefix (<code class="inline">opk_1a2b3c4d_…</code>), when it was created and last used, and whether it is active or revoked.</p>
<ul>
  <li><strong>Create a key</strong> by naming what will use it. The full key is displayed <strong>once</strong>, on the page you land on, with a copy button. It cannot be shown again.</li>
  <li><strong>Revoke</strong> a key with a confirmation. Anything using it is refused immediately (<code class="inline">401</code>).</li>
  <li>"Last used" helps you find keys that nothing uses any more — revoke those.</li>
</ul>
<p>Every create and revoke is written to your audit log with the person's email.</p>

<h2 id="webhooks">Webhook endpoints</h2>
<p>Everything the API can't do:</p>
<ul>
  <li><strong>Add</strong> an endpoint (https, public address) and choose events; the signing secret appears once.</li>
  <li><strong>Edit</strong> the URL or the subscribed events.</li>
  <li><strong>Pause / enable</strong> — a paused endpoint receives no new events.</li>
  <li><strong>Rotate secret</strong> — a new secret replaces the old one immediately; update your server first or be ready for failing signatures.</li>
  <li><strong>Send test</strong> — queues a signed <code class="inline">webhook.test</code> event to that endpoint and opens its delivery record so you can see the result.</li>
  <li><strong>Delete</strong> — the endpoint stops receiving events; past deliveries stay in the log.</li>
</ul>
<p>Each endpoint card shows its subscribed events and a red count of failed deliveries that links to the filtered log.</p>

<h2 id="deliveries">Deliveries</h2>
<p>Every event the platform tried to send, newest first. Filter by status, event type or endpoint. A delivery's page shows:</p>
<ul>
  <li>status, attempts, the last HTTP status and error, next attempt time, delivered time;</li>
  <li>the <strong>exact JSON payload</strong> sent.</li>
</ul>
<p><strong>Retry now</strong> re-queues a delivery that isn't yet delivered (attempts reset to zero). Finished deliveries are removed after 30 days. See <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'delivery']) }}">Delivery &amp; Retries</a>.</p>

<h2 id="settings">Settings page</h2>
<p>A read-only summary of your configuration, your assigned voice numbers (with active/disabled), and a copy-ready <code class="inline">curl</code> example for issuing a code against your base URL.</p>
