<div class="docs-eyebrow">API Reference</div>
<h1>Merchants</h1>

<p>A <strong>merchant</strong> is the payee — the shop, biller or wallet that receives the money. Each merchant carries an <code class="inline">account_reference</code>: <em>your</em> identifier for the account that gets credited. The platform passes it to your system when it captures funds; it never interprets it.</p>

<h2 id="upsert">Register or update a merchant</h2>
<x-docs.endpoint method="PUT" path="/merchants/{reference}" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Where</th><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td>Path</td><td><code class="inline">reference</code></td><td>Your identifier for the merchant. Unique within your organisation.</td></tr>
    <tr><td>Body</td><td><code class="inline">name</code></td><td><strong>Required.</strong> String, max 191. Shown in your portal.</td></tr>
    <tr><td>Body</td><td><code class="inline">account_reference</code></td><td><strong>Required.</strong> String, max 191. The account to credit on capture.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl -X PUT {{ config('docs.api_base') }}/merchants/shop-7 \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "name": "Corner Shop", "account_reference": "acct-9001" }'</x-docs.code>
<x-docs.code lang="json">// 201 Created (200 OK when it already existed)
{ "reference": "shop-7", "name": "Corner Shop", "account_reference": "acct-9001" }</x-docs.code>
<x-docs.try-it method="PUT" path="/merchants/{reference}" :fields="[
    ['name' => 'name', 'in' => 'body', 'required' => true, 'default' => 'Corner Shop'],
    ['name' => 'account_reference', 'in' => 'body', 'required' => true, 'default' => 'acct-9001'],
]" warning="Creates or updates a merchant on your account." />

<x-docs.callout type="warn" title="Changing account_reference">
  <p>The merchant's current <code class="inline">account_reference</code> is read at capture time, not when the code is issued. If you change it while codes are open, those payments settle to the <em>new</em> account.</p>
</x-docs.callout>

<h2 id="errors">Errors</h2>
<p><code class="inline">422</code> with <code class="inline">{ message, errors }</code> when <code class="inline">name</code> or <code class="inline">account_reference</code> is missing or too long.</p>
