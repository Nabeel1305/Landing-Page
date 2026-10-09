<div class="docs-eyebrow">Payers &amp; Settlement</div>
<h1>Code Lifecycle &amp; Reconciliation</h1>

<h2 id="states">States and transitions</h2>
<x-docs.code lang="text">                ┌──────────▶ cancelled   (you, via the API or the portal)
 issued ────────┼──────────▶ expired     (deadline passed unused)
                └─▶ redeemed ─┬─▶ settled   (your system captured)
                              └─▶ failed    (capture declined / hold released)</x-docs.code>
<p>Only the transitions shown are possible; a final state (<code class="inline">settled</code>, <code class="inline">failed</code>, <code class="inline">cancelled</code>, <code class="inline">expired</code>) never changes again. The platform enforces this in code, so a late capture and a reconciliation pass can never both settle the same transaction.</p>

<h2 id="expiry">Expiry</h2>
<p>A scheduled job runs <strong>every minute</strong> and expires every <code class="inline">issued</code> code past its deadline: the state becomes <code class="inline">expired</code>, the hold is released and <code class="inline">code.expired</code> is sent. A code is also refused at redemption the instant its deadline passes, even before the sweep has run. The lifetime is set per account (1–60 minutes, default 10).</p>

<h2 id="lost-capture">When the capture result is lost</h2>
<p>The capture call goes over the network to your system. It can time out or fail after your system already moved the money. The platform will not guess: it leaves the code <code class="inline">redeemed</code> and the transaction <code class="inline">pending</code>.</p>
<p>A scheduled <strong>reconciliation</strong> job runs <strong>every five minutes</strong> for pending transactions older than 2 minutes. For each it asks your system's <code class="inline">status</code>:</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Your answer</th><th>Platform action</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">captured</code></td><td>Mark settled with your reference; send <code class="inline">transaction.settled</code></td></tr>
    <tr><td><code class="inline">released</code></td><td>Mark failed ("The tenant released the hold."); send <code class="inline">transaction.failed</code> — no second release is sent</td></tr>
    <tr><td><code class="inline">held</code></td><td>Not captured yet: ask your system to capture again (safe — keyed by the transaction reference)</td></tr>
    <tr><td><code class="inline">unknown</code> / no answer</td><td>Leave it pending. After <strong>24 hours</strong> it is flagged <code class="inline">transaction.needs_review</code> in the audit log (once) and a critical log line is written, for a person to resolve manually</td></tr>
  </tbody>
</table></div>
<p>So a payment you never heard back about resolves itself within minutes in the normal case, and is escalated to a human after a day in the worst case.</p>

<h2 id="reading">Reading state</h2>
<ul>
  <li>Webhooks announce each transition.</li>
  <li><code class="inline">GET /codes/{id}</code> and <code class="inline">GET /transactions/{id}</code> give the current truth.</li>
</ul>

