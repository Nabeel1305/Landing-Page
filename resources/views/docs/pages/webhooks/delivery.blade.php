<div class="docs-eyebrow">Webhooks</div>
<h1>Delivery &amp; Retries</h1>

<h2 id="success">What counts as delivered</h2>
<p>Any <strong>2xx</strong> response within <strong>10 seconds</strong>. Anything else — another status code, a redirect, a timeout, a connection or TLS error, or a URL that is no longer public — is a failed attempt.</p>

<h2 id="retries">Retry schedule</h2>
<p>A failed delivery is retried with growing delays. Including the first attempt, an event is tried <strong>seven times</strong> over roughly 21 hours:</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Attempt</th><th>When</th></tr></thead>
  <tbody>
    <tr><td>1</td><td>Immediately</td></tr>
    <tr><td>2</td><td>1 minute later</td></tr>
    <tr><td>3</td><td>5 minutes later</td></tr>
    <tr><td>4</td><td>30 minutes later</td></tr>
    <tr><td>5</td><td>2 hours later</td></tr>
    <tr><td>6</td><td>6 hours later</td></tr>
    <tr><td>7</td><td>12 hours later</td></tr>
  </tbody>
</table></div>
<p>After the last failure the delivery is marked <code class="inline">failed</code> and is not retried automatically. The schedule is an operator setting (<code class="inline">PLATFORM_WEBHOOK_BACKOFF</code>), so your deployment may differ.</p>
<p>Each retry carries the <strong>same event id</strong> and a freshly signed timestamp. Retries run on a queue worker; the operator must keep one running.</p>

<h2 id="log">Delivery log</h2>
<p>Every delivery is recorded with its status (<code class="inline">pending</code>, <code class="inline">delivered</code>, <code class="inline">failed</code>), the number of attempts, the last HTTP status or error, the exact payload sent, and timestamps. You see it in the portal under <em>Deliveries</em>. Finished deliveries are kept for <strong>30 days</strong>, then removed.</p>

<h2 id="redelivery">Re-delivering</h2>
<p>From the delivery's page in the portal, <strong>Retry now</strong> resets a pending or failed delivery and queues it again (attempts start from zero). A delivered one can't be re-sent. If you were down for hours, bulk-recover by listing failed deliveries and retrying them — or read each payment with <code class="inline">GET /transactions/{id}</code>.</p>

<h2 id="checklist">Receiver checklist</h2>
<ul>
  <li>Public HTTPS URL, valid certificate, no redirects.</li>
  <li>Verify the signature on the raw body.</li>
  <li>Answer <code class="inline">2xx</code> fast; do slow work after responding.</li>
  <li>De-duplicate on <code class="inline">id</code>; tolerate out-of-order arrival.</li>
  <li>Alert on failed deliveries — the portal dashboard shows delivery health and recent failures.</li>
</ul>
