@extends('layouts.page')

@section('title', 'About PakaPay — Payce Financial Technologies')
@section('description', 'PakaPay is a mobile wallet product of Payce Financial Technologies Ltd, built for Nigeria\'s markets.')
@section('canonical', 'https://pakapay.ng/about.html')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "About", "item": "https://pakapay.ng/about.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">About</span>
    <h1>Built for Nigeria's markets, not just its cities.</h1>
    <p class="lede">PakaPay is a mobile wallet product of Payce Financial Technologies Ltd.</p>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <div class="notice">
      <strong>This page is intentionally brief.</strong>
      We'd rather say less here than invent a founding story, team bios, or milestones we can't verify. As PakaPay's real history and team are ready to share publicly, this page should be expanded with the real details — not before.
    </div>

    <h2>What PakaPay is</h2>
    <p>PakaPay moves money three ways — tap, scan, or dial — so a payment doesn't have to wait on a data connection that isn't always there. It's built around the reality that a lot of everyday commerce in Nigeria happens somewhere the network is inconsistent, and a wallet that only works when the signal is strong isn't much use in a market, a stall, or a delivery run.</p>

    <h2>Who operates it</h2>
    <p>PakaPay is a product of Payce Financial Technologies Ltd.</p>

    <h2>How we think about security</h2>
    <p>Every transfer is signed by a hardware key that never leaves its vault, and every action is written into a record that locks itself. We built it this way because a wallet that's fast but easy to compromise isn't actually useful to anyone — see the full breakdown on the <a href="{{ route(\'home\') }}#security" style="color:var(--navy); font-weight:600; text-decoration:underline;">Security</a> section of the homepage.</p>

    <h2>Get in touch</h2>
    <p>Questions about the company, partnerships, or press — reach out through the <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>

  </div>
</section>
@endsection
