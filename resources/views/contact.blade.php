@extends('layouts.page')

@section('title', 'Contact — PakaPay')
@section('description', 'Get in touch with the PakaPay team for account support, partnerships, or press.')
@section('canonical', '')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://pakapay.ng/"}, {"@type": "ListItem", "position": 2, "name": "Contact", "item": "https://pakapay.ng/contact.html"}]}</script>
@endpush

@section('content')
<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Contact</span>
    <h1>Get in touch</h1>
    <p class="lede">Questions about your account, a partnership idea, or press — send us a message and we'll get back to you.</p>
  </div>
</section>

<section class="content">
  <div class="wrap">

    @if (session('success'))
      <div class="notice" style="background:#E7F5EC; border-color:#B7E4C7; color:#1F5C3A;">
        <strong>Message sent.</strong>
        {{ session('success') }}
      </div>
    @endif

    <div class="contact-grid">
      <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
        @csrf
        <div class="field">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Your full name" value="{{ old('name') }}" required>
          @error('name') <p style="color:#C0392B; font-size:12.5px; margin-top:6px;">{{ $message }}</p> @enderror
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
          @error('email') <p style="color:#C0392B; font-size:12.5px; margin-top:6px;">{{ $message }}</p> @enderror
        </div>
        <div class="field">
          <label for="reason">What's this about?</label>
          <select id="reason" name="reason">
            @foreach (['Account or transaction support', 'Business or partnership inquiry', 'Press or media', 'Something else'] as $option)
              <option value="{{ $option }}" @selected(old('reason') === $option)>{{ $option }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="Tell us what's going on">{{ old('message') }}</textarea>
          @error('message') <p style="color:#C0392B; font-size:12.5px; margin-top:6px;">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-amber">Send message</button>
        <p class="form-note">Submissions are stored and logged. Connect a real mailbox in <code>config/mail.php</code> (via your <code>.env</code>) so these actually reach an inbox — until then they're saved to the database and to <code>storage/logs/laravel.log</code> only.</p>
      </form>

      <div class="side-card">
        <h3>Other ways to reach us</h3>
        <p>If you're already using the app, the fastest path for account-specific issues is usually inside PakaPay itself.</p>
        <div class="side-item">
          <svg class="ic" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="#FFB400" stroke-width="1.8"/><path d="M3 9h18" stroke="#FFB400" stroke-width="1.8"/></svg>
          <div>
            <p>In-app support</p>
            <span>Settings → Help, from inside the PakaPay app.</span>
          </div>
        </div>
        <div class="side-item">
          <svg class="ic" viewBox="0 0 24 24" fill="none"><path d="M12 2l7 4v6c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-4z" stroke="#FFB400" stroke-width="1.8" stroke-linejoin="round"/></svg>
          <div>
            <p>Security concerns</p>
            <span>If you suspect unauthorized access, freeze your device from Settings first, then reach out here.</span>
          </div>
        </div>
        <div class="side-item">
          <svg class="ic" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="#FFB400" stroke-width="1.8"/><path d="M9 12l2 2 4-4" stroke="#FFB400" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <div>
            <p>Legal &amp; privacy</p>
            <span>See our <a href="{{ route('privacy') }}" style="color:#FFB400; text-decoration:underline;">Privacy Policy</a> and <a href="{{ route('terms') }}" style="color:#FFB400; text-decoration:underline;">Terms of Service</a>.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
