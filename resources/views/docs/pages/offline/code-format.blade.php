<div class="docs-eyebrow">Offline Phone Codes</div>
<h1>Code Format &amp; Signing</h1>

<p>This page is the contract between your mobile app and the platform. Build the code <strong>byte for byte</strong> as described: one wrong character gives a different signature. Test against the vectors at the end.</p>

<h2 id="layout">Layout — 40 digits, fixed width</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Digits</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td>VERSION</td><td>1</td><td>Always <code class="inline">1</code>.</td></tr>
    <tr><td>DEVICE</td><td>8</td><td>The phone's <code class="inline">short_id</code> from enrolment, leading zeros kept.</td></tr>
    <tr><td>COUNTER</td><td>5</td><td>Strictly greater than any counter this phone has used (1–99999), zero-padded. It makes a code work once.</td></tr>
    <tr><td>AMOUNT</td><td>8</td><td>Amount in <strong>minor units (kobo)</strong>, zero-padded. <code class="inline">00250000</code> = ₦2,500.00. 1 to 99,999,999 (₦999,999.99).</td></tr>
    <tr><td>MERCHANT</td><td>8</td><td>The merchant's <code class="inline">short_id</code>, leading zeros kept.</td></tr>
    <tr><td>MAC</td><td>10</td><td>The signature, below.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="text"> V  DDDDDDDD  CCCCC  AAAAAAAA  MMMMMMMM  SSSSSSSSSS
 1  48201937  00042  00250000  70011234  4868933947   →  1482019370004200250000700112344868933947</x-docs.code>
<p>On a <strong>shared</strong> voice number, put your organisation's 4-digit short code in front (44 digits dialled). On your own number, dial the 40 digits.</p>

<h2 id="why-ids">Why the code holds ids, not account numbers</h2>
<p>Everything you dial is logged by the telephony provider. So the code contains only <strong>identifiers</strong> (device, merchant) and no account numbers or bank codes. The signature, however, <strong>covers</strong> both parties' account details. The platform rebuilds them from its own records when it verifies, so a code cannot be re-pointed at another account, and nobody reading a call log learns an account number.</p>

<h2 id="signature">Computing the signature</h2>
<ol>
  <li><strong>Window</strong> = <code class="inline">floor(unix_seconds / 300)</code>. It is not transmitted; the platform accepts the previous, current and next window, so a code is good for roughly 5–15 minutes after the phone built it, and the phone's clock must be within about 5 minutes of real time.</li>
  <li><strong>Canonical text</strong>, joined with <code class="inline">|</code>:
<x-docs.code lang="text">nunu-d1|DEVICE|COUNTER|AMOUNT|MERCHANT|WINDOW|CURRENCY|SRC_ACCOUNT|SRC_BANK|DST_ACCOUNT|DST_BANK</x-docs.code>
    <code class="inline">DEVICE</code> and <code class="inline">MERCHANT</code> are the 8-digit strings (with their leading zeros). <code class="inline">COUNTER</code>, <code class="inline">AMOUNT</code> and <code class="inline">WINDOW</code> are plain decimal integers <strong>without</strong> padding. <code class="inline">CURRENCY</code> is upper case (<code class="inline">NGN</code>, from enrolment). <code class="inline">SRC_*</code> is the subscriber's own account number and bank code; <code class="inline">DST_*</code> is the merchant's.</li>
  <li><strong>HMAC</strong> = HMAC-SHA256 of the canonical text (UTF-8), keyed with the 32 raw bytes of the base64-<em>decoded</em> <code class="inline">device_key</code>.</li>
  <li><strong>Truncate</strong>: take the first <strong>5 bytes</strong> as a big-endian unsigned integer, compute <code class="inline">value mod 10<sup>10</sup></code>, left-pad with zeros to 10 digits. That is MAC.</li>
</ol>

<h2 id="examples">Examples</h2>
<x-docs.code lang="js">// JavaScript (Node) — the client library does this for you
import { buildDeviceCode, dialString } from 'offline-payments-client';

const code = buildDeviceCode({
  deviceKey: 'MDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWY=',
  deviceShortId: '48201937',
  counter: 42,                         // last_counter + 1, then increment and PERSIST before dialling
  amountMinor: 250000,
  merchantShortId: '70011234',
  source: { accountNumber: '2000000001', bankCode: '058' },
  destination: { accountNumber: '3000000001', bankCode: '011' },
});
const dial = dialString('+2347000000001', code);   // "+2347000000001,,,1482…#"</x-docs.code>
<x-docs.code lang="php">// PHP
use OfflinePayments\DeviceCode;

$code = DeviceCode::build(
    deviceKey: 'MDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWY=',
    deviceShortId: '48201937', counter: 42, amountMinor: 250000, merchantShortId: '70011234',
    source: ['accountNumber' => '2000000001', 'bankCode' => '058'],
    destination: ['accountNumber' => '3000000001', 'bankCode' => '011'],
);</x-docs.code>
<x-docs.code lang="dart">// Dart / Flutter (package:crypto) — port for the mobile app
String buildDeviceCode({
  required Uint8List key, required String device, required int counter, required int amountMinor,
  required String merchant, required String srcAccount, required String srcBank,
  required String dstAccount, required String dstBank, String currency = 'NGN', int? nowSeconds,
}) {
  final window = (nowSeconds ?? DateTime.now().millisecondsSinceEpoch ~/ 1000) ~/ 300;
  final canonical = ['nunu-d1', device, counter, amountMinor, merchant, window, currency.toUpperCase(),
    srcAccount, srcBank, dstAccount, dstBank].join('|');
  final mac = Hmac(sha256, key).convert(utf8.encode(canonical)).bytes;
  var v = 0;
  for (final b in mac.sublist(0, 5)) { v = (v << 8) | b; }          // fits in a 64-bit int
  return '1$device${counter.toString().padLeft(5, '0')}${amountMinor.toString().padLeft(8, '0')}'
         '$merchant${(v % 10000000000).toString().padLeft(10, '0')}';
}</x-docs.code>

<h2 id="vectors">Test vectors</h2>
<p>Use these to prove your implementation before shipping. Key (base64): <code class="inline">MDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWY=</code> (the ASCII text <code class="inline">0123456789abcdef0123456789abcdef</code>). Unix time <code class="inline">1791600000</code> (window <code class="inline">5972000</code>), currency NGN, source account <code class="inline">2000000001</code> bank <code class="inline">058</code>, destination account <code class="inline">3000000001</code> bank <code class="inline">011</code>.</p>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Device</th><th>Counter</th><th>Amount</th><th>Merchant</th><th>Code</th></tr></thead>
  <tbody>
    <tr><td><code class="inline">48201937</code></td><td>42</td><td>250000</td><td><code class="inline">70011234</code></td><td><code class="inline">1482019370004200250000700112344868933947</code></td></tr>
    <tr><td><code class="inline">00000007</code></td><td>1</td><td>100</td><td><code class="inline">00000009</code></td><td><code class="inline">1000000070000100000100000000090281068932</code></td></tr>
  </tbody>
</table></div>
<p>Canonical text for the first vector: <code class="inline">nunu-d1|48201937|42|250000|70011234|5972000|NGN|2000000001|058|3000000001|011</code></p>

<h2 id="counter">Managing the counter</h2>
<ul>
  <li>Increment <strong>before</strong> you build and persist it before dialling, so a crash can't reuse a counter.</li>
  <li>Never reuse one, even if the call failed: a counter the platform accepted is spent. Build a new code with the next counter.</li>
  <li>After a reinstall, call enrol again and resume from <code class="inline">last_counter + 1</code>. A phone that jumps far ahead only wastes numbers; the counter ends at 99999, after which you revoke and re-enrol the device (counter resets).</li>
</ul>
