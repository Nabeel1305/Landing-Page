<div class="docs-eyebrow">Offline Payments</div>
<h1>Code Format &amp; Signing</h1>

<p>This page is the contract between the mobile app and the server. The app must build the code <strong>byte-for-byte</strong> as described — one differing separator invalidates every signature.</p>

<h2 id="layout">Layout — 31 digits</h2>
<div class="docs-table-wrap"><table class="docs-table">
  <thead><tr><th>Field</th><th>Digits</th><th>Meaning</th></tr></thead>
  <tbody>
    <tr><td>VERSION</td><td>1</td><td>Format version. Currently <code class="inline">1</code> (also returned by the key endpoint).</td></tr>
    <tr><td>FROM-ACCOUNT</td><td>1</td><td>1-based position of the paying bank account in the user's list, ordered by link date (<code class="inline">linked_on</code>), then id. So <code class="inline">1</code> = first linked account.</td></tr>
    <tr><td>COUNTER</td><td>5</td><td>Per-device counter, strictly increasing with every code generated. Replay protection.</td></tr>
    <tr><td>AMOUNT</td><td>6</td><td>Whole naira, zero-padded (<code class="inline">002500</code> = ₦2,500). Kobo is not supported.</td></tr>
    <tr><td>QR-ID</td><td>8</td><td>The receiver's 8-digit <code class="inline">qr_id</code>, with leading zeros kept.</td></tr>
    <tr><td>SIGNATURE</td><td>10</td><td>Truncated HMAC, below.</td></tr>
  </tbody>
</table></div>
<x-docs.code lang="text"> V  F  CCCCC  AAAAAA  QQQQQQQQ  SSSSSSSSSS
 1  1  00042  002500  48201937  7710294358        → 1100042002500482019377710294358</x-docs.code>

<h2 id="signature">Computing the signature</h2>
<ol>
  <li><strong>Time window</strong> = <code class="inline">floor(unix_seconds / 300)</code>. Not transmitted; the server checks its own clock and accepts the previous, current and next window (±5 min of clock drift).</li>
  <li><strong>Canonical string</strong> — joined with <code class="inline">|</code>:
    <x-docs.code lang="text">version|deviceId|fromAccount|counter|amount|qrId|timeWindow</x-docs.code>
    Integers are plain decimal with <em>no</em> zero-padding (<code class="inline">counter</code> 42 → <code class="inline">42</code>, <code class="inline">amount</code> 2500 → <code class="inline">2500</code>); <code class="inline">qrId</code> is the 8-char string and <code class="inline">deviceId</code> is the bound device id string.
  </li>
  <li><strong>HMAC</strong> = HMAC-SHA256(<em>canonical</em>, key = raw 32-byte device key — i.e. the base64-<em>decoded</em> <code class="inline">device_key</code>).</li>
  <li><strong>Truncate</strong>: take the first <strong>5 bytes</strong> as a big-endian unsigned integer, compute <code class="inline">value mod 10<sup>10</sup></code>, left-pad with zeros to 10 digits.</li>
</ol>
<x-docs.code lang="dart">// Dart reference (crypto package)
String signature({
  required Uint8List deviceKey, required int version, required String deviceId,
  required int fromAccount, required int counter, required int amount,
  required String qrId, required int timeWindow,
}) {
  final canonical = [version, deviceId, fromAccount, counter, amount, qrId, timeWindow].join('|');
  final mac = Hmac(sha256, deviceKey).convert(utf8.encode(canonical)).bytes;
  var v = 0;
  for (final b in mac.sublist(0, 5)) { v = (v << 8) | b; }
  return (v % 10000000000).toString().padLeft(10, '0');
}</x-docs.code>
<x-docs.code lang="php">// PHP reference — App\Util\Offline\OfflineCodeService
$raw = hash_hmac('sha256', implode('|', [$version, $deviceId, $from, $counter, $amount, $qrId, $window]), $deviceKey, true);
$value = 0;
foreach (str_split(substr($raw, 0, 5)) as $byte) { $value = ($value << 8) | ord($byte); }
$signature = str_pad((string) ($value % 10_000_000_000), 10, '0', STR_PAD_LEFT);</x-docs.code>

<x-docs.callout type="warn" title="Know the trade-off">
  <p>10 digits is a deliberately short signature (it must fit DTMF). Its safety rests on the combination of a single-use counter, the 5-minute window, per-account lockout (5 bad signatures lock the account), caller-ID matching and the offline spending caps — not on signature length alone.</p>
</x-docs.callout>

<h2 id="counter">Managing the counter</h2>
<ul>
  <li>Increment before every code generated, and persist it <em>before</em> dialing.</li>
  <li>Never reuse a counter after a failed call — a counter the server has seen but not completed returns "already submitted". Generate a new code with the next counter.</li>
  <li>If the app reinstalls and loses its counter, resume from a value safely above any previous one (e.g. a timestamp-derived start), because the server rejects anything ≤ the last accepted counter.</li>
  <li>The 5-digit counter caps at 99,999 codes per device key lifetime; plan rotation by re-binding the device.</li>
</ul>
