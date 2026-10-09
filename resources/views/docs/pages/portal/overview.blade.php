<div class="docs-eyebrow">Tenant Portal</div>
<h1>Overview &amp; Roles</h1>

<p>The <strong>portal</strong> is a web dashboard where your own staff see everything about your organisation's payments and manage your integration — without asking PakaPay. It lives at <code class="inline">/portal</code> on your platform deployment and is completely separate from the operator console.</p>

<h2 id="getting-in">Getting in</h2>
<ol>
  <li>The platform operator (or an existing owner) <strong>invites</strong> you. You receive a one-time <strong>invitation link</strong>, valid 7 days and usable once. (There is no email step; the link is handed to you directly.)</li>
  <li>Open the link, confirm your name and choose a password of at least 12 characters.</li>
  <li>Set up <strong>two-factor</strong> with an authenticator app (required in production).</li>
  <li>From then on, sign in at <code class="inline">/portal</code> with email, password and a 6-digit code.</li>
</ol>
<p>Forgot your password or lost your phone? An owner of your organisation (or the platform operator) issues a new access link or resets your two-factor. See <a href="{{ route('docs.show', ['section' => 'portal', 'page' => 'team']) }}">Team &amp; Security</a>.</p>

<h2 id="roles">Roles</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Can…</th><th>Owner</th><th>Developer</th><th>Viewer</th></tr></thead>
  <tbody>
    <tr><td>See the overview with money figures</td><td>✔</td><td>—</td><td>✔</td></tr>
    <tr><td>See transactions, payment codes, subscribers, merchants; export CSV</td><td>✔</td><td>—</td><td>✔</td></tr>
    <tr><td>See API key names, webhook endpoints and delivery logs</td><td>✔</td><td>✔</td><td>✔</td></tr>
    <tr><td>Create and revoke API keys</td><td>✔</td><td>✔</td><td>—</td></tr>
    <tr><td>Add, edit, pause, rotate and delete webhook endpoints; retry deliveries; send test events</td><td>✔</td><td>✔</td><td>—</td></tr>
    <tr><td>Cancel an unredeemed payment code</td><td>✔</td><td>—</td><td>—</td></tr>
    <tr><td>Read the audit log and verify its integrity</td><td>✔</td><td>—</td><td>✔</td></tr>
    <tr><td>Manage the team (invite, change role, switch off, reset 2FA, remove)</td><td>✔</td><td>—</td><td>—</td></tr>
    <tr><td>Own account page (password, two-factor) and read-only settings</td><td>✔</td><td>✔</td><td>✔</td></tr>
  </tbody>
</table></div>
<p>Developers deliberately cannot see payment amounts — an engineer can fix an integration without being able to see customers' money. Pages a role can't open aren't shown in its menu, and opening one directly returns <strong>403</strong>.</p>

<h2 id="what-you-can-t-change">What stays with the operator</h2>
<p>The portal shows, read-only, how PakaPay configured you: status, environment (sandbox/live), settlement adapter, code lifetime, caller binding, maximum amount, voice mode and numbers. Changing any of these — and going live — is done by the platform operator.</p>

<h2 id="isolation">Your data, only yours</h2>
<p>Every portal query is pinned to your organisation. Another tenant's transactions, codes, keys, webhooks or users are unreachable even if someone guesses an id (they get <em>not found</em>). Payer phone numbers are <strong>masked</strong> everywhere (<code class="inline">+23480••••78</code>) and never included in exports.</p>

<h2 id="environment">Sandbox vs live</h2>
<p>The header shows a <em>Sandbox</em> or <em>Live</em> badge for your organisation. In sandbox nothing moves real money. If your organisation is suspended, a red banner explains that new codes can't be issued.</p>
