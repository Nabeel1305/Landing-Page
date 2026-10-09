<div class="docs-eyebrow">Reference</div>
<h1>Changelog</h1>

<p>Notable changes to the Offline Payments platform, most recent first. Dates are taken from the repository's migrations.</p>

<h2 id="2026-10-09">9 October 2026</h2>
<ul>
  <li><strong>Tenant portal</strong> at <code class="inline">/portal</code>: overview dashboard, transactions (filters, detail, CSV export), payment codes (with owner-only cancel), subscribers and merchants, API key and webhook endpoint management, delivery log with retry and test events, per-tenant audit log with integrity check, team management with owner / developer / viewer roles, mandatory two-factor in production. Payer phone numbers are masked.</li>
  <li><strong>Operator console</strong>: invite portal users, issue new access links, reset a portal user's two-factor, switch users off. New commands <code class="inline">tenant-user:create</code> and <code class="inline">tenant-user:reset-2fa</code>.</li>
  <li><strong>CORS</strong> now enabled for <code class="inline">/api/*</code> (any origin by default; restrict with <code class="inline">PLATFORM_CORS_ORIGINS</code>). Previously every browser call was refused.</li>
  <li>Indexes on <code class="inline">(tenant_id, created_at)</code> for transactions, payment codes and webhook deliveries.</li>
  <li>Dashboards moved to the shared PakaPay admin design; the content-security-policy still forbids inline scripts.</li>
</ul>

<h2 id="2026-10-06">6 October 2026</h2>
<ul>
  <li><strong>Initial release:</strong> API v1 (<code class="inline">/subscribers</code>, <code class="inline">/merchants</code>, <code class="inline">/webhook-endpoints</code>, <code class="inline">/codes</code>, <code class="inline">/transactions</code>) with idempotent issue and cancel, signed and retried webhooks, voice redemption via Africa's Talking, own and shared voice numbers, caller binding, sandbox settlement adapter, expiry and reconciliation jobs, hash-chained per-tenant audit log, operator console with mandatory two-factor, PHP and JavaScript clients, OpenAPI description.</li>
  <li>Upgraded to Laravel 12 to clear framework advisories.</li>
</ul>

<x-docs.callout type="note" title="Not yet available">
  <p>Real settlement adapters (only the sandbox simulator exists), live accounts, additional telephony providers, per-key permissions, and a list/delete API for webhook endpoints. These are not documented as features because they don't exist.</p>
</x-docs.callout>
