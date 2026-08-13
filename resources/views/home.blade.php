@extends('layouts.marketing')

@section('title', 'PakaPay — Banking That Works Without Data')
@section('description', 'PakaPay is a mobile wallet built for Nigeria\'s markets. Send money by tap, scan, or offline dial — hardware-signed, even with zero data.')
@section('canonical', 'https://pakapay.ng/')

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "Organization", "name": "PakaPay", "url": "https://pakapay.ng/", "logo": "https://pakapay.ng/og-image.png", "parentOrganization": {"@type": "Organization", "name": "Payce Financial Technologies Ltd"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "SoftwareApplication", "name": "PakaPay", "applicationCategory": "FinanceApplication", "operatingSystem": "Android", "url": "https://pakapay.ng/", "description": "PakaPay is a mobile wallet built for Nigeria's markets. Send money by tap, scan, or offline dial \u2014 hardware-signed, even with zero data.", "offers": {"@type": "Offer", "price": "0", "priceCurrency": "NGN"}}</script>
@endpush

@push('jsonld')
<script type="application/ld+json">{"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": "Is my money safe with PakaPay?", "acceptedAnswer": {"@type": "Answer", "text": "Every transfer is signed by a hardware key that never leaves AWS's vault, and every action is written into a record that locks itself \u2014 altering one entry breaks everything after it. Accounts, devices, and the whole system can each be frozen independently if something looks wrong."}}, {"@type": "Question", "name": "What does PakaPay cost?", "acceptedAnswer": {"@type": "Answer", "text": "Fees vary by the type of transfer and are shown before you confirm any payment \u2014 nothing is deducted without you seeing the amount first."}}, {"@type": "Question", "name": "Is PakaPay a bank?", "acceptedAnswer": {"@type": "Answer", "text": "No \u2014 PakaPay is a wallet built by Payce Financial Technologies Ltd on top of the bank accounts you already have. You link one or more bank accounts, and PakaPay moves money between them and your PayPoints."}}, {"@type": "Question", "name": "Does the offline rail use my airtime or data?", "acceptedAnswer": {"@type": "Answer", "text": "No. The payment is encoded into a signed tone sequence and carried over a normal phone call, the same way any voice call works \u2014 no data bundle required."}}, {"@type": "Question", "name": "What if I lose my phone?", "acceptedAnswer": {"@type": "Answer", "text": "You can freeze the device on its own without freezing your whole account, so whoever has your phone can't move money while you sort out a replacement."}}, {"@type": "Question", "name": "Is PakaPay available on iPhone?", "acceptedAnswer": {"@type": "Answer", "text": "Not yet \u2014 PakaPay is on Android today, with iOS on the roadmap."}}]}</script>
@endpush

@section('content')
<section class="hero" id="main">
  <div class="wrap hero-grid" data-stagger>
    <div>
      <h1 class="reveal">Banking that keeps up, even where the <span class="accent">network</span> doesn't.</h1>
      <p class="lede reveal">PakaPay is a mobile wallet built for Nigeria's markets, not just its cities. Tap, scan, or — when there's no data at all — dial. Every transfer is signed by a hardware key and locked into a tamper-evident record, so speed never comes at the cost of security.</p>
      <div class="trust-row reveal">
        <span><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 2l7 4v6c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-4z" stroke="#03556A" stroke-width="2" stroke-linejoin="round"/></svg>Secured with AWS hardware keys</span>
        <span><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="#03556A" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke="#03556A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Works without data</span>
        <span><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="#03556A" stroke-width="2"/><path d="M3 9h18" stroke="#03556A" stroke-width="2"/></svg>Backed by Payce Financial Technologies</span>
      </div>
      <div class="cta-row reveal">
        <a href="#download" class="btn btn-amber">Download PakaPay</a>
        <a href="#features" class="btn btn-outline">See how it works</a>
      </div>
    </div>
    <div class="hero-photo reveal">
      <div class="app-mock">
        <div class="screen">
          <div class="punch-hole"></div>
          <div class="status-bar">
            <span>9:41</span>
            <div class="stat-icons" id="mockStatIcons">
              <span id="statNoSignal" style="display:none; font-size:10px; font-weight:700;">SOS</span>
              <span id="statConn" style="display:inline-flex; align-items:center; gap:5px;">
                <svg width="15" height="11" viewBox="0 0 16 12" fill="none"><rect x="0" y="8" width="3" height="4" rx="0.5" fill="#1B1F2A"/><rect x="4.5" y="5.5" width="3" height="6.5" rx="0.5" fill="#1B1F2A"/><rect x="9" y="3" width="3" height="9" rx="0.5" fill="#1B1F2A"/><rect x="13" y="0.5" width="3" height="11.5" rx="0.5" fill="#1B1F2A"/></svg>
                <svg width="14" height="11" viewBox="0 0 16 12" fill="none"><path d="M1 4.5a10 10 0 0114 0" stroke="#1B1F2A" stroke-width="1.4" stroke-linecap="round"/><path d="M3.3 7.2a6.5 6.5 0 019.4 0" stroke="#1B1F2A" stroke-width="1.4" stroke-linecap="round"/><circle cx="8" cy="10.3" r="1.2" fill="#1B1F2A"/></svg>
              </span>
              <svg width="19" height="11" viewBox="0 0 20 11" fill="none"><rect x="0.5" y="0.5" width="16" height="10" rx="2.5" stroke="#1B1F2A"/><rect x="17.3" y="3.3" width="1.4" height="4.4" rx="0.7" fill="#1B1F2A"/><rect x="2" y="2" width="12" height="7" rx="1" fill="#1B1F2A"/></svg>
              <span class="batt-pct">82%</span>
            </div>
          </div>
          <div class="slider-viewport">
            <div class="slider-track" id="mockTrack">

              <div class="slide" data-slide="send">
                <div class="app-bar">
                  <div class="tab-label">Send</div>
                  <div class="tab-sub">Scan a code or enter details</div>
                </div>
                <div class="viewfinder">
                  <div class="corner tl"></div><div class="corner tr"></div>
                  <div class="corner bl"></div><div class="corner br"></div>
                  <svg width="30" height="30" viewBox="0 0 24 24" fill="none"><path d="M3 7a2 2 0 012-2h2l1.5-2h7L17 5h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="#FFB400" stroke-width="1.6" stroke-linejoin="round"/><circle cx="12" cy="13" r="3.5" stroke="#FFB400" stroke-width="1.6"/></svg>
                </div>
                <p class="scan-hint">Point your camera at a PakaPay QR to pay instantly</p>
                <div class="from-row">
                  <div class="fl"><small>Paying from</small><span>Personal Account</span></div>
                  <span class="chev">›</span>
                </div>
                <button class="slide-link" data-jump="offline">No signal? Dial instead →</button>
              </div>

              <div class="slide" data-slide="offline">
                <div class="app-bar">
                  <div class="tab-label">Offline Pay</div>
                  <div class="tab-sub">No data needed — signed by device key</div>
                </div>
                <div class="dial-visual">
                  <div class="ring"></div>
                  <div class="ring r2"></div>
                  <div class="ring r3"></div>
                  <div class="core">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M6 3h4l2 5-2.5 1.5a11 11 0 005 5L16 12l5 2v4a2 2 0 01-2 2C10.5 20 4 13.5 4 5a2 2 0 012-2z" stroke="#FFB400" stroke-width="1.8" stroke-linejoin="round"/></svg>
                  </div>
                </div>
                <div class="dial-status">Dialing secure line…</div>
                <div class="dial-sub">Carrying a signed 31-digit payment code as tones — no data used</div>
                <div class="dial-waveform"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
                <div class="dial-meta">₦5,000 to <strong>Mama Ngozi's Stall</strong> · confirms the moment the call connects</div>
                <button class="slide-link" data-jump="send" style="margin-top:16px;">← Back to Send</button>
              </div>

              <div class="slide" data-slide="receive">
                <div class="app-bar">
                  <div class="tab-label">Receive</div>
                  <div class="tab-sub">Personal Account</div>
                </div>
                <div class="receive-card">
                  <div class="who">Your Personal Account QR</div>
                  <div class="qr"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQ4AAAEOCAIAAAD3027yAAAEt0lEQVR4nO3dwW3dOBRA0ThIjdPE1DNNpMYAmm2yoS5AM6Tkc7ZGvuQfX3DxROrjuq5vwJ3vu28AnkEqkEgFEqlAIhVIpAKJVCCRCiRSgeTHzY//+ffv3Mdf8+vnf4Ofzvy+40+e8b67OtP4u7KqQCIVSKQCiVQgkQokUoFEKpBIBRKpQHIzrR9bNwmesWu2Pb7uuqcEzvS+vw2rCiRSgUQqkEgFEqlAIhVIpAKJVCCRCiRT0/qbj142gd61X3x83Zm7mpnlnzkXH3vi34ZVBRKpQCIVSKQCiVQgkQokUoFEKpBIBZKF0/onet9zAO+b9O9iVYFEKpBIBRKpQCIVSKQCiVQgkQokUoHEtP7TzOwXX3eKvnn8Z7GqQCIVSKQCiVQgkQokUoFEKpBIBRKpQLJwWv/EOfG6yfe6/fFP9MS/DasKJFKBRCqQSAUSqUAiFUikAolUIJEKJFPT+vdNkXdZt7d+3XVnPvmJrCqQSAUSqUAiFUikAolUIJEKJFKBRCqQfFzXtfseXmLXfHrdLP+JO+DXsapAIhVIpAKJVCCRCiRSgUQqkEgFEqlAcrO3/onvUz9zPr1upn7mdXft2l/3N2lVgUQqkEgFEqlAIhVIpAKJVCCRCiRSgWThe+tvLrxspn7mufG7niF44ln3u77nMasKJFKBRCqQSAUSqUAiFUikAolUIJEKJNum9TMz13WT713nAeyaQI/tmsfP/Nt136RVBRKpQCIVSKQCiVQgkQokUoFEKpBIBZKb99afear8mXuv19n1hMGZ7zJYd90xqwokUoFEKpBIBRKpQCIVSKQCiVQgkQokN9P6dc48Vf6JTyeMfbWZ+pj31sNyUoFEKpBIBRKpQCIVSKQCiVQgkQok26b1T3TmXvN1PNnwO6sKJFKBRCqQSAUSqUAiFUikAolUIJEKJIeehH/mzvuxJ06vd52xP7br7ATTevgEUoFEKpBIBRKpQCIVSKQCiVQgkQokC/fWv+996mPv+3137ekf8956OJpUIJEKJFKBRCqQSAUSqUAiFUikAsm2vfVPPN191877sTO/5xlnPtlgVYFEKpBIBRKpQCIVSKQCiVQgkQokUoHEe+v/cOZM/cxz8r/ada0qkEgFEqlAIhVIpAKJVCCRCiRSgUQqkPy4+fEDd2aP7TpHfca6Ew5mnPk+gnWsKpBIBRKpQCIVSKQCiVQgkQokUoFEKpDcTOvHzpzI7pptj6+7650Cu04LeB+rCiRSgUQqkEgFEqlAIhVIpAKJVCCRCiRT0/qbjzYn/s2Z77w/87oz1t2zVQUSqUAiFUikAolUIJEKJFKBRCqQSAWShdP693ni9HrdUwK7nj/YdXaCVQUSqUAiFUikAolUIJEKJFKBRCqQSAUS0/o/7Dqvfvxvv9q+/Jnvaua9AONPtqpAIhVIpAKJVCCRCiRSgUQqkEgFEqlAsnBa/77z6nft6l63x31s1//gzHXX3bNVBRKpQCIVSKQCiVQgkQokUoFEKpBIBZKpaf0TT4YfW7ere5d197zrnPxdn2xVgUQqkEgFEqlAIhVIpAKJVCCRCiRSgeTjuq7d9wAPYFWBRCqQSAUSqUAiFUikAolUIJEKJFKB5H+4SIDEWhU4RQAAAABJRU5ErkJggg==" alt="QR code linking to pakapay.ng"></div>
                  <div class="hint">Generated fresh — this code expires after use</div>
                </div>
                <div class="balance-row">
                  <span class="label">Available balance</span>
                  <span class="amount">₦128,450.00</span>
                </div>
                <div class="activity-list">
                  <div class="activity-label">Recent activity</div>
                  <div class="activity-row">
                    <div class="av">AT</div>
                    <div class="ainfo"><div class="an">Amaka T.</div><div class="at">2 minutes ago</div></div>
                    <div class="aamt in">+₦2,500</div>
                  </div>
                  <div class="activity-row">
                    <div class="av">CO</div>
                    <div class="ainfo"><div class="an">Chidi O.</div><div class="at">1 hour ago</div></div>
                    <div class="aamt in">+₦1,800</div>
                  </div>
                  <div class="activity-row">
                    <div class="av">MB</div>
                    <div class="ainfo"><div class="an">MTN Airtime</div><div class="at">Yesterday</div></div>
                    <div class="aamt out">−₦500</div>
                  </div>
                </div>
              </div>

              <div class="slide" data-slide="settings">
                <div class="app-bar">
                  <div class="tab-label">Settings</div>
                  <div class="tab-sub">Manage accounts &amp; preferences</div>
                </div>
                <div class="settings-list">
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="#03556A" stroke-width="1.8"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6" stroke="#03556A" stroke-width="1.8" stroke-linecap="round"/></svg></span>Edit Profile</span><span class="chev">›</span></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M3 10l9-6 9 6" stroke="#03556A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 10v9M19 10v9M9 19v-6h6v6" stroke="#03556A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 19h18" stroke="#03556A" stroke-width="1.8" stroke-linecap="round"/></svg></span>Linked Bank Accounts</span><span class="chev">›</span></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#03556A" stroke-width="1.8"/><circle cx="12" cy="12" r="3" fill="#03556A"/></svg></span>Manage Payment Points</span><span class="chev">›</span></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><rect x="5" y="11" width="14" height="9" rx="2" stroke="#03556A" stroke-width="1.8"/><path d="M8 11V7a4 4 0 018 0v4" stroke="#03556A" stroke-width="1.8"/></svg></span>Change Security PIN</span><span class="chev">›</span></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 2a5 5 0 015 5v2M7 9V7a5 5 0 019.9-1M12 12v3M8 12v5a4 4 0 008 0v-2M6 12v4a6 6 0 0012 0v-1" stroke="#03556A" stroke-width="1.5" stroke-linecap="round"/></svg></span>Biometrics</span><div class="toggle"></div></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z" stroke="#03556A" stroke-width="1.8" stroke-linejoin="round"/></svg></span>Quick Pay Mode</span><div class="toggle"></div></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 3h4l2 5-2.5 1.5a11 11 0 005 5L16 12l5 2v4a2 2 0 01-2 2C10.5 20 4 13.5 4 5a2 2 0 012-2z" stroke="#03556A" stroke-width="1.8" stroke-linejoin="round"/></svg></span>Offline Payments</span><div class="toggle"></div></div>
                  <div class="settings-row"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#03556A" stroke-width="1.8"/><path d="M12 7.5V12l3 2" stroke="#03556A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Transaction History</span><span class="chev">›</span></div>
                  <div class="settings-row danger"><span class="sl"><span class="ic-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" stroke="#C0392B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17l5-5-5-5M21 12H9" stroke="#C0392B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Log Out</span></div>
                </div>
              </div>

            </div>
          </div>
          <div class="bottom-nav" id="mockNav">
            <button class="nav-item" data-target="send" aria-label="Send tab">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M22 2L11 13" stroke="#B1B8CC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="#B1B8CC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              <span>Send</span>
            </button>
            <button class="nav-item active" data-target="receive" aria-label="Receive tab">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke="#5A9AA5" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke="#5A9AA5" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke="#5A9AA5" stroke-width="2"/><path d="M14 14h3v3h-3zM19 14h2v2h-2zM14 19h2v2h-2zM19 19h2v2h-2z" fill="#5A9AA5"/></svg>
              <span>Receive</span>
            </button>
            <button class="nav-item" data-target="settings" aria-label="Settings tab">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="#B1B8CC" stroke-width="2"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9c.14.36.4.66.73.85.24.14.51.21.79.21H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z" stroke="#B1B8CC" stroke-width="1.6"/></svg>
              <span>Settings</span>
            </button>
          </div>
          <div class="gesture-pill"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="features" id="features">
  <div class="wrap">
    <div class="feature-group">
      <span class="feature-group-label">Three ways to pay</span>
      <div class="rails-row" data-stagger>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke="#03556A" stroke-width="1.8"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke="#03556A" stroke-width="1.8"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke="#03556A" stroke-width="1.8"/><path d="M14 14h3v3h-3zM19 14h2v2h-2zM14 19h2v2h-2zM19 19h2v2h-2z" fill="#03556A"/></svg>
          <div>
            <h3>Tap &amp; scan</h3>
            <p>One QR for every account and PayPoint you own.</p>
          </div>
        </div>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#03556A" stroke-width="1.8"/><circle cx="12" cy="12" r="3" fill="#03556A"/></svg>
          <div>
            <h3>PayPoints</h3>
            <p>A standing request for money, for your stall or your rent.</p>
          </div>
        </div>
        <div class="feature-card hi reveal">
          <div class="ico-wrap">
            <svg class="ico" viewBox="0 0 24 24" fill="none"><path d="M6 3h4l2 5-2.5 1.5a11 11 0 005 5L16 12l5 2v4a2 2 0 01-2 2C10.5 20 4 13.5 4 5a2 2 0 012-2z" stroke="#03556A" stroke-width="1.8" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h3>Offline dial</h3>
            <p>No data? Pay by placing a signed phone call instead. <a href="#offline-dial" style="text-decoration:underline; color:inherit;">See how →</a></p>
          </div>
        </div>
      </div>
    </div>

    <div class="feature-group">
      <span class="feature-group-label">Built-in protections</span>
      <div class="protections-row" data-stagger>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z" stroke="#03556A" stroke-width="1.8" stroke-linejoin="round"/></svg>
          <div>
            <h3>Quick Pay</h3>
            <p>Small, trusted transfers go straight through — no PIN prompt every time.</p>
          </div>
        </div>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><path d="M12 2a5 5 0 015 5v2M7 9V7a5 5 0 019.9-1M12 12v3M8 12v5a4 4 0 008 0v-2M6 12v4a6 6 0 0012 0v-1" stroke="#03556A" stroke-width="1.6" stroke-linecap="round"/></svg>
          <div>
            <h3>Biometric login</h3>
            <p>Unlock with a fingerprint instead of typing your PIN every time.</p>
          </div>
        </div>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2" stroke="#03556A" stroke-width="1.8"/><path d="M3 10h18M7 3v4M17 3v4" stroke="#03556A" stroke-width="1.8" stroke-linecap="round"/></svg>
          <div>
            <h3>Link multiple banks</h3>
            <p>Connect every account you have and move between them freely.</p>
          </div>
        </div>
        <div class="feature-card reveal">
          <svg class="ico" viewBox="0 0 24 24" fill="none"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9z" stroke="#03556A" stroke-width="1.8" stroke-linejoin="round"/><path d="M13.7 21a2 2 0 01-3.4 0" stroke="#03556A" stroke-width="1.8"/></svg>
          <div>
            <h3>Instant alerts</h3>
            <p>Know the moment money moves, in or out.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="panel amber">
  <div class="wrap panel-grid" data-stagger>
    <div class="reveal">
      <span class="section-label">Everyday money</span>
      <h2 class="panel-title">Send, receive, and collect — without switching apps.</h2>
      <p>Fund your wallet, move money to any bank, and connect a PayPoint for anything you collect regularly. Add one from a linked bank account, or connect to one shared with you — personal and business PayPoints keep separate books automatically.</p>
      <a href="{{ route('business') }}" class="btn btn-dark">See how PayPoints work</a>
    </div>
    <div class="rounded-img reveal">
      <img src="https://images.unsplash.com/photo-1575303093127-18b3c4ef8c41?fm=jpg&q=75&w=900&auto=format&fit=crop" alt="Trader holding tomatoes at a market stall">
    </div>
  </div>
</section>

<section class="panel" id="business">
  <div class="wrap panel-grid" data-stagger>
    <div class="rounded-img reveal" style="order:2;">
      <img src="https://images.unsplash.com/photo-1687422809654-579d81c29d32?fm=jpg&q=75&w=900&auto=format&fit=crop" alt="Trader at a market stall holding a phone">
    </div>
    <div class="reveal" style="order:1;">
      <span class="section-label" style="color:var(--amber-deep);">Built for real conditions</span>
      <h2 class="panel-title">Works even when the signal doesn't.</h2>
      <p>Every account and PayPoint gets its own QR code, so getting paid is just a scan away when you're online. And when you're not — PakaPay still works.</p>
      <a href="#offline-dial" class="btn btn-outline">See how offline pay works</a>
    </div>
  </div>
</section>

<section class="signature" id="offline-dial">
  <div class="wrap">
    <div class="sig-card reveal">
      <div class="sig-copy">
        <span class="mark">no signal? no problem.</span>
        <h3>Open PakaPay, tap Pay Offline, and place the call.</h3>
        <p>The tones carry a signed 31-digit payment code automatically — the transfer confirms the moment the call connects. No data used, start to finish.</p>
        <a href="#download" class="btn btn-amber">Download PakaPay</a>
      </div>
      <div class="sig-photo">
        <img src="https://images.unsplash.com/photo-1687422808565-929533931584?fm=jpg&q=75&w=700&auto=format&fit=crop" alt="Trader with a phone at a market stall">
      </div>
    </div>
  </div>
</section>

<section class="faq" id="faq">
  <div class="wrap">
    <span class="section-label">Common questions</span>
    <h2 class="section-title">Before you download</h2>
    <div class="faq-list" data-stagger>
      <details class="faq-item reveal" open>
        <summary>Is my money safe with PakaPay?<span class="plus">+</span></summary>
        <p>Every transfer is signed by a hardware key that never leaves AWS's vault, and every action is written into a record that locks itself — altering one entry breaks everything after it. Accounts, devices, and the whole system can each be frozen independently if something looks wrong.</p>
      </details>
      <details class="faq-item reveal">
        <summary>What does PakaPay cost?<span class="plus">+</span></summary>
        <p>Fees vary by the type of transfer and are shown before you confirm any payment — nothing is deducted without you seeing the amount first.</p>
      </details>
      <details class="faq-item reveal">
        <summary>Is PakaPay a bank?<span class="plus">+</span></summary>
        <p>No — PakaPay is a wallet built by Payce Financial Technologies Ltd on top of the bank accounts you already have. You link one or more bank accounts, and PakaPay moves money between them and your PayPoints.</p>
      </details>
      <details class="faq-item reveal">
        <summary>Does the offline rail use my airtime or data?<span class="plus">+</span></summary>
        <p>No. The payment is encoded into a signed tone sequence and carried over a normal phone call, the same way any voice call works — no data bundle required.</p>
      </details>
      <details class="faq-item reveal">
        <summary>What if I lose my phone?<span class="plus">+</span></summary>
        <p>You can freeze the device on its own without freezing your whole account, so whoever has your phone can't move money while you sort out a replacement.</p>
      </details>
      <details class="faq-item reveal">
        <summary>Is PakaPay available on iPhone?<span class="plus">+</span></summary>
        <p>Not yet — PakaPay is on Android today, with iOS on the roadmap.</p>
      </details>
    </div>
  </div>
</section>

<section class="security" id="security">
  <div class="wrap">
    <span class="section-label">Held to a higher bar</span>
    <h2 class="section-title">Your security is not an afterthought.</h2>
    <div class="sec-grid" data-stagger>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>Every payment is signed by a hardware key sitting inside AWS — not a password, not software. The key never leaves the vault.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>Every action writes into a record that locks itself. Change one line and everything after it stops matching.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>One account, one phone, or the whole system — any of the three can be frozen on its own the second something looks off.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>Idle 30 minutes, you're logged out. Stay in for 30 days straight, we ask again anyway.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>New accounts get checked twice — at signup, then by a one-time code — before they can hold a naira.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>Identity is confirmed against your BVN before higher spending limits unlock.</p></div>
      <div class="sec-item reveal"><div class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12l5 5L19 7" stroke="#03556A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p>If the signing service is unreachable, the payment fails. It doesn't quietly fall back to something weaker.</p></div>
    </div>
  </div>
</section>

<section class="download-strip" id="download">
  <div class="wrap ds-grid" data-stagger>
    <div class="ds-copy reveal">
      <span class="section-label">Get PakaPay</span>
      <h2>Set up takes a few minutes.</h2>
      <p>Verified twice, secured by hardware, ready whenever your network isn't.</p>
      <div class="store-badges" style="margin-top:20px;">
        <a href="#" class="store-badge">
          <svg viewBox="0 0 24 24" width="24" height="24"><path fill="#fff" d="M4.5 2.5c-.4.3-.6.8-.6 1.4v16.2c0 .6.2 1.1.6 1.4l.1.1L13.5 12l-9-9.5-.1.1z"/><path fill="#fff" d="M16.4 15L13.5 12l2.9-3 3.6 2.1c1 .6 1 1.6 0 2.1L16.4 15z"/><path fill="#fff" fill-opacity="0.75" d="M16.4 15l-2.9-3L4.5 21.5c.4.4 1 .4 1.7.1L16.4 15z"/><path fill="#fff" fill-opacity="0.75" d="M16.4 9L6.2 3.3c-.7-.3-1.3-.3-1.7.1L13.5 12l2.9-3z"/></svg>
          <span class="store-badge-text"><small>GET IT ON</small><strong>Google Play</strong></span>
        </a>
        <span class="store-badge disabled" aria-disabled="true" title="iOS is on the roadmap — not available yet">
          <svg viewBox="0 0 24 24" width="26" height="26" fill="#fff"><path d="M16.365 1.43c0 1.14-.415 2.02-1.244 2.65-.828.62-1.797.98-2.906.87-.03-1.03.44-2.06 1.244-2.75.4-.36.92-.66 1.55-.9.63-.24 1.19-.35 1.68-.34.02.16.03.31.03.47zm3.61 15.98c-.5 1.09-.74 1.57-1.38 2.53-.9 1.33-2.16 2.99-3.73 3.01-1.4.01-1.76-.9-3.66-.89-1.9.01-2.3.9-3.7.89-1.57-.02-2.76-1.51-3.66-2.84-2.5-3.68-2.77-8.01-1.22-10.31 1.1-1.63 2.83-2.58 4.46-2.58 1.66 0 2.7.91 4.07.91 1.33 0 2.14-.91 4.06-.91 1.45 0 3 .79 4.09 2.16-3.6 1.97-3.02 7.09.67 8.03z"/></svg>
          <span class="store-badge-text"><small>Coming soon on</small><strong>App Store</strong></span>
        </span>
      </div>
    </div>
    <div class="rounded-img reveal">
      <img src="https://images.unsplash.com/photo-1622556498246-755f44ca76f3?fm=jpg&q=75&w=900&auto=format&fit=crop" alt="Person holding a smartphone">
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  // Stagger each group's reveal children so they cascade in rather than pop together
  document.querySelectorAll('[data-stagger]').forEach(function(group){
    group.querySelectorAll('.reveal').forEach(function(el, i){
      el.style.transitionDelay = (i * 90) + 'ms';
    });
  });

  // Reveal on scroll into view; hero content is already in view on load so it
  // animates in immediately without waiting for a scroll event.
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var hasIO = 'IntersectionObserver' in window;
  if (reduceMotion || !hasIO){
    document.querySelectorAll('.reveal').forEach(function(el){ el.classList.add('in'); });
  } else {
    var observer = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting){
          entry.target.classList.add('in');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    document.querySelectorAll('.reveal').forEach(function(el){ observer.observe(el); });

    // Safety net: some in-app browsers (Facebook, Opera Mini) have unreliable
    // IntersectionObserver timing. If content hasn't revealed itself shortly
    // after load, force it visible rather than leaving it permanently hidden.
    window.addEventListener('load', function(){
      setTimeout(function(){
        document.querySelectorAll('.reveal:not(.in)').forEach(function(el){
          var rect = el.getBoundingClientRect();
          if (rect.top < window.innerHeight){ el.classList.add('in'); }
        });
      }, 1200);
    });
  }

  // Header gains a stronger shadow once the page has scrolled past the hero
  var header = document.querySelector('header');
  window.addEventListener('scroll', function(){
    header.classList.toggle('scrolled', window.scrollY > 12);
  }, { passive: true });

  // Mobile hamburger menu
  var burger = document.getElementById('navBurger');
  var mobileNav = document.getElementById('mobileNav');
  if (burger && mobileNav){
    burger.addEventListener('click', function(){
      var isOpen = header.classList.toggle('nav-open');
      burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    mobileNav.querySelectorAll('a').forEach(function(link){
      link.addEventListener('click', function(){
        header.classList.remove('nav-open');
        burger.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // Hero app-mockup slider: Send / Offline / Receive / Settings
  var mockTrack = document.getElementById('mockTrack');
  var mockNav = document.getElementById('mockNav');
  if (mockTrack && mockNav){
    var order = ['send', 'offline', 'receive', 'settings'];
    // Offline is reached from within Send (matches the real app: it's a
    // fallback state, not its own bottom-nav tab) — so the Send button
    // stays highlighted while the offline-dial slide is showing.
    var navMap = { send:'send', offline:'send', receive:'receive', settings:'settings' };
    var mockIndex = 2; // start on Receive
    var mockTimer;

    function goToSlide(i){
      mockIndex = i;
      mockTrack.style.transform = 'translateX(-' + (i * 25) + '%)';
      var activeNav = navMap[order[i]];
      mockNav.querySelectorAll('.nav-item').forEach(function(btn){
        btn.classList.toggle('active', btn.dataset.target === activeNav);
      });
      var isOffline = order[i] === 'offline';
      var statConn = document.getElementById('statConn');
      var statNoSignal = document.getElementById('statNoSignal');
      if (statConn && statNoSignal){
        statConn.style.display = isOffline ? 'none' : 'inline-flex';
        statNoSignal.style.display = isOffline ? 'inline' : 'none';
      }
    }

    function goToSlideName(name){
      var i = order.indexOf(name);
      if (i > -1) goToSlide(i);
    }

    function startAutoplay(){
      if (reduceMotion) return;
      clearInterval(mockTimer);
      mockTimer = setInterval(function(){
        goToSlide((mockIndex + 1) % order.length);
      }, 4200);
    }

    mockNav.querySelectorAll('.nav-item').forEach(function(btn){
      btn.addEventListener('click', function(){
        goToSlideName(btn.dataset.target);
        startAutoplay(); // reset the timer on manual interaction
      });
    });

    mockTrack.querySelectorAll('[data-jump]').forEach(function(link){
      link.addEventListener('click', function(){
        goToSlideName(link.dataset.jump);
        startAutoplay();
      });
    });

    goToSlide(mockIndex);
    startAutoplay();
  }
</script>@endsection
