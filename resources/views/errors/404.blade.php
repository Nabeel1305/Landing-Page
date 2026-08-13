@extends('layouts.page')

@section('title', 'Page not found — PakaPay')
@section('description', 'This page could not be found.')
@section('canonical', 'https://pakapay.ng/404')
@section('robots', 'noindex')

@section('content')
<section class="err-wrap">
  <div class="wrap">
    <div class="err-code">404</div>
    <h1 class="err-title">This page went offline.</h1>
    <p class="err-sub">The link that brought you here doesn't match anything on PakaPay — it may have moved, or the address was mistyped.</p>
    <a href="{{ route('home') }}" class="btn btn-amber">Back to PakaPay</a>
  </div>
</section>
@endsection
