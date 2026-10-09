<div class="docs-eyebrow">Webhooks</div>
<h1>Verifying Signatures</h1>

<p>Anyone who learns your webhook URL can post to it. Only the platform knows your endpoint's secret, so a valid signature proves a delivery is genuine and unaltered. <strong>Always verify before acting</strong> — especially before telling a customer their payment worked.</p>

<h2 id="header">The header</h2>
<x-docs.code lang="text">Offline-Signature: t=1791558204,v1=3b0c5e…64 hex characters…</x-docs.code>
<ul>
  <li><code class="inline">t</code> — Unix time (seconds) when the delivery was signed.</li>
  <li><code class="inline">v1</code> — hex <strong>HMAC-SHA256</strong> of the string <code class="inline">"&lt;t&gt;.&lt;raw body&gt;"</code>, keyed with your endpoint secret (<code class="inline">whsec_…</code>, used as-is).</li>
</ul>

<h2 id="steps">Verify in four steps</h2>
<ol>
  <li>Read the <strong>raw</strong> request body — the exact bytes. Do not parse and re-serialise it; that changes whitespace and key order and breaks the signature.</li>
  <li>Parse <code class="inline">t</code> and <code class="inline">v1</code> from the header. Reject if it doesn't match <code class="inline">t=&lt;digits&gt;,v1=&lt;64 hex&gt;</code>.</li>
  <li>Reject if <code class="inline">t</code> is more than <strong>five minutes</strong> from your clock (replay protection). Keep your server's time synced.</li>
  <li>Compute the HMAC and compare to <code class="inline">v1</code> in <strong>constant time</strong>.</li>
</ol>

<h2 id="examples">Examples</h2>
<x-docs.code lang="js">// Node — the official client does this for you
import { verifyWebhook } from 'offline-payments-client';

app.post('/hooks/offline', express.raw({ type: 'application/json' }), (req, res) => {
  const ok = verifyWebhook(process.env.WHSEC, req.get('Offline-Signature'), req.body); // req.body is a Buffer
  if (!ok) return res.sendStatus(400);

  const event = JSON.parse(req.body);
  // …de-duplicate on event.id, act on event.type…
  res.sendStatus(200);
});</x-docs.code>
<x-docs.code lang="php">// PHP
use OfflinePayments\Webhook;

$raw = file_get_contents('php://input');
$header = $_SERVER['HTTP_OFFLINE_SIGNATURE'] ?? '';

if (! Webhook::verify(getenv('WHSEC'), $header, $raw)) {
    http_response_code(400);
    exit;
}
$event = json_decode($raw, true);
http_response_code(200);</x-docs.code>
<x-docs.code lang="python"># Python (no official client — the algorithm is five lines)
import hmac, hashlib, re, time

def verify(secret: str, header: str, raw_body: bytes, tolerance: int = 300) -> bool:
    m = re.fullmatch(r"t=(\d+),v1=([0-9a-f]{64})", header or "")
    if not m or abs(time.time() - int(m[1])) > tolerance:
        return False
    expected = hmac.new(secret.encode(), m[1].encode() + b"." + raw_body, hashlib.sha256).hexdigest()
    return hmac.compare_digest(expected, m[2])</x-docs.code>

<h2 id="idempotent-handling">Handle events idempotently</h2>
<ul>
  <li>The same event can arrive more than once. Store processed <code class="inline">event.id</code>s and skip repeats.</li>
  <li>Do the minimum inside the request (verify, record, queue) and answer <code class="inline">2xx</code> quickly. The platform waits only <strong>10 seconds</strong> before treating the attempt as failed.</li>
  <li>Return <code class="inline">400</code> for a bad signature; the platform will keep retrying, which is fine — it also tells you something is misconfigured.</li>
</ul>

<h2 id="testing">Testing</h2>
<p>Use <strong>Send test</strong> next to an endpoint in the portal. It delivers a signed <code class="inline">webhook.test</code> event, and the delivery log shows your response code and any error.</p>
