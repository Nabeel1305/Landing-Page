<div class="docs-eyebrow">Tenant Portal</div>
<h1>Team &amp; Security</h1>

<h2 id="team">Managing your team <span class="docs-endpoint-auth is-auth">owners only</span></h2>
<ul>
  <li><strong>Invite</strong> someone with a name, email and role. You get a one-time link (valid 7 days) to send them yourself; they choose their own password. Until they accept, they show as <em>invited</em> (or <em>invite expired</em>).</li>
  <li><strong>Change role</strong> at any time.</li>
  <li><strong>New access link</strong> — for someone who hasn't accepted yet or has forgotten their password. Any older link stops working.</li>
  <li><strong>Reset two-factor</strong> — for a lost phone; they set it up again at next sign-in.</li>
  <li><strong>Switch off / restore</strong> — a switched-off person is signed out at their next request and can't sign in.</li>
  <li><strong>Remove</strong> permanently.</li>
</ul>
<p>Guard-rails: you can't change, switch off, reset or remove <em>yourself</em> (ask another owner), and the last active owner can't be demoted, switched off or removed — an organisation always keeps someone who can manage the team. Emails are unique across the whole platform.</p>

<h2 id="account">Your own account</h2>
<ul>
  <li><strong>Two-factor</strong>: add the secret key to an authenticator app, then enter the 6-digit code once to turn it on. Where required, you can't use the rest of the portal until it's on. It can't be switched off by you; an owner can reset it.</li>
  <li><strong>Password</strong>: needs the current password; the new one must be at least 12 characters and different.</li>
</ul>

<h2 id="sign-in-protection">Sign-in protection</h2>
<ul>
  <li>Five wrong passwords for an email from one address lock further attempts for 15 minutes. The message is the same for an unknown email, a wrong password and a switched-off account.</li>
  <li>The authenticator step allows five wrong codes, then you must sign in again. A code can be used once.</li>
  <li>After the password step you have five minutes to supply the code.</li>
</ul>

<h2 id="audit">Audit log <span class="docs-endpoint-auth is-auth">owner &amp; viewer</span></h2>
<p>A permanent, <strong>tamper-evident</strong> record of what happened in your organisation: code and transaction events, plus portal and operator actions (sign-ins, key and endpoint changes, team changes, code cancellations). Filter by event type. Each entry stores a hash of its content and of the entry before it, so any edit or deletion breaks the chain.</p>
<p><strong>Verify chain integrity</strong> re-computes every hash and reports the id of any entry that no longer matches. "Intact" means nothing has been altered or removed since it was written. If it ever reports a break, contact the platform operator.</p>
