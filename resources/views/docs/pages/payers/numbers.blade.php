<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>Voice Numbers &amp; Routing</h1>

<p>An incoming call must be matched to the right organisation. There are two ways, chosen per organisation by PakaPay (<strong>voice mode</strong>).</p>

<h2 id="own">Own number</h2>
<p>The organisation has a phone number of its own. Any call to that number belongs to that organisation, and codes are the plain digits. This is the cleanest experience: the payer sees <em>your</em> number, and you can brand it.</p>

<h2 id="shared">Shared pool</h2>
<p>Several organisations share a number. To tell them apart, every code starts with the organisation's fixed <strong>4-digit short code</strong> (for example <code class="inline">4821</code>), followed by the random digits (<code class="inline">4821</code> + <code class="inline">93771043…</code>). When a call comes in on a shared number, the first four digits keyed select the organisation. Calls whose first four digits match nobody are ignored.</p>
<ul>
  <li>The short code is already part of the <code class="inline">code</code> returned by <code class="inline">POST /codes</code>; show it as returned.</li>
  <li>An organisation's mode can only be changed by PakaPay when it has <strong>no open codes</strong>, otherwise codes already out would be stranded.</li>
</ul>

<h2 id="setup">Setting it up</h2>
<p>Voice numbers, their provider credentials and the own/shared choice are configured for you by PakaPay — ask your contact. Once a number is assigned, your integration needs nothing more than the <code class="inline">code</code> returned by <code class="inline">POST /codes</code> and the number to show the payer.</p>
