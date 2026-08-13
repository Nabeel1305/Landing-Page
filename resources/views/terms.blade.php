@extends('layouts.page')

@section('title', 'Terms of Service — PakaPay')
@section('description', 'The terms that govern your use of PakaPay.')
@section('canonical', '')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Terms of Service", "item": "https://pakapay.ng/terms.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Legal</span>
    <h1>Terms of Service</h1>
    <p class="lede">The terms that govern your use of PakaPay.</p>
    <div class="meta-line">Draft version — last edited August 2026</div>
  </div>
</section>

<section class="content">
  <div class="wrap">
    <div class="notice">
      <strong>Placeholder — pending legal review.</strong>
      This page is a structural draft, not a finished or legally binding terms of service. Payce Financial Technologies Ltd should have this reviewed and finalized by qualified counsel before it is published or relied on.
    </div>

    <div class="toc">
      <h3>On this page</h3>
      <a href="#eligibility">1. Eligibility &amp; verification</a>
      <a href="#using">2. Using PakaPay</a>
      <a href="#fees">3. Fees</a>
      <a href="#limits">4. Spending limits &amp; Quick Pay</a>
      <a href="#security">5. Security &amp; your responsibilities</a>
      <a href="#freezing">6. Freezing &amp; suspension</a>
      <a href="#liability">7. Liability</a>
      <a href="#termination">8. Termination</a>
      <a href="#law">9. Governing law</a>
      <a href="#contact">10. Contact us</a>
    </div>

    <h2 id="eligibility">1. Eligibility &amp; verification</h2>
    <p>To open a PakaPay account, you'll confirm your phone number by one-time code and verify your identity with your Bank Verification Number (BVN). Your account can't hold or move funds until both steps are complete.</p>

    <h2 id="using">2. Using PakaPay</h2>
    <p>PakaPay lets you send and receive money by scanning a QR code, through a standing PayPoint, or — when you have no data connection — by placing a signed phone call that carries the payment instruction as a tone sequence. You agree to use these only for payments you're authorized to make.</p>

    <h2 id="fees">3. Fees</h2>
    <p>Fees vary by transfer type and are shown before you confirm any payment. Nothing is deducted without you seeing the amount first.</p>

    <h2 id="limits">4. Spending limits &amp; Quick Pay</h2>
    <p>You can set your own daily and per-transaction spending caps in Settings. Small, trusted transfers under Quick Pay may go through without a PIN prompt each time; you can adjust this behavior at any point.</p>

    <h2 id="security">5. Security &amp; your responsibilities</h2>
    <p>Keep your PIN, device, and biometric access to yourself. If your device is lost or stolen, you're responsible for freezing it as soon as you're able to.</p>

    <h2 id="freezing">6. Freezing &amp; suspension</h2>
    <p>We may freeze your account, your device, or the wider system independently if we detect unusual activity or a security risk. We'll aim to tell you why, and how to get reinstated once it's resolved.</p>

    <h2 id="liability">7. Liability</h2>
    <p>[To be defined by legal counsel — this section should set out liability for unauthorized transactions, lost devices, and disputed payments, consistent with applicable Nigerian consumer and financial services law.]</p>

    <h2 id="termination">8. Termination</h2>
    <p>You can close your account at any time. We may suspend or close accounts that violate these terms or applicable law.</p>

    <h2 id="law">9. Governing law</h2>
    <p>[To be defined by legal counsel — governing law and jurisdiction for disputes arising from use of PakaPay.]</p>

    <h2 id="contact">10. Contact us</h2>
    <p>Questions about these terms can be sent through our <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>
  </div>
</section>
@endsection
