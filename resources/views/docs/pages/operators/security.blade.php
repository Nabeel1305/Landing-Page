<div class="docs-eyebrow">Operators</div>
<h1>Security Model</h1>

<h2 id="codes">Codes</h2>
<ul>
  <li>Stored only as an <strong>HMAC-SHA256 hash</strong> keyed with a server secret (the pepper) — a database leak reveals no live code, and a bare hash of a 12-digit code couldn't be brute-forced offline without the key.</li>
  <li>The idempotency table stores response bodies <strong>encrypted</strong> (they contain the code), and prunes rows after 24 hours.</li>
  <li>Single use: a code is claimed under a row lock; twelve simultaneous calls on one code produce exactly one payment; cancel racing redeem always ends in one clean state.</li>
  <li>Guessing is bounded by per-caller (5 failures / 10 min) and per-tenant (120 / min) limits, not by the code space (10<sup>12</sup>) alone. Alert on spikes of rejected redemptions.</li>
</ul>

<h2 id="voice">Voice callbacks</h2>
<ul>
  <li>Authenticated by number + secret URL token (stored as a hash; rotatable). Telephony providers don't sign callbacks, so scrub tokens from logs and ask the provider about IP allow-listing.</li>
  <li>Every failure sounds identical to the caller — no oracle.</li>
  <li><strong>Caller ID can be spoofed.</strong> With binding off only the code protects a payment; with it on, a spoofer who also has the code can pass. Keep codes short-lived and prefer binding.</li>
</ul>

<h2 id="isolation">Tenant isolation</h2>
<ul>
  <li>Every tenant-owned model carries a global scope pinned to the current tenant (set from the API key, the voice gateway, a queued job or the signed-in portal user). A test fails the build if a table with a <code class="inline">tenant_id</code> has neither a scoped model nor a documented exemption.</li>
  <li>It is enforced in application code. Review any raw SQL or <code class="inline">withoutGlobalScopes()</code> (today only the sweeps, the operator console and sign-in lookups). Banks with strict requirements can use a dedicated deployment.</li>
  <li>The portal adds per-role abilities on top; tenants cannot reach each other's ids.</li>
</ul>

<h2 id="webhooks">Webhooks</h2>
<ul>
  <li>HMAC-SHA256 over <code class="inline">"&lt;t&gt;.&lt;body&gt;"</code> with a per-endpoint secret stored encrypted; five-minute tolerance defeats replay.</li>
  <li><strong>SSRF defences:</strong> https and public addresses only, re-checked before each delivery; the connection is pinned to the address that was checked; redirects aren't followed.</li>
</ul>

<h2 id="audit">Audit chain</h2>
<p>One append-only, hash-chained log per tenant. Writers take turns on a dedicated head row, so parallel events can't fork the chain; metadata is stored as text so MySQL's JSON key re-ordering can't cause false alarms. <code class="inline">php artisan audit:verify</code> (or the buttons in the consoles) detects tampering.</p>
<x-docs.callout type="warn" title="Defence in depth">
  <p>The model refuses updates and deletes, but anyone with database write access can still edit rows (the chain would reveal it, not prevent it). <strong>Revoke UPDATE and DELETE on <code class="inline">audit_logs</code></strong> for the application's database user.</p>
</x-docs.callout>

<h2 id="humans">Operators &amp; tenant staff</h2>
<ul>
  <li>Passwords hashed; sign-in rate-limited per email + address with identical errors and equal timing for unknown users.</li>
  <li><strong>Two-factor</strong> (RFC 6238, replay-protected, five-attempt lockout, five-minute window between steps) is mandatory in production for both populations; it can't be disabled from a dashboard, only reset by an authorised person.</li>
  <li>Invitation links are random, hashed at rest, single-use and expire in 7 days.</li>
  <li>Every portal and console action that changes something is written to the tenant's audit chain.</li>
</ul>

<h2 id="headers">Web headers</h2>
<p>Dashboards send a strict Content-Security-Policy (<code class="inline">default-src 'self'</code>, no inline scripts, <code class="inline">frame-ancestors 'none'</code>), <code class="inline">X-Frame-Options: DENY</code>, <code class="inline">X-Content-Type-Options: nosniff</code>, <code class="inline">Referrer-Policy: no-referrer</code>, plus HSTS over https in production. CORS applies only to <code class="inline">/api/*</code>, never to the dashboards.</p>

<h2 id="open-items">Known open items</h2>
<p>Concurrency was tested on a development MariaDB, not production hardware; a real settlement adapter is not yet written or reviewed; retention periods are not agreed per tenant. An independent review should still test the running deployment, the telephony integration end to end (including a real call) and the first real adapter.</p>
