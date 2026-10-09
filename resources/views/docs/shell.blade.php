<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }} — PakaPay Developer Docs</title>
<meta name="description" content="PakaPay developer documentation — mobile API, payments, payment points, KYC, offline (voice) payments, webhooks and the security model.">
<link rel="canonical" href="{{ url()->current() }}">

<meta name="theme-color" content="#03556A">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/docs.css') }}">
</head>
<body>

@php
  $icons = [
    'home'   => '<path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>',
    'wallet' => '<rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/><circle cx="17" cy="14" r="1"/>',
    'bolt'   => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>',
    'school' => '<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.5 2.5 3 6 3s6-1.5 6-3v-5"/>',
    'store'  => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/>',
    'link'   => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>',
    'meter'  => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 2"/>',
    'user'   => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 016-6h4a6 6 0 016 6v1"/>',
    'shield' => '<path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5l8-3z"/><path d="M9 12l2 2 4-4"/>',
    'phone'  => '<path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/>',
    'cog'    => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>',
    'bell'   => '<path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
  ];
  $sections = config('docs.sections');
  $standalone = config('docs.standalone', []);
@endphp

<header class="docs-topbar">
  <button class="docs-ham" id="docsHam" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
  <a href="{{ route('docs.index') }}" class="docs-logo">
    <img src="{{ asset('favicon.png') }}" alt="PakaPay" class="docs-logo-mark">
    docs
  </a>
  <div class="docs-search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="docsSearch" placeholder="Search documentation" autocomplete="off">
  </div>
  <div class="docs-topbar-right">
    <button type="button" class="docs-token-btn" id="docsTokenBtn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-9.6 9.6"/><circle cx="7.5" cy="16.5" r="5.5"/></svg>
      <span id="docsTokenLabel">Set Bearer Token</span>
    </button>
    <a href="{{ route('home') }}">&larr; pakapay.ng</a>
    <a href="{{ route('contact') }}" class="docs-btn-signup">Contact us</a>
  </div>

  <div class="docs-token-panel" id="docsTokenPanel">
    <div class="docs-token-panel-inner">
      <label for="docsTokenInput">Bearer token</label>
      <p>Used by every "Try It" panel across these docs to call <code>{{ parse_url(config('docs.api_base'), PHP_URL_HOST) }}</code> for real. Get one from <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'authentication']) }}">Authentication</a> — log in (or finish registration), then paste the <code>data.token</code> value here. Stored only in this browser (localStorage), never sent anywhere but the PakaPay API, and auto-cleared after 8 hours.</p>
      <div class="docs-token-row">
        <input type="password" id="docsTokenInput" placeholder="1|k3j2h4g5f6...">
        <button type="button" id="docsTokenSave">Save</button>
        <button type="button" id="docsTokenClear">Clear</button>
      </div>
    </div>
  </div>
</header>

<div class="docs-body">

  <nav class="docs-sidebar" id="docsSidebar">
    <a href="{{ route('home') }}" class="docs-nav-home">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
      Home
    </a>

    @foreach ($sections as $slug => $data)
      @php $isActive = $section === $slug; @endphp
      <div class="docs-section {{ $isActive ? 'active open' : '' }}" data-nav-section>
        <button type="button" class="docs-section-btn" data-toggle-section>
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$data['icon']] ?? '' !!}</svg>
          {{ $data['title'] }}
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 6 15 12 9 18"/></svg>
        </button>
        <ul class="docs-subpages">
          @foreach ($data['pages'] as $pageSlug => $pageTitle)
            <li>
              <a href="{{ route('docs.show', ['section' => $slug, 'page' => $pageSlug]) }}"
                 class="{{ $isActive && $page === $pageSlug ? 'active' : '' }}">{{ $pageTitle }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach

    <hr class="docs-sidebar-divider">
    <div class="docs-standalone">
      @foreach ($standalone as $slug => $label)
        <a href="{{ route('docs.show', ['section' => $slug, 'page' => $slug]) }}" class="{{ $page === $slug ? 'active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>
  </nav>

  <main class="docs-content-col">
    <article class="docs-content">
      @include($contentView)

      @if($prev || $next)
      <div class="docs-pagenav">
        @if($prev)
        <a href="{{ route('docs.show', ['section' => $prev['section'], 'page' => $prev['page']]) }}">
          <div class="docs-pagenav-label">&larr; Previous</div>
          <div class="docs-pagenav-title">{{ $prev['title'] }}</div>
        </a>
        @else
        <div></div>
        @endif
        @if($next)
        <a href="{{ route('docs.show', ['section' => $next['section'], 'page' => $next['page']]) }}" class="docs-pagenav-next">
          <div class="docs-pagenav-label">Next &rarr;</div>
          <div class="docs-pagenav-title">{{ $next['title'] }}</div>
        </a>
        @endif
      </div>
      @endif
    </article>
  </main>

  <aside class="docs-toc-col">
    <div class="docs-toc">
      <div class="docs-toc-label">On this page</div>
      <ul id="docsToc"></ul>
    </div>
  </aside>

</div>

<script>
// Mobile sidebar toggle
(function(){
  var ham = document.getElementById('docsHam');
  if (ham) ham.addEventListener('click', function(){ document.body.classList.toggle('docs-nav-open'); });
  document.addEventListener('click', function(e){
    if (document.body.classList.contains('docs-nav-open') && !e.target.closest('.docs-sidebar') && !e.target.closest('#docsHam')) {
      document.body.classList.remove('docs-nav-open');
    }
  });
})();

// Bearer token: stored in localStorage, shared by every Try It panel
// on every docs page. Auto-expires after 8 hours so a token left on a
// shared/public computer doesn't sit there indefinitely.
(function(){
  var TOKEN_KEY = 'pakapay_docs_bearer_token';
  var EXPIRY_MS = 8 * 60 * 60 * 1000; // 8 hours
  var btn = document.getElementById('docsTokenBtn');
  var label = document.getElementById('docsTokenLabel');
  var panel = document.getElementById('docsTokenPanel');
  var input = document.getElementById('docsTokenInput');
  var saveBtn = document.getElementById('docsTokenSave');
  var clearBtn = document.getElementById('docsTokenClear');
  if (!btn) return;

  function readStore() {
    try {
      var raw = localStorage.getItem(TOKEN_KEY);
      if (!raw) return null;
      var parsed = JSON.parse(raw);
      if (!parsed || !parsed.token || !parsed.savedAt) return null;
      if (Date.now() - parsed.savedAt > EXPIRY_MS) {
        localStorage.removeItem(TOKEN_KEY);
        return null;
      }
      return parsed;
    } catch (e) {
      localStorage.removeItem(TOKEN_KEY);
      return null;
    }
  }
  function getToken() {
    var s = readStore();
    return s ? s.token : '';
  }
  function refreshLabel() {
    var s = readStore();
    if (s) {
      var hoursLeft = Math.max(1, Math.round((EXPIRY_MS - (Date.now() - s.savedAt)) / 3600000));
      label.textContent = 'Token Set (expires in ' + hoursLeft + 'h)';
      btn.classList.add('is-set');
      input.value = s.token;
    } else {
      label.textContent = 'Set Bearer Token';
      btn.classList.remove('is-set');
    }
  }
  refreshLabel();
  setInterval(refreshLabel, 5 * 60 * 1000); // keep the "expires in Xh" label honest

  btn.addEventListener('click', function(e){
    e.stopPropagation();
    panel.classList.toggle('open');
    if (panel.classList.contains('open')) input.focus();
  });
  document.addEventListener('click', function(e){
    if (panel.classList.contains('open') && !e.target.closest('.docs-token-panel') && !e.target.closest('#docsTokenBtn')) {
      panel.classList.remove('open');
    }
  });
  saveBtn.addEventListener('click', function(){
    var val = input.value.trim();
    if (val) {
      localStorage.setItem(TOKEN_KEY, JSON.stringify({ token: val, savedAt: Date.now() }));
    } else {
      localStorage.removeItem(TOKEN_KEY);
    }
    refreshLabel();
    panel.classList.remove('open');
    document.querySelectorAll('[data-try-authnotice]').forEach(function(n){ n.hidden = !!getToken(); });
  });
  clearBtn.addEventListener('click', function(){
    localStorage.removeItem(TOKEN_KEY);
    input.value = '';
    refreshLabel();
  });
  document.addEventListener('click', function(e){
    if (e.target.closest('[data-open-token]')) {
      btn.click();
    }
  });

  window.__pakapayDocsGetToken = getToken;
})();

// Try It panels: send real requests to the live PakaPay API.
(function(){
  var API_BASE = @json(rtrim(config('docs.api_base'), '/'));

  document.querySelectorAll('.try-it').forEach(function(panelEl){
    var toggleBtn = panelEl.querySelector('[data-try-toggle]');
    var body = panelEl.querySelector('.try-it-body');
    var sendBtn = panelEl.querySelector('[data-try-send]');
    var responseEl = panelEl.querySelector('[data-try-response]');
    var statusEl = panelEl.querySelector('[data-try-status]');
    var timeEl = panelEl.querySelector('[data-try-time]');
    var bodyCodeEl = panelEl.querySelector('[data-try-body]');
    var authNotice = panelEl.querySelector('[data-try-authnotice]');
    var requiresAuth = panelEl.dataset.auth === '1';
    var isHighStakes = panelEl.dataset.highStakes === '1';
    var confirmInput = panelEl.querySelector('[data-try-confirm-input]');

    if (authNotice) authNotice.hidden = !requiresAuth || !!window.__pakapayDocsGetToken();

    if (isHighStakes && confirmInput) {
      confirmInput.addEventListener('input', function(){
        sendBtn.disabled = confirmInput.value.trim() !== 'CONFIRM';
      });
    }

    toggleBtn.addEventListener('click', function(){
      var isOpen = panelEl.classList.toggle('open');
      body.hidden = !isOpen;
      if (isOpen && authNotice) authNotice.hidden = !requiresAuth || !!window.__pakapayDocsGetToken();
    });

    sendBtn.addEventListener('click', function(){
      var method = panelEl.dataset.method;
      var path = panelEl.dataset.path;
      var token = window.__pakapayDocsGetToken ? window.__pakapayDocsGetToken() : '';

      if (requiresAuth && !token) {
        if (authNotice) authNotice.hidden = false;
        return;
      }

      var fields = panelEl.querySelectorAll('[data-field-name]');
      var pathParams = {}, queryParams = {}, bodyParams = {}, headerParams = {};
      var hasError = false;

      fields.forEach(function(input){
        var name = input.dataset.fieldName;
        var loc = input.dataset.fieldIn;
        var val = input.value;
        var field = input.closest('.try-it-field');
        if (input.hasAttribute('data-required') && !val.trim()) {
          if (field) field.classList.add('has-error');
          hasError = true;
          return;
        }
        if (field) field.classList.remove('has-error');
        if (loc === 'path') pathParams[name] = val;
        else if (loc === 'query') { if (val !== '') queryParams[name] = val; }
        else if (loc === 'header') { if (val !== '') headerParams[name] = val; }
        else if (val !== '' || input.hasAttribute('data-required')) {
          var ft = input.dataset.fieldType;
          bodyParams[name] = ft === 'boolean' ? val === 'true' : (ft === 'number' && val !== '' ? Number(val) : val);
        }
      });

      if (hasError) return;

      var finalPath = path;
      Object.keys(pathParams).forEach(function(k){
        finalPath = finalPath.replace('{' + k + '}', encodeURIComponent(pathParams[k]));
      });

      var url = API_BASE + finalPath;
      var qs = new URLSearchParams(queryParams).toString();
      if (qs) url += '?' + qs;

      var headers = { 'Accept': 'application/json' };
      Object.keys(headerParams).forEach(function(k){ headers[k] = headerParams[k]; });
      if (requiresAuth) headers['Authorization'] = 'Bearer ' + token;
      var fetchOpts = { method: method, headers: headers };
      if (method !== 'GET' && Object.keys(bodyParams).length) {
        headers['Content-Type'] = 'application/json';
        fetchOpts.body = JSON.stringify(bodyParams);
      }

      sendBtn.disabled = true;
      sendBtn.textContent = 'Sending...';
      var start = performance.now();

      fetch(url, fetchOpts)
        .then(function(res){
          var elapsed = Math.round(performance.now() - start);
          return res.json().catch(function(){ return {}; }).then(function(data){
            return { status: res.status, ok: res.ok, data: data, elapsed: elapsed };
          });
        })
        .then(function(result){
          responseEl.hidden = false;
          statusEl.textContent = result.status + (result.ok ? ' OK' : ' Error');
          statusEl.className = result.ok ? 'ok' : 'err';
          timeEl.textContent = result.elapsed + 'ms';
          bodyCodeEl.textContent = JSON.stringify(result.data, null, 2);
        })
        .catch(function(err){
          responseEl.hidden = false;
          statusEl.textContent = 'Network Error';
          statusEl.className = 'err';
          timeEl.textContent = '';
          bodyCodeEl.textContent = 'Request failed before a response came back — most likely a CORS restriction ({{ parse_url(config('docs.api_base'), PHP_URL_HOST) }} must allow CORS from this origin) or a connectivity issue, not necessarily an API error.\n\n' + err;
        })
        .finally(function(){
          if (isHighStakes && confirmInput) {
            confirmInput.value = '';
            sendBtn.disabled = true;
          } else {
            sendBtn.disabled = false;
          }
          sendBtn.textContent = panelEl.dataset.mutating === '1' ? 'Confirm & Send Real Request' : 'Send Request';
        });
    });
  });
})();

// Expand/collapse sidebar sections
document.querySelectorAll('[data-toggle-section]').forEach(function(btn){
  btn.addEventListener('click', function(){
    btn.closest('[data-nav-section]').classList.toggle('open');
  });
});

// Client-side search filter over sidebar links
(function(){
  var input = document.getElementById('docsSearch');
  if (!input) return;
  input.addEventListener('input', function(){
    var q = input.value.trim().toLowerCase();
    document.querySelectorAll('[data-nav-section]').forEach(function(sectionEl){
      var matches = 0;
      sectionEl.querySelectorAll('.docs-subpages li').forEach(function(li){
        var hit = li.textContent.toLowerCase().indexOf(q) !== -1;
        li.style.display = (q === '' || hit) ? '' : 'none';
        if (hit) matches++;
      });
      if (q !== '') sectionEl.classList.add('open');
      sectionEl.style.display = (q === '' || matches > 0) ? '' : 'none';
    });
  });
})();

// Build "On this page" from h2/h3 in the content, with scrollspy
(function(){
  var content = document.querySelector('.docs-content');
  var tocList = document.getElementById('docsToc');
  if (!content || !tocList) return;
  var headings = content.querySelectorAll('h2, h3');
  if (!headings.length) { document.querySelector('.docs-toc-col').style.display = 'none'; return; }

  var links = [];
  headings.forEach(function(h, i){
    if (!h.id) h.id = 'section-' + i;
    var li = document.createElement('li');
    if (h.tagName === 'H3') li.className = 'toc-h3';
    var a = document.createElement('a');
    a.href = '#' + h.id;
    a.textContent = h.textContent;
    li.appendChild(a);
    tocList.appendChild(li);
    links.push({ el: h, link: a });
  });

  var observer = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      var match = links.find(function(l){ return l.el === entry.target; });
      if (!match) return;
      if (entry.isIntersecting) {
        links.forEach(function(l){ l.link.classList.remove('active'); });
        match.link.classList.add('active');
      }
    });
  }, { rootMargin: '-80px 0px -70% 0px' });

  headings.forEach(function(h){ observer.observe(h); });
})();

// Copy-to-clipboard on code blocks
document.querySelectorAll('.docs-code-copy').forEach(function(btn){
  btn.addEventListener('click', function(){
    var code = btn.closest('.docs-code').querySelector('pre').textContent;
    navigator.clipboard.writeText(code).then(function(){
      var original = btn.textContent;
      btn.textContent = 'Copied!';
      setTimeout(function(){ btn.textContent = original; }, 1500);
    });
  });
});
</script>

</body>
</html>
