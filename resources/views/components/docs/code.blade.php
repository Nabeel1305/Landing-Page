@props(['lang' => 'text'])
<div class="docs-code" data-lang="{{ $lang }}">
  <div class="docs-code-head"><span>{{ $lang }}</span><button type="button" class="docs-code-copy">Copy</button></div>
  <pre><code>{{ trim((string) $slot) }}</code></pre>
</div>
