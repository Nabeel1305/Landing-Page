@props(['method' => 'GET', 'path', 'auth' => true, 'note' => null])
<div class="docs-endpoint">
  <span class="docs-method docs-method-{{ strtolower($method) }}">{{ $method }}</span>
  <code class="docs-endpoint-path">{{ $path }}</code>
  <span class="docs-endpoint-auth {{ $auth ? 'is-auth' : 'is-public' }}">{{ $auth ? 'Bearer token' : 'Public' }}</span>
  @if($note)<span class="docs-endpoint-note">{{ $note }}</span>@endif
</div>
