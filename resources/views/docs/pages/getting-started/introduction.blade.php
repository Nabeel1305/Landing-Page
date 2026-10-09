<div class="docs-eyebrow">Getting Started</div>
<h1>Introduction</h1>

<p>The PakaPay <strong>Offline Payments API</strong> lets a bank, fintech or wallet let its customers pay with <strong>nothing but a phone call</strong>. Your app asks the platform for a one-time numeric code while the customer is still online. Later — with no data connection at all — the customer dials a voice number and keys the code in. The platform verifies the call and tells <em>your</em> system to move the money.</p>

<x-docs.callout type="note" title="The platform never holds funds">
  <p>PakaPay verifies and orchestrates; <strong>your own core system</strong> places the hold and performs the transfer (see <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'settlement']) }}">Settlement Adapter</a>). Because of that, identity checks on your customers (KYC, sanctions, fraud scoring) are <strong>your</strong> responsibility — the platform trusts the subscribers you register.</p>
</x-docs.callout>

<x-docs.callout type="note" title="Base URL">
  <p><code class="inline">{{ config('docs.api_base') }}</code></p>
  <p>Every endpoint in the <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'codes']) }}">API Reference</a> is relative to this. All requests are JSON over HTTPS and need an <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}">API key</a>.</p>
</x-docs.callout>

<h2 id="how-a-payment-works">How a payment works</h2>
<ol>
  <li><strong>Issue.</strong> Your app calls <code class="inline">POST /codes</code> while the payer is online and has chosen an amount and a merchant. The platform asks your system to <strong>hold</strong> the funds, then returns a code of digits.</li>
  <li><strong>Show.</strong> Your app displays the code (and the voice number to dial).</li>
  <li><strong>Dial.</strong> The payer calls the voice number and keys the code, then <code class="inline">#</code>. No data connection is needed for this step.</li>
  <li><strong>Verify and capture.</strong> The platform checks the call and the code, then asks your system to <strong>capture</strong> the held funds for the merchant.</li>
  <li><strong>Notify.</strong> You receive signed <a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'events']) }}">webhooks</a> — <code class="inline">code.redeemed</code>, then <code class="inline">transaction.settled</code> or <code class="inline">transaction.failed</code>. The payer hears only a generic thank-you; <strong>you</strong> tell them the result, from your webhook.</li>
</ol>
<x-docs.code lang="text">  your app ──POST /codes──▶ platform ──hold──▶ your core system
      ▲                         │
      └─────── code ────────────┘
  payer ──(phone call, code#)──▶ voice number ──▶ platform ──capture──▶ your core system
  your server ◀── webhook: code.redeemed, transaction.settled / failed ── platform</x-docs.code>
<p>A code works once and expires after the lifetime set on your account (default 10 minutes). Funds held for a code that is cancelled, expires or fails are released.</p>

<h2 id="what-you-get">What you get</h2>
<ul>
  <li><strong>A small REST API</strong> — subscribers, merchants, codes, transactions, webhook endpoints.</li>
  <li><strong>Signed, retried webhooks</strong> with a verification helper for PHP and JavaScript (<a href="{{ route('docs.show', ['section' => 'clients', 'page' => 'clients']) }}">Client Libraries</a>).</li>
  <li><strong>Safe retries</strong> — every issue/cancel call is idempotent.</li>
  <li><strong>A tenant portal</strong> at <code class="inline">/portal</code> where your team sees transactions, codes, webhook deliveries and the audit log, and manages API keys and webhook endpoints. See <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'overview']) }}">Tenant Portal</a>.</li>
  <li><strong>A sandbox</strong> that simulates your core system, including failures, so you can test end to end before connecting the real thing.</li>
</ul>

<h2 id="who-is-this-for">Who reads what</h2>
<ul>
  <li><strong>Integrating developers</strong> — start with the <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'quickstart']) }}">Quickstart</a>, then the API Reference and Webhooks.</li>
  <li><strong>Teams connecting a core banking system</strong> — <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'settlement']) }}">Settlement Adapter</a> and <a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'going-live']) }}">Going Live</a>.</li>
  <li><strong>Operations and support staff</strong> — the <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'overview']) }}">Tenant Portal</a> guides.</li>
  <li><strong>PakaPay platform operators</strong> — the <em>Operators</em> section.</li>
</ul>
