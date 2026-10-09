@extends('layouts.page')

@section('title', 'Nunu — Offline Payment Infrastructure for Banks | PakaPay')
@section('description', 'Nunu lets a bank or fintech accept payments from customers with no data connection: issue a one-time code through an API, the customer dials it, and your own system settles. Developer documentation included.')
@section('canonical', 'https://pakapay.ng/nunu.html')

@push('jsonld')
<script type="application/ld+json">{"@@context": "https://schema.org", "@type": "Service", "name": "Nunu", "provider": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}, "serviceType": "Offline payment infrastructure for financial institutions", "audience": {"@type": "BusinessAudience", "audienceType": "Banks, fintechs and wallet providers"}, "url": "https://pakapay.ng/nunu.html"}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Nunu", "item": "https://pakapay.ng/nunu.html"}]}</script>
@endpush

@section('content')
<section class="page-hero" id="main">
  <div class="wrap">
    <img src="{{ asset('img/nunu-mark.svg') }}" alt="nunu" width="150" height="44" style="display:block; margin-bottom:14px;">
    <span class="eyebrow">For banks and fintechs</span>
    <h1>Let your customers pay with nothing but a phone call.</h1>
    <p class="lede">Nunu is PakaPay's offline payment infrastructure. Your app asks for a one-time code through a simple API; later, with no data connection at all, your customer dials a number and keys the code in. Nunu verifies the call and tells <em>your</em> system to move the money.</p>
    <div class="cta-row" style="margin-top:24px; display:flex; gap:14px; flex-wrap:wrap;">
      <a href="{{ route('docs.index') }}" class="btn btn-amber">Read the documentation</a>
      <a href="{{ route('contact') }}" class="btn btn-outline">Talk to us about Nunu</a>
    </div>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <figure style="margin:0 0 36px;">
      <img src="{{ asset('img/nunu-flow.svg') }}" alt="Diagram: a bank app issues a one-time code, the customer dials it from a basic phone with no data, and the bank's own system settles the payment" width="900" height="620" style="width:100%; height:auto; border-radius:22px; display:block;">
    </figure>

    <h2>The problem Nunu solves</h2>
    <p>A large share of everyday payments happen where the network is patchy: markets, transport, rural areas, power cuts. When data drops, card and app payments fail. A basic phone call still works. Nunu turns a call into a safe payment instruction, so you can offer a fallback rail that works anywhere a customer can make a voice call.</p>

    <h2>How a payment works</h2>
    <div class="card-grid">
      <div class="info-card">
        <h3>1. Issue a code</h3>
        <p>While the customer is still online, your app calls the Nunu API with an amount and a merchant. Nunu asks your system to <strong>hold</strong> the funds, then returns a one-time code.</p>
      </div>
      <div class="info-card">
        <h3>2. The customer dials</h3>
        <p>Later — with no data — they call the voice number and key in the code, then <code>#</code>. Any phone will do; there is nothing to install.</p>
      </div>
      <div class="info-card">
        <h3>3. Nunu verifies</h3>
        <p>The call and the code are checked: valid, unused, unexpired, and optionally from the customer's registered number. Then Nunu asks your system to <strong>capture</strong> the held funds.</p>
      </div>
      <div class="info-card">
        <h3>4. You confirm</h3>
        <p>Signed webhooks tell your server what happened, and you tell the customer. The caller only hears a generic thank-you, so the phone line can't be used to guess codes.</p>
      </div>
    </div>

    <h2>You stay in control of the money</h2>
    <p><strong>Nunu never holds funds.</strong> Your core system places the hold and performs the transfer; Nunu only decides when. That keeps your ledger, your limits and your customer checks (KYC, fraud, sanctions) exactly where they are today. Funds held for a code that is cancelled, expires or fails are released automatically, and a payment whose result gets lost on the network is resolved by a built-in reconciliation job rather than left in doubt.</p>

    <h2>What you get</h2>
    <div class="card-grid">
      <div class="info-card">
        <h3>A small, clear API</h3>
        <p>Register subscribers and merchants, issue and cancel codes, read transactions. Safe retries through idempotency keys, and PHP and JavaScript client libraries.</p>
      </div>
      <div class="info-card">
        <h3>Signed webhooks</h3>
        <p>Redeemed, settled, failed and expired events, HMAC-signed, retried automatically, with a full delivery log.</p>
      </div>
      <div class="info-card">
        <h3>A developer portal</h3>
        <p>Your team creates API keys, manages webhook endpoints, sends test events and inspects every delivery. Finance and operations staff see transactions and reports.</p>
      </div>
      <div class="info-card">
        <h3>A sandbox</h3>
        <p>Build and test the whole flow against a simulated core system, including failure cases, before connecting the real one.</p>
      </div>
      <div class="info-card">
        <h3>Built for isolation and audit</h3>
        <p>Every organisation's data is separate, every action is written to a tamper-evident audit log, and sign-in to the portal requires two-factor.</p>
      </div>
      <div class="info-card">
        <h3>Your numbers or ours</h3>
        <p>Run on a voice number of your own, or share one with a short code that routes each call to the right organisation.</p>
      </div>
    </div>

    <h2>For developers</h2>
    <p>The documentation covers everything from a six-step quickstart to the settlement contract your core system implements.</p>
    <div class="card-grid">
      <div class="info-card">
        <h3><a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'quickstart']) }}">Quickstart →</a></h3>
        <p>From nothing to a settled sandbox payment.</p>
      </div>
      <div class="info-card">
        <h3><a href="{{ route('docs.show', ['section' => 'api', 'page' => 'codes']) }}">API reference →</a></h3>
        <p>Subscribers, merchants, payment codes and transactions.</p>
      </div>
      <div class="info-card">
        <h3><a href="{{ route('docs.show', ['section' => 'webhooks', 'page' => 'verifying']) }}">Webhooks →</a></h3>
        <p>Events, signatures, retries and delivery.</p>
      </div>
      <div class="info-card">
        <h3><a href="{{ route('docs.show', ['section' => 'payers', 'page' => 'settlement']) }}">Settlement →</a></h3>
        <p>The hold / capture / release / status contract for your system.</p>
      </div>
    </div>

    <h2>Questions banks ask</h2>
    <details class="acc-item" open>
      <summary>Is Nunu an app my customers download?</summary>
      <p>No. Nunu is infrastructure your bank or fintech integrates. Customers use your app (or your USSD or agent channel) to get a code, and any phone to dial it.</p>
    </details>
    <details class="acc-item">
      <summary>Do you hold or move customer money?</summary>
      <p>No. Your system holds and captures funds; Nunu orchestrates and verifies.</p>
    </details>
    <details class="acc-item">
      <summary>Who does KYC on the customer?</summary>
      <p>You do. Nunu trusts the subscribers you register, and your own limits and checks apply when you place the hold.</p>
    </details>
    <details class="acc-item">
      <summary>What is available today?</summary>
      <p>The full API, webhooks, portal and a sandbox that simulates your core system. Connecting your real core banking system means building a settlement adapter against your API together with our team; we work out the scope with you.</p>
    </details>
    <details class="acc-item">
      <summary>What does it cost?</summary>
      <p>Pricing depends on scale and integration scope, so we discuss it directly with your team.</p>
    </details>

    <div class="notice" style="margin-top:32px;">
      <strong>Ready to try it?</strong> <a href="{{ route('docs.index') }}">Open the documentation</a> to build against the sandbox, or <a href="{{ route('contact') }}">contact us</a> to talk about connecting your system.
    </div>

  </div>
</section>
@endsection
