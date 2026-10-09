<div class="docs-eyebrow">Tenant Portal</div>
<h1>Payments &amp; Reporting</h1>
<p><em>Owner and viewer roles.</em></p>

<h2 id="overview">Overview</h2>
<p>The landing page summarises a period you choose (today, 7, 30 or 90 days):</p>
<ul>
  <li><strong>Settled</strong> — total value and number of settled payments (one figure per currency).</li>
  <li><strong>Success rate</strong> — settled ÷ (settled + failed), and the failed count.</li>
  <li><strong>Codes issued</strong> — and how many are open right now.</li>
  <li><strong>Waiting on you</strong> — pending transactions, and how old the oldest is. A growing number here usually means your settlement system is slow or down.</li>
  <li>A <strong>settled-per-day chart</strong>, <strong>top merchants</strong>, <strong>code outcomes</strong> and the <strong>latest failures</strong> with their reasons.</li>
</ul>
<p>Developers and viewers also see <em>integration health</em>: webhook delivery rate, active endpoints, active API keys and active voice numbers.</p>

<h2 id="transactions">Transactions</h2>
<p>Every payment made with one of your codes, newest first.</p>
<ul>
  <li><strong>Filters:</strong> search by reference, id or settlement reference; status (pending, settled, failed); date range; minimum and maximum amount; merchant reference; subscriber reference. The page shows the match count and total value.</li>
  <li><strong>Detail page:</strong> amount, status, merchant and the account credited, subscriber, paying account, masked caller number, settlement reference or failure reason, timestamps, a lifecycle timeline (issued → redeemed → settled/failed), a link to the payment code, and the webhooks sent for this payment (for roles that may see them).</li>
  <li><strong>Export CSV</strong> of the current filter (up to 50,000 rows): transaction id, reference, status, amount, currency, merchant, subscriber reference, code id, settlement reference, failure reason, created and settled times. Payer phone numbers are not exported, and cells that could be run as spreadsheet formulas are neutralised.</li>
</ul>

<h2 id="codes">Payment codes</h2>
<p>Filter by state, date range, or search by code id, subscriber or merchant reference. A code's page shows state, amount, merchant, subscriber, hold reference, issue / expiry / redemption times and the linked transaction. The digits of a code are <strong>never shown</strong> — they aren't stored.</p>
<p><strong>Owners</strong> can <strong>cancel</strong> a code that is still <code class="inline">issued</code>. This releases the hold and writes an audit entry naming who did it. Redeemed or finished codes can't be cancelled.</p>

<h2 id="subscribers-merchants">Subscribers &amp; merchants</h2>
<p>Read-only lists of the payers and payees your systems registered through the API, with the number of codes for each, and search. They are created and changed through <code class="inline">PUT /subscribers</code> and <code class="inline">PUT /merchants</code>.</p>
