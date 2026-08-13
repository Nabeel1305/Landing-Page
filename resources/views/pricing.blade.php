@extends('layouts.page')

@section('title', 'Pricing — PakaPay')
@section('description', 'How PakaPay pricing works — fees shown before you confirm, every time.')
@section('canonical', 'https://pakapay.ng/pricing.html')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Pricing", "item": "https://pakapay.ng/pricing.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Pricing</span>
    <h1>Fees you see before you pay — never after.</h1>
    <p class="lede">We're not publishing a fixed fee schedule here yet, because we'd rather show you the real number in the app at the moment it applies to you than print a table that goes stale. Here's how it actually works.</p>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <div class="notice">
      <strong>Placeholder — pending a published fee schedule.</strong>
      Detailed, itemized pricing should replace this table once Payce Financial Technologies Ltd finalizes it. Until then, this page describes the fee philosophy honestly rather than inventing numbers.
    </div>

    <h2>How pricing works today</h2>
    <p>Every transfer shows its fee, if any, before you confirm it. Nothing is deducted without you seeing the amount first — that's true whether you're paying by tap, scan, or offline dial.</p>

    <div class="price-table">
      <div class="price-row">
        <div><div class="pname">Tap &amp; Scan transfers</div><div class="pdesc">Sending or receiving via QR code</div></div>
        <div class="pval">Shown at checkout</div>
      </div>
      <div class="price-row">
        <div><div class="pname">PayPoint transfers</div><div class="pdesc">Payments into a personal or business PayPoint</div></div>
        <div class="pval">Shown at checkout</div>
      </div>
      <div class="price-row">
        <div><div class="pname">Offline dial payments</div><div class="pdesc">Signed transfers placed as a phone call</div></div>
        <div class="pval">Shown at checkout</div>
      </div>
      <div class="price-row">
        <div><div class="pname">Account funding &amp; withdrawal</div><div class="pdesc">Moving money between PakaPay and a linked bank</div></div>
        <div class="pval">Shown at checkout</div>
      </div>
    </div>

    <h2>Why we're not listing numbers yet</h2>
    <p>A published rate that's wrong is worse than no published rate at all — it either undersells what something actually costs or overstates it, and either way someone gets a surprise. Once there's a finalized fee schedule, it belongs here, plainly, not buried in the app.</p>

    <h2>Questions about a specific fee</h2>
    <p>If a fee you were charged doesn't match what the app showed you before you confirmed, that's worth reporting — reach out through the <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>

  </div>
</section>
@endsection
