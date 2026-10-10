<div class="docs-eyebrow">API Reference</div>
<h1>Merchants</h1>

<p>A <strong>merchant</strong> is the payee — the shop, biller or wallet that receives the money. Each merchant carries the <strong>bank account that gets credited</strong>: an <code class="inline">account_number</code> and a <code class="inline">bank_code</code>. The platform passes them to your system when it captures funds.</p>

<x-docs.callout type="note" title="You can skip this call">
  <p>You can also register a merchant while issuing a payment code, by sending <code class="inline">merchant: { name, account_number, bank_code }</code> with <code class="inline">POST /codes</code>. See <a href="{{ route('docs.show', ['section' => 'api', 'page' => 'codes']) }}#merchant-on-the-fly">Creating the merchant while issuing</a>. Use <code class="inline">PUT /merchants</code> when you want to <strong>change</strong> a merchant — it is the only way to change the account that gets credited.</p>
</x-docs.callout>

<h2 id="upsert">Register or update a merchant</h2>
<x-docs.endpoint method="PUT" path="/merchants/{reference}" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Where</th><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td>Path</td><td><code class="inline">reference</code></td><td>Your identifier for the merchant, max 191 characters. Unique within your organisation.</td></tr>
    <tr><td>Body</td><td><code class="inline">name</code></td><td><strong>Required.</strong> String, max 191.</td></tr>
    <tr><td>Body</td><td><code class="inline">account_number</code></td><td><strong>Required.</strong> The account to credit — 10 digits by default.</td></tr>
    <tr><td>Body</td><td><code class="inline">bank_code</code></td><td><strong>Required.</strong> The bank's code for that account, 3–10 letters or digits.</td></tr>
    <tr><td>Body</td><td><code class="inline">account_reference</code></td><td>Optional. Your own label for the account (max 191), passed to your system alongside the account.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="bash">curl -X PUT {{ config('docs.api_base') }}/merchants/shop-7 \
  -H "Authorization: Bearer $KEY" -H "Content-Type: application/json" \
  -d '{ "name": "Corner Shop", "account_number": "3000000001", "bank_code": "011" }'</x-docs.code>
<x-docs.code lang="json">// 201 Created (200 OK when it already existed)
{ "reference": "shop-7", "name": "Corner Shop", "account_number": "3000000001", "bank_code": "011", "account_reference": null }</x-docs.code>
<x-docs.try-it method="PUT" path="/merchants/{reference}" :fields="[
    ['name' => 'name', 'in' => 'body', 'required' => true, 'default' => 'Corner Shop'],
    ['name' => 'account_number', 'in' => 'body', 'required' => true, 'default' => '3000000001'],
    ['name' => 'bank_code', 'in' => 'body', 'required' => true, 'default' => '011'],
    ['name' => 'account_reference', 'in' => 'body', 'placeholder' => 'optional label'],
]" warning="Creates or updates a merchant on your account." />

<x-docs.callout type="note" title="Changing a merchant's account is safe for payments in flight">
  <p>When a code is issued, both accounts are <strong>frozen on the code</strong>. If you change a merchant's account afterwards, codes already issued still credit the account they were issued with; only new codes use the new one.</p>
</x-docs.callout>

<h2 id="errors">Errors</h2>
<p><code class="inline">422</code> with <code class="inline">{ message, errors }</code> when <code class="inline">name</code>, <code class="inline">account_number</code> or <code class="inline">bank_code</code> is missing or in the wrong format.</p>
