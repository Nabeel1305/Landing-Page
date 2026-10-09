@props(['method' => 'GET', 'path', 'auth' => true, 'fields' => [], 'mutating' => true, 'highStakes' => false, 'warning' => null])
@php
  $pathFields = collect(preg_match_all('/\{(\w+)\}/', $path, $m) ? $m[1] : [])
      ->reject(fn ($n) => collect($fields)->contains(fn ($f) => $f['name'] === $n && ($f['in'] ?? '') === 'path'))
      ->map(fn ($n) => ['name' => $n, 'in' => 'path', 'type' => 'text', 'required' => true])->all();
  $all = array_merge($pathFields, $fields);
@endphp
<div class="try-it" data-method="{{ $method }}" data-path="{{ $path }}" data-auth="{{ $auth ? 1 : 0 }}" data-mutating="{{ $mutating ? 1 : 0 }}" data-high-stakes="{{ $highStakes ? 1 : 0 }}">
  <button type="button" class="try-it-toggle" data-try-toggle>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
    Try it
  </button>
  <div class="try-it-body" hidden>
    @if($auth)
      <div class="try-it-notice" data-try-authnotice hidden>This endpoint needs a bearer token. <a href="#" data-open-token onclick="return false">Set one</a> first.</div>
    @endif
    @if($warning)
      <div class="try-it-warning"><strong>Heads up:</strong> {{ $warning }}</div>
    @endif
    @foreach($all as $f)
      @php $type = $f['type'] ?? 'text'; @endphp
      <label class="try-it-field">
        <span class="try-it-label">{{ $f['name'] }} <em>{{ $f['in'] ?? 'body' }}{{ !empty($f['required']) ? ' · required' : '' }}</em></span>
        @if($type === 'boolean')
          <select data-field-name="{{ $f['name'] }}" data-field-in="{{ $f['in'] ?? 'body' }}" data-field-type="boolean" @if(!empty($f['required'])) data-required @endif>
            <option value="true">true</option><option value="false">false</option>
          </select>
        @elseif($type === 'select')
          <select data-field-name="{{ $f['name'] }}" data-field-in="{{ $f['in'] ?? 'body' }}" @if(!empty($f['required'])) data-required @endif>
            @foreach($f['options'] ?? [] as $o)<option value="{{ $o }}" @selected(($f['default'] ?? null) === $o)>{{ $o }}</option>@endforeach
          </select>
        @else
          <input type="{{ in_array($type, ['password', 'email']) ? $type : 'text' }}" autocomplete="off"
                 data-field-name="{{ $f['name'] }}" data-field-in="{{ $f['in'] ?? 'body' }}" data-field-type="{{ $type === 'number' ? 'number' : 'string' }}"
                 value="{{ $f['default'] ?? '' }}" placeholder="{{ $f['placeholder'] ?? '' }}" @if(!empty($f['required'])) data-required @endif>
        @endif
      </label>
    @endforeach
    @if($highStakes)
      <label class="try-it-field">
        <span class="try-it-label">Type CONFIRM to enable sending</span>
        <input type="text" autocomplete="off" data-try-confirm-input placeholder="CONFIRM">
      </label>
    @endif
    <button type="button" class="try-it-send" data-try-send @if($highStakes) disabled @endif>{{ $mutating ? 'Confirm & Send Real Request' : 'Send Request' }}</button>
    <div class="try-it-response" data-try-response hidden>
      <div class="try-it-meta"><span data-try-status></span><span data-try-time></span></div>
      <pre data-try-body></pre>
    </div>
  </div>
</div>
