<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>Voice Numbers &amp; Routing</h1>

<p>An incoming call must be matched to the right organisation. There are two ways, chosen per organisation by the platform operator (<strong>voice mode</strong>).</p>

<h2 id="own">Own number</h2>
<p>The organisation has a phone number of its own. Any call to that number belongs to that organisation, and codes are the plain digits. This is the cleanest experience: the payer sees <em>your</em> number, and you can brand it.</p>

<h2 id="shared">Shared pool</h2>
<p>Several organisations share a number. To tell them apart, every code starts with the organisation's fixed <strong>4-digit short code</strong> (for example <code class="inline">4821</code>), followed by the random digits (<code class="inline">4821</code> + <code class="inline">93771043…</code>). When a call comes in on a shared number, the first four digits keyed select the organisation. Calls whose first four digits match nobody are ignored.</p>
<ul>
  <li>The short code is already part of the <code class="inline">code</code> returned by <code class="inline">POST /codes</code>; show it as returned.</li>
  <li>An organisation's mode can only be changed by an operator when it has <strong>no open codes</strong>, otherwise codes already out would be stranded.</li>
</ul>

<h2 id="security">Number security</h2>
<ul>
  <li>Each number has its own secret <strong>callback token</strong> in the callback URL. The telephony provider doesn't sign callbacks, so this token is what authenticates them. Only a hash is stored; it is shown once when the number is added or the token rotated.</li>
  <li>Bad tokens are limited per source address (30/minute). Valid traffic is limited <strong>per number</strong> (600/minute), because all provider traffic arrives from the same few addresses and a per-address limit would throttle everyone.</li>
  <li>Disabling a number takes it out of service at once.</li>
</ul>
<x-docs.callout type="warn" title="Keep the token out of your logs">
  <p>The token travels in the callback URL's query string, and the dialled digits arrive in the request. Exclude the query string and <code class="inline">dtmfDigits</code> from access logs, APMs and error trackers.</p>
</x-docs.callout>

<h2 id="who-manages">Who manages numbers</h2>
<p>Adding numbers, rotating tokens and switching voice mode are <strong>operator</strong> tasks (<a href="{{ route('docs.show', ['section' => 'operators', 'page' => 'console']) }}">Operator Console</a>). In the portal you can see which numbers are assigned to you. Ask your PakaPay contact for changes.</p>
