@extends('layouts.page')

@section('title', 'Security &amp; Compliance — PakaPay')
@section('description', 'The real security practices behind PakaPay: hardware-signed transfers, tamper-evident records, independent kill switches, and two-step verification.')
@section('canonical', 'https://pakapay.ng/compliance.html')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Security & Compliance", "item": "https://pakapay.ng/compliance.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Security &amp; Compliance</span>
    <h1>How PakaPay protects your money and your data.</h1>
    <p class="lede">The technical practices below are real and already built. Formal regulatory disclosures are a separate matter, noted honestly below rather than assumed.</p>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <h2>Hardware-signed transactions</h2>
    <p>Every payment is signed by a key that lives inside a hardware security module, not in software and not behind a password alone. The signing key itself never leaves the vault it's generated in.</p>

    <h2>Tamper-evident record-keeping</h2>
    <p>Every action writes into a chained record, with each entry locked to the one before it. Alter a single line and everything after it stops matching — which makes tampering detectable rather than silent.</p>

    <h2>Independent kill switches</h2>
    <p>One account, one device, or the whole system — any of the three can be frozen on its own, without affecting the others, the moment something looks wrong.</p>

    <h2>Two-step identity verification</h2>
    <p>New accounts are confirmed twice: once by a one-time code at signup, and again by BVN before higher spending limits unlock.</p>

    <h2>Fail-closed signing</h2>
    <p>If the signing service is unreachable, the payment fails outright rather than falling back to something weaker. A stopped payment is safer than an unsigned one.</p>

    <div class="notice">
      <strong>Placeholder — regulatory disclosures pending.</strong>
      This section is where PakaPay's formal regulatory status — licensing, insurance coverage, and any relevant registrations with Nigerian financial authorities — should be published once confirmed. We're not stating a license or registration here because we haven't verified one to state. If Payce Financial Technologies Ltd holds relevant licenses, this section should be updated with the specifics and any required regulatory badges.
    </div>

    <h2>Questions</h2>
    <p>For anything not covered here, see the <a href="{{ route('privacy') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">Privacy Policy</a>, or reach out through the <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>

  </div>
</section>
@endsection
