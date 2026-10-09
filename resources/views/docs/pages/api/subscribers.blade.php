<div class="docs-eyebrow">API Reference</div>
<h1>Subscribers</h1>

<p>A <strong>subscriber</strong> is a payer — one of your customers. You identify them with your own <code class="inline">reference</code> (a customer id, account holder id, wallet id…). The platform stores the reference and, optionally, a phone number; it never needs their name, balance or identity documents.</p>

<x-docs.callout type="note" title="You verify your customers">
  <p>The platform trusts the subscribers you register. Run KYC and any sanctions or fraud checks <em>before</em> you register someone or issue them a code.</p>
</x-docs.callout>

<h2 id="upsert">Register or update a subscriber</h2>
<x-docs.endpoint method="PUT" path="/subscribers/{reference}" />
<p>Creates the subscriber if the reference is new, otherwise updates it. Safe to call repeatedly — for example every time a customer opens your app.</p>

<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Where</th><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td>Path</td><td><code class="inline">reference</code></td><td>Your identifier for the payer. Unique within your organisation.</td></tr>
    <tr><td>Body</td><td><code class="inline">phone</code></td><td>Optional, string, max 32 characters, may be <code class="inline">null</code>. Used to match the caller when <strong>caller binding</strong> is on for your account (see below).</td></tr>
  </tbody>
</table></div>

<x-docs.code lang="bash">curl -X PUT {{ config('docs.api_base') }}/subscribers/cust-42 \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "phone": "+2348012345678" }'</x-docs.code>
<x-docs.code lang="json">// 201 Created (200 OK when it already existed)
{ "reference": "cust-42", "phone": "+2348012345678" }</x-docs.code>
<x-docs.try-it method="PUT" path="/subscribers/{reference}" :fields="[
    ['name' => 'phone', 'in' => 'body', 'placeholder' => '+2348012345678'],
]" warning="Creates or updates a subscriber on your account." />

<h2 id="caller-binding">Phone numbers and caller binding</h2>
<p>When <strong>caller binding</strong> is switched on for your account (a setting PakaPay controls for you), a code only works if the call comes from the subscriber's registered phone number. Numbers are compared as <strong>full international numbers</strong>: <code class="inline">+2348012345678</code>, <code class="inline">2348012345678</code>, <code class="inline">08012345678</code> and <code class="inline">8012345678</code> all match each other (a number written without a country code is assumed to be Nigerian), but a number from another country never matches one that merely ends in the same digits. Register subscribers with the country code to avoid ambiguity.</p>
<ul>
  <li>Binding <strong>on</strong>: a subscriber with no phone can never redeem a code.</li>
  <li>Binding <strong>off</strong>: the code alone authorises the payment. Caller ID can be spoofed, so keep codes short-lived. We recommend binding for retail use.</li>
</ul>

<h2 id="errors">Errors</h2>
<p><code class="inline">422</code> with <code class="inline">{ message, errors }</code> if <code class="inline">phone</code> is not a string of up to 32 characters. See <a href="{{ route('docs.show', ['section' => 'getting-started', 'page' => 'errors']) }}">Errors</a>.</p>
<p>There is no endpoint to list or delete subscribers; your system is the source of truth for who they are.</p>
