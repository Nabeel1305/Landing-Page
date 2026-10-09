<div class="docs-eyebrow">Account &amp; Profile</div>
<h1>Bank Accounts</h1>

<p>PakaPay moves money between users' own Nigerian bank accounts. A user links one or more accounts; one is <em>active</em> (the default for sending and receiving). Account numbers are resolved against the bank through the payment provider (Paystack) before linking, so the stored <code class="inline">account_name</code> is the bank's, not user-typed.</p>

<h2 id="banks">List banks</h2>
<x-docs.endpoint method="GET" path="/settings/banks" />
<p>The bank list (name + CBN code), cached for 24 hours. Use it to populate the bank picker.</p>
<x-docs.code lang="json">{ "status": true, "data": [ { "name": "Guaranty Trust Bank", "code": "058" }, { "name": "First Bank of Nigeria", "code": "011" } ] }</x-docs.code>
<x-docs.try-it method="GET" path="/settings/banks" :mutating="false" />

<h2 id="verify">Resolve an account number</h2>
<x-docs.endpoint method="POST" path="/settings/accounts/verify" />
<x-docs.code lang="bash">curl {{ config('docs.api_base') }}/settings/accounts/verify \
  -H "Authorization: Bearer 2|Zp0q..." -H "Content-Type: application/json" \
  -d '{ "account_no": "0123456789", "bank_code": "058" }'</x-docs.code>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">account_no</code></td><td>Required, exactly 10 digits</td></tr>
    <tr><td><code class="inline">bank_code</code></td><td>Required, max 10 chars</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="json">{ "status": true, "data": { "account_name": "AMAKA OKONKWO", "account_no": "0123456789", "bank_code": "058" } }</x-docs.code>
<x-docs.try-it method="POST" path="/settings/accounts/verify" :fields="[
    ['name' => 'account_no', 'in' => 'body', 'required' => true, 'placeholder' => '0123456789'],
    ['name' => 'bank_code', 'in' => 'body', 'required' => true, 'placeholder' => '058'],
]" :mutating="false" />

<h2 id="list">List linked accounts</h2>
<x-docs.endpoint method="GET" path="/settings/accounts" />
<x-docs.code lang="json">{
  "status": true,
  "data": [
    {
      "id": "acc-k3j2h4g5",
      "bank": "Guaranty Trust Bank",
      "label": "Salary account",
      "last4": "6789",
      "account_no": "0123456789",
      "account_name": "AMAKA OKONKWO",
      "bank_code": "058",
      "issued_at": "2026-10-01T09:00:00.000000Z",
      "type": "Savings",
      "signature": "base64-hmac...",
      "linked_on": "Oct 01, 2026",
      "is_active": true
    }
  ],
  "meta": { "total": 1 }
}</x-docs.code>
<p><code class="inline">is_active</code> marks the single default account — it is <em>not</em> a "still linked" flag. <code class="inline">signature</code> is the HMAC of the account's QR fields (see <a href="{{ route('docs.show', ['section' => 'payments', 'page' => 'receiving']) }}">Receiving Money</a>).</p>
<x-docs.try-it method="GET" path="/settings/accounts" :mutating="false" />

<h2 id="link">Link an account</h2>
<x-docs.endpoint method="POST" path="/settings/accounts" />
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Rules</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">bank</code></td><td>Required, max 100</td></tr>
    <tr><td><code class="inline">bank_code</code></td><td>Required, max 10</td></tr>
    <tr><td><code class="inline">account_no</code></td><td>Required, 10 digits</td></tr>
    <tr><td><code class="inline">account_type</code></td><td>Optional: <code class="inline">Savings</code> or <code class="inline">Current</code></td></tr>
  </tbody>
</table></div>
<p>The account is resolved with the bank, linked and immediately made the <strong>active</strong> account. An account number can be linked to only one PakaPay user — a duplicate returns 422 <code class="inline">This account is already linked to a PakaPay account.</code> The default label is the bank name.</p>
<x-docs.code lang="json">{
  "status": true,
  "message": "Account linked successfully.",
  "data": {
    "id": "acc-k3j2h4g5", "bank": "Guaranty Trust Bank", "account_name": "AMAKA OKONKWO",
    "account_no": "0123456789", "bank_code": "058", "issued_at": 1791471600,
    "last4": "6789", "linked_on": "Oct 08, 2026", "signature": "base64-hmac..."
  }
}</x-docs.code>
<x-docs.try-it method="POST" path="/settings/accounts" :fields="[
    ['name' => 'bank', 'in' => 'body', 'required' => true, 'placeholder' => 'Guaranty Trust Bank'],
    ['name' => 'bank_code', 'in' => 'body', 'required' => true, 'placeholder' => '058'],
    ['name' => 'account_no', 'in' => 'body', 'required' => true],
    ['name' => 'account_type', 'in' => 'body', 'type' => 'select', 'options' => ['Savings', 'Current'], 'default' => 'Savings'],
]" warning="Links a real bank account to your PakaPay profile." />

<h2 id="manage">Rename, activate, remove</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Method</th><th>Path</th><th>Body</th><th>Result</th></tr></thead>
  <tbody>
    <tr><td>PATCH</td><td><code class="inline">/settings/accounts/{id}/rename</code></td><td><code class="inline">label</code> (2–100 chars)</td><td><code class="inline">data: {id, label}</code></td></tr>
    <tr><td>PATCH</td><td><code class="inline">/settings/accounts/{id}/activate</code></td><td>—</td><td>Makes this the default account</td></tr>
    <tr><td>DELETE</td><td><code class="inline">/settings/accounts/{id}</code></td><td>—</td><td>Unlinks the account. 422 <code class="inline">This account still has payment points attached to it. Archive or delete them first.</code> if payment points exist</td></tr>
  </tbody>
</table></div>
<p>Unknown ids return 404 <code class="inline">Account not found.</code></p>
<x-docs.try-it method="PATCH" path="/settings/accounts/{id}/rename" :fields="[
    ['name' => 'label', 'in' => 'body', 'required' => true],
]" warning="Renames a real linked account." />
<x-docs.try-it method="PATCH" path="/settings/accounts/{id}/activate" warning="Changes your default account." />
<x-docs.try-it method="DELETE" path="/settings/accounts/{id}" :high-stakes="true" warning="Unlinks the account." />
