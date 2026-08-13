@extends('layouts.page')

@section('title', 'Help Center — PakaPay')
@section('description', 'Answers for getting started, payments, security, PayPoints, and your account on PakaPay.')
@section('canonical', 'https://pakapay.ng/help.html')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Help Center", "item": "https://pakapay.ng/help.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Help Center</span>
    <h1>How can we help?</h1>
    <p class="lede">Answers grounded in how the app actually works — not generic support copy. Can't find it here? Reach out on the <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a>.</p>
  </div>
</section>

<section class="content">
  <div class="wrap">

    <div class="acc-group">
      <span class="acc-group-label">Getting started</span>

      <details class="acc-item" open>
        <summary>How do I create a PakaPay account?<span class="plus">+</span></summary>
        <p>Account creation is two steps. First you register with your phone number, then you confirm it with a one-time code sent to that number. Your account can't hold or move money until both steps are complete — so if you registered but never got asked for a code, go back and finish verification before assuming something's wrong.</p>
      </details>

      <details class="acc-item">
        <summary>Why do I need to submit my BVN?<span class="plus">+</span></summary>
        <p>Your Bank Verification Number confirms it's really you before higher spending limits unlock. You'll see this as a "Let's Confirm It's You" step, where you enter your 11-digit BVN. After you submit it, your information goes under review and you'll be notified once it's complete — this isn't instant.</p>
      </details>

      <details class="acc-item">
        <summary>How do I link a bank account?<span class="plus">+</span></summary>
        <p>From Settings, add a bank account under Linked Bank Accounts. Once it's linked, you can use it to fund PayPoints, receive transfers, and move money out. You can link more than one account and switch between them.</p>
      </details>
    </div>

    <div class="acc-group">
      <span class="acc-group-label">Payments</span>

      <details class="acc-item">
        <summary>My transfer says pending — what do I do?<span class="plus">+</span></summary>
        <p>Online transfers are signed and confirmed almost immediately. If one is stuck pending for more than a few minutes, don't retry the same payment blindly — check Transaction History first to confirm it hasn't already gone through, then contact us with the transaction reference if it's genuinely stuck.</p>
      </details>

      <details class="acc-item">
        <summary>I placed an offline call to pay, but it didn't confirm<span class="plus">+</span></summary>
        <p>Offline payments confirm the moment the call connects and the full tone sequence is carried through. If the call drops early, gets rejected, or the tones are cut off, the payment won't confirm — this usually means placing the call again from a clear signal area, even a weak one (offline pay needs a phone connection, just not a data connection).</p>
      </details>

      <details class="acc-item">
        <summary>What is Quick Pay, and how do I turn it off?<span class="plus">+</span></summary>
        <p>Quick Pay lets small, trusted transfers go through without a PIN prompt every time, so you're not typing your PIN for every tiny payment. You can switch it off entirely from Settings if you'd rather confirm every transfer manually.</p>
      </details>
    </div>

    <div class="acc-group">
      <span class="acc-group-label">Security</span>

      <details class="acc-item">
        <summary>I forgot my PIN — what now?<span class="plus">+</span></summary>
        <p>Go to Settings → Change Security PIN. You'll be asked to verify it's you before you can set a new one — this is intentional, since your PIN protects the signing of every transfer you make.</p>
      </details>

      <details class="acc-item">
        <summary>My phone was lost or stolen. What should I do?<span class="plus">+</span></summary>
        <p>Freeze the device as soon as you're able to — this locks that specific device without touching your whole account, so you can keep using PakaPay from another phone once you're verified. Device freezing is separate from account freezing and system-wide freezing, so only what needs to stop, stops.</p>
      </details>

      <details class="acc-item">
        <summary>How does biometric login work?<span class="plus">+</span></summary>
        <p>Once enabled in Settings, you can unlock the app with your fingerprint instead of typing your PIN each time you open it. Your PIN still works as a fallback, and biometrics can be turned off at any point.</p>
      </details>
    </div>

    <div class="acc-group">
      <span class="acc-group-label">PayPoints</span>

      <details class="acc-item">
        <summary>How do I set up a PayPoint?<span class="plus">+</span></summary>
        <p>From Settings → Manage Payment Points, connect a PayPoint from any linked bank account, or connect to one someone has shared with you. Each PayPoint gets its own QR code and its own running total.</p>
      </details>

      <details class="acc-item">
        <summary>What happens to a PayPoint if I remove the bank account behind it?<span class="plus">+</span></summary>
        <p>The PayPoint is archived automatically the moment its linked account is removed — it won't keep collecting money with nowhere for that money to settle.</p>
      </details>
    </div>

    <div class="acc-group">
      <span class="acc-group-label">Account</span>

      <details class="acc-item">
        <summary>How do I close my account?<span class="plus">+</span></summary>
        <p>Log Out is available from Settings, but closing an account entirely is a separate, deliberate action — reach out through the contact page and we'll walk you through it, since it affects any PayPoints and linked accounts tied to it.</p>
      </details>

      <details class="acc-item">
        <summary>Didn't find your answer?<span class="plus">+</span></summary>
        <p>Send us a message from the <a href="{{ route('contact') }}" style="color:var(--navy); font-weight:600; text-decoration:underline;">contact page</a> and describe what you're seeing — the more specific (what screen, what error, roughly when), the faster we can help.</p>
      </details>
    </div>

  </div>
</section>
@endsection
