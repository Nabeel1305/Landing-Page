<div class="docs-eyebrow">Getting Started</div>
<h1>Authentication</h1>

<p>Every API call carries an <strong>API key</strong> as a bearer token:</p>
<x-docs.code lang="text">Authorization: Bearer opk_1a2b3c4d_9f3c6a1e6f4a4b509d3e3a1f1c2b9e1087ab12cd</x-docs.code>

<h2 id="key-format">Key format</h2>
<p>Keys look like <code class="inline">opk_&lt;8 hex&gt;_&lt;40 hex&gt;</code>. The first part after <code class="inline">opk_</code> is a lookup prefix you can safely show in your own screens ("key 1a2b3c4d"); the rest is the secret. Keys carry 160 bits of randomness and only a SHA-256 hash is stored, so a key <strong>cannot be recovered</strong> — if you lose it, create a new one and revoke the old one.</p>

<h2 id="getting-keys">Getting and managing keys</h2>
<ul>
  <li>The platform operator gives you the <strong>first key</strong> when your organisation is created.</li>
  <li>After that, owners and developers create and revoke keys in the portal under <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'developers']) }}">Developer Tools</a>. A key is displayed once, at creation.</li>
  <li>Use <strong>one key per system</strong> (e.g. "production core banking", "staging"), so you can revoke one without stopping the others.</li>
  <li>Revoking is immediate: the next request with that key gets <code class="inline">401</code>.</li>
</ul>

<h2 id="scope">What a key can do</h2>
<p>A key acts as your <strong>whole organisation</strong>: it can read and write only your subscribers, merchants, codes, transactions and webhook endpoints, and never anyone else's. There are no per-key permissions. Keys are for servers — never put one in a mobile app or web page.</p>

<x-docs.callout type="warn" title="Browser access">
  <p>The API sends permissive CORS headers by default so you can experiment from a browser (including the "Try it" panels in these docs). A key in front-end code is visible to anyone who opens developer tools. In production, call the API from your backend.</p>
</x-docs.callout>

<h2 id="failures">Authentication failures</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Status</th><th><code class="inline">error.code</code></th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td>401</td><td><code class="inline">unauthenticated</code></td><td>Missing, malformed, wrong or revoked key. The same answer for all four, so keys can't be probed.</td></tr>
    <tr><td>403</td><td><code class="inline">tenant_suspended</code></td><td>The key is valid but your organisation is suspended. Contact PakaPay support.</td></tr>
    <tr><td>429</td><td><code class="inline">too_many_attempts</code></td><td>Too many requests with an invalid key from one address (30 per minute). Back off.</td></tr>
  </tbody>
</table></div>

<h2 id="test-your-key">Test your key</h2>
<p>There is no "who am I" endpoint. A cheap check is to read a code that does not exist: a <code class="inline">404</code> means the key was accepted, a <code class="inline">401</code> means it wasn't.</p>
<x-docs.code lang="bash">curl -i {{ config('docs.api_base') }}/codes/00000000-0000-0000-0000-000000000000 \
  -H "Authorization: Bearer $KEY"      # 404 = key OK</x-docs.code>
<x-docs.try-it method="GET" path="/codes/{id}" :mutating="false" :fields="[
    ['name' => 'id', 'in' => 'path', 'required' => true, 'default' => '00000000-0000-0000-0000-000000000000'],
]" />
