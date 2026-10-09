<div class="docs-eyebrow">Platform (Internal)</div>
<h1>Admin Dashboard</h1>

<p>A server-rendered Blade back office for PakaPay operators, mounted at <code class="inline">/admin</code> (the web root redirects to <code class="inline">/admin/login</code>). It is separate from the mobile API: sessions use the <code class="inline">AuthenticateSuperAdmin</code> middleware and the <code class="inline">super_admins</code> table, not Sanctum.</p>

<x-docs.callout type="danger" title="Never expose to the public internet unprotected">
  <p>The dashboard shows account numbers, names and transaction history. Restrict it by network (VPN / IP allow-list) in addition to its login, and review the access log regularly. Every view and export of user-identifying data is written to the audit log.</p>
</x-docs.callout>

<h2 id="screens">Screens &amp; routes</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Area</th><th>Route(s)</th><th>What operators can do</th></tr></thead>
  <tbody>
    <tr><td>Login</td><td><code class="inline">GET|POST /admin/login</code>, <code class="inline">POST /admin/logout</code></td><td>Super-admin sign in</td></tr>
    <tr><td>Dashboard</td><td><code class="inline">GET /admin</code></td><td>Headline metrics</td></tr>
    <tr><td>Users</td><td><code class="inline">/admin/users</code>, <code class="inline">/admin/users/{user}</code></td><td>Search, view profile, KYC, device state; suspend/unlock; suspend/resume device</td></tr>
    <tr><td>Transactions</td><td><code class="inline">/admin/transactions</code> (+ <code class="inline">/pending</code>, <code class="inline">/reversals</code>, <code class="inline">/export</code>, <code class="inline">/{id}/detail</code>)</td><td>Browse, filter, CSV export; <code class="inline">POST /{id}/reverse</code> (reason required, creates an <code class="inline">REV-</code> counter-transaction) and <code class="inline">/{id}/cancel</code></td></tr>
    <tr><td>Reports</td><td><code class="inline">/admin/reports</code>, <code class="inline">/export</code></td><td>Aggregates incl. top senders/receivers; monthly CSV</td></tr>
    <tr><td>Bank accounts</td><td><code class="inline">/admin/accounts</code> (+ <code class="inline">POST /</code>, <code class="inline">DELETE /destroy</code>, <code class="inline">PATCH /activate</code>)</td><td>View/add/remove/activate users' linked accounts</td></tr>
    <tr><td>Security</td><td><code class="inline">/admin/security</code> (+ <code class="inline">/users/{user}/toggle|limits|force-reset</code>)</td><td>Toggle a user's security flags, override limits, force a PIN reset</td></tr>
    <tr><td>Fraud</td><td><code class="inline">/admin/fraud</code> (+ <code class="inline">/{event}/resolve|escalate</code>, <code class="inline">/bulk-resolve</code>, <code class="inline">/export</code>, <code class="inline">/simulate</code>)</td><td>Work the fraud-event queue</td></tr>
    <tr><td>Stop-switches</td><td><code class="inline">PATCH /admin/fraud/users/{user}/suspend|unlock|suspend-device|resume-device</code>, <code class="inline">/admin/fraud/kill-switch</code> (+ <code class="inline">activate</code>, <code class="inline">deactivate</code>)</td><td>Lock one account, suspend one device, or halt the whole API</td></tr>
    <tr><td>Audit log</td><td><code class="inline">GET /admin/audit-log</code>, <code class="inline">POST /admin/audit-log/verify</code></td><td>Filter by event/actor/reference/date; verify the hash chain (read-only — no edit or delete routes exist)</td></tr>
    <tr><td>Service API keys</td><td><code class="inline">/admin/settings/api-keys</code> (+ <code class="inline">POST /</code>, <code class="inline">PATCH /toggle</code>)</td><td>Set/rotate provider credentials (Paystack, Smile ID, SMS, mail, AT…) and enable/disable them</td></tr>
  </tbody>
</table></div>
<p>(The newer <code class="inline">dashboard-and-api</code> build adds transaction problem reports, account deletion handling and admin role/permission management on top of this set.)</p>

<h2 id="incident-response">Incident response cheat-sheet</h2>
<ol>
  <li><strong>One user compromised:</strong> Users → profile → <em>Suspend User</em> (locks + revokes all tokens). Add a reason; it is audit-logged.</li>
  <li><strong>One phone stolen:</strong> <em>Suspend device</em> — lighter than a lock; the user can re-verify.</li>
  <li><strong>Systemic problem:</strong> Fraud → <em>Kill Switch</em> → Activate, with a reason. Every authenticated endpoint returns 503 <code class="inline">SERVICE_SUSPENDED</code> within 5 seconds (cache TTL); a banner appears on every admin page. Deactivate when safe.</li>
  <li><strong>Wrong payment:</strong> Transactions → detail → <em>Reverse</em> (reason required). Reversal creates a counter-transaction and emails the user if their email is verified; it doesn't re-trigger signing of the original.</li>
  <li><strong>Suspect tampering:</strong> Audit log → <em>Verify chain integrity</em>. Any returned ids are rows whose hash or link is broken.</li>
</ol>
