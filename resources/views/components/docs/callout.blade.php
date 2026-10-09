@props(['type' => 'note', 'title' => null])
<div class="docs-callout docs-callout-{{ $type }}">
  @if($title)<div class="docs-callout-title">{{ $title }}</div>@endif
  <div class="docs-callout-body">{{ $slot }}</div>
</div>
