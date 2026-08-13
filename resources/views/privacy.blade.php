@extends('layouts.page')

@section('title', 'Privacy Policy — PakaPay')
@section('description', 'How PakaPay collects, uses, and protects your information.')
@section('canonical', '')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Privacy Policy", "item": "https://pakapay.ng/privacy.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Legal</span>
    <h1>Privacy Policy</h1>
    <p class="lede">How PakaPay collects, uses, and protects your information.</p>
    <div class="meta-line">Draft version — last edited August 2026</div>
  </div>
</section>

<section class="content">
  <div class="wrap">
    <div class="notice">
      <strong>Placeholder — pending legal review.</strong>
      This page is a structural draft, not a finished or legally binding privacy policy. Payce Financial Technologies Ltd should have this reviewed and finalized by qualified counsel, familiar with Nigeria's Data Protection Act, before it is published or relied on.
    </div>

    <div class="toc">
      <h3>On this page</h3>
      <a href="#collect">1. Information we collect</a>
      <a href="#use">2. How we use it</a>
      <a href="#protect">3. How we protect it</a>
      <a href="#share">4. Who we share it with</a>
      <a href="#rights">5. Your rights</a>
      <a href="#retention">6. How long we keep it</a>
      <a href="#changes">7. Changes to this policy</a>
      <a href="#contact">8. Contact us</a>
    </div>

    <h2 id="collect">1. Information we collect</h2>
    <p>To open and run a PakaPay account, we collect information including:</p>
    <ul>
      <li><strong>Identity information</strong> — your name, phone number, and Bank Verification Number (BVN), used to confirm it's really you before your account can hold funds.</li>
      <li><strong>Financial information</strong> — the bank accounts you link, transaction history, and balances held in PayPoints.</li>
      <li><strong>Device information</strong> — a device-specific key used to sign offline payments, and general device details needed to keep your session secure.</li>
      <li><strong>Usage information</strong> — how you use the app, so we can detect problems and unusual activity.</li>
    </ul>

    <h2 id="use">2. How we use it</h2>
    <p>We use this information to open and verify your account, process transfers you authorize, detect and prevent fraud, meet our regulatory obligations, and improve the product.</p>

    <h2 id="protect">3. How we protect it</h2>
    <p>Every payment is signed by a hardware key that never leaves its vault — not stored in software, not tied to a password. Every action you take is written into a record that locks itself: change one entry and everything after it stops matching, which makes tampering detectable. Your account, your device, or the whole system can each be frozen independently the moment something looks wrong.</p>

    <h2 id="share">4. Who we share it with</h2>
    <p>We share information only where necessary to operate PakaPay — with the banks you link, identity verification providers used during signup, and infrastructure providers who help us run the service securely. We do not sell your information.</p>

    <h2 id="rights">5. Your rights</h2>
    <p>You can ask to see the information we hold about you, correct anything inaccurate, or request that we delete it, subject to what we're required to keep for regulatory or fraud-prevention purposes.</p>

    <h2 id="retention">6. How long we keep it</h2>
    <p>We keep account and transaction records for as long as your account is active, and for a period afterward as required by financial regulation.</p>

    <h2 id="changes">7. Changes to this policy</h2>
    <p>If this policy changes in a way that affects how your information is handled, we'll let you know before the change takes effect.</p>

    <h2 id="contact">8. Contact us</h2>
    <p>Questions about this policy or your data can be sent through our <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>
  </div>
</section>
@endsection
