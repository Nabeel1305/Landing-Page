<div class="docs-eyebrow">Reference</div>
<h1>Client Libraries</h1>

<p>Two small, dependency-free clients ship with the platform (in <code class="inline">clients/</code>). They wrap the five API calls, generate idempotency keys for you, turn error responses into exceptions, and include the webhook signature check. You don't need them — the API is plain JSON over HTTPS — but they remove the easy mistakes.</p>

<h2 id="javascript">JavaScript (Node 18+)</h2>
<p>Package <code class="inline">offline-payments-client</code> (ES module, built on <code class="inline">fetch</code> and <code class="inline">node:crypto</code>).</p>
<x-docs.code lang="js">import { Client, ApiError, verifyWebhook } from 'offline-payments-client';

const client = new Client({ apiKey: process.env.OPK, baseUrl: '{{ config('docs.api_base') }}' });

await client.upsertSubscriber('cust-42', '+2348012345678');
await client.upsertMerchant('shop-7', 'Corner Shop', 'acct-9001');
const endpoint = await client.createWebhookEndpoint('https://you.example/hooks/offline', ['transaction.settled']);
// endpoint.secret is shown once — store it

try {
  const key = crypto.randomUUID();                       // store it with your payment attempt
  const issued = await client.issueCode({
    subscriber_reference: 'cust-42', merchant_reference: 'shop-7',
    amount_minor: 250000, currency: 'NGN', source_account_reference: 'acct-1234',
  }, key);                                               // omit the key to have one generated
  console.log(issued.code, issued.expires_at);
} catch (e) {
  if (e instanceof ApiError) console.error(e.status, e.code, e.message);   // e.g. 422 settlement_rejected
  else throw e;
}

await client.getCode(id);
await client.cancelCode(id);
await client.getTransaction(txId);</x-docs.code>

<h2 id="php">PHP (8.1+, curl)</h2>
<p>Package <code class="inline">offline-payments/client</code>, namespace <code class="inline">OfflinePayments</code>.</p>
<x-docs.code lang="php">use OfflinePayments\Client;
use OfflinePayments\ApiException;

$client = new Client(getenv('OPK'), '{{ config('docs.api_base') }}');

$client->upsertSubscriber('cust-42', '+2348012345678');
$client->upsertMerchant('shop-7', 'Corner Shop', 'acct-9001');
$endpoint = $client->createWebhookEndpoint('https://you.example/hooks/offline', ['transaction.settled']);

try {
    $issued = $client->issueCode([
        'subscriber_reference' => 'cust-42', 'merchant_reference' => 'shop-7',
        'amount_minor' => 250000, 'currency' => 'NGN', 'source_account_reference' => 'acct-1234',
    ], $idempotencyKey);                                  // optional; generated if omitted
    echo $issued['code'];
} catch (ApiException $e) {
    error_log("{$e->status} {$e->errorCode} {$e->getMessage()}");
}

$client->getCode($id);
$client->cancelCode($id);
$client->getTransaction($txId);</x-docs.code>
<p>You can pass your own HTTP transport (a callable) as the third constructor argument to use Guzzle or a test double.</p>

<h2 id="methods">Methods</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Method</th><th>API call</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">upsertSubscriber(reference, phone?)</code></td><td><code class="inline">PUT /subscribers/{reference}</code></td></tr>
    <tr><td><code class="inline">upsertMerchant(reference, name, accountReference)</code></td><td><code class="inline">PUT /merchants/{reference}</code></td></tr>
    <tr><td><code class="inline">createWebhookEndpoint(url, events?)</code></td><td><code class="inline">POST /webhook-endpoints</code></td></tr>
    <tr><td><code class="inline">issueCode(params, idempotencyKey?)</code></td><td><code class="inline">POST /codes</code></td></tr>
    <tr><td><code class="inline">getCode(id)</code></td><td><code class="inline">GET /codes/{id}</code></td></tr>
    <tr><td><code class="inline">cancelCode(id, idempotencyKey?)</code></td><td><code class="inline">POST /codes/{id}/cancel</code></td></tr>
    <tr><td><code class="inline">getTransaction(id)</code></td><td><code class="inline">GET /transactions/{id}</code></td></tr>
    <tr><td><code class="inline">verifyWebhook(secret, header, rawBody)</code> / <code class="inline">Webhook::verify(...)</code></td><td>Signature check — see <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'verifying']) }}">Verifying Signatures</a></td></tr>
  </tbody>
</table></div>

<h2 id="openapi">OpenAPI</h2>
<p>A machine-readable description of the same API (OpenAPI 3.1, including the webhook event) is in the repository at <code class="inline">docs/openapi.yaml</code> — import it into Postman, Insomnia or a code generator for other languages.</p>
