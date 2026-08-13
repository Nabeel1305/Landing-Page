@extends('layouts.page')

@section('title', 'PakaPay for Business — PayPoints')
@section('description', 'Set up a PayPoint for your stall, shop, rent, or fundraiser — its own QR code, its own running total, signed the same way as every PakaPay transfer.')
@section('canonical', 'https://pakapay.ng/business.html')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Business", "item": "https://pakapay.ng/business.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">For business</span>
    <h1>A PayPoint for however you get paid.</h1>
    <p class="lede">A PayPoint is a standing account for money you collect regularly — a stall, a shop till, rent, a fundraiser. Set one up and it keeps its own QR code, its own running total, and its own history, separate from everything else you're holding.</p>
    <div class="cta-row" style="margin-top:24px; display:flex; gap:14px; flex-wrap:wrap;">
      <a href="{{ route(\'home\') }}#download" class="btn btn-amber">Download PakaPay</a>
      <a href="{{ route('help') }}" class="btn btn-outline">See setup help</a>
    </div>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <h2>What makes a PayPoint different from just an account</h2>
    <p>Your bank accounts hold money. A PayPoint is built for <em>collecting</em> it — it's a named, purpose-built way to take payments for one specific thing, with its own QR code generated the moment you connect it. Scan-to-pay works the same way it does everywhere else on PakaPay; the difference is what happens to the money once it lands, and how you track it.</p>

    <h2>Personal and business PayPoints stay separate</h2>
    <p>You can open more than one PayPoint, and each is typed as either personal or business. A market stall's PayPoint and a personal savings goal's PayPoint don't share a QR namespace or a running total — money collected for your business doesn't quietly blend into money you're setting aside personally, without you having to manage that separation yourself.</p>

    <h2>Every PayPoint gets its own QR code</h2>
    <p>Because each PayPoint is issued its own code, you can hand out a specific QR for a specific purpose — one for your stall, a different one for a side project — and know exactly which one a payment came through when it lands.</p>

    <h2>Closing the account behind a PayPoint closes the PayPoint too</h2>
    <p>If you remove the bank account a PayPoint settles into, the PayPoint archives automatically. Nothing is left open collecting money with nowhere for it to go — you don't have to remember to manually shut it down.</p>

    <h2>The same security, whether it's ₦500 or ₦500,000</h2>
    <p>Every payment into a PayPoint is signed by the same hardware key used for every other PakaPay transfer, and written into the same tamper-evident record. Business use doesn't get a lighter version of the security — it gets the same one.</p>

    <div class="card-grid">
      <div class="info-card">
        <h3>Set it up in Settings</h3>
        <p>Manage Payment Points → Connect a Payment Point, from any linked bank account, or connect to one shared with you.</p>
      </div>
      <div class="info-card">
        <h3>Works online or offline</h3>
        <p>PayPoints accept payments through the same rails as everything else — tap, scan, or the offline dial when there's no data.</p>
      </div>
      <div class="info-card">
        <h3>One history, one total</h3>
        <p>Each PayPoint tracks what came in and when, so you're not digging through a combined statement to find what a specific stall or project earned.</p>
      </div>
    </div>

  </div>
</section>
@endsection
