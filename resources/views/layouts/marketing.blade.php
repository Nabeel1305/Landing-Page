<!DOCTYPE html>
<html lang="en">
<head>
@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
@include('partials.nav-home')

@yield('content')

@include('partials.footer-home')

@yield('scripts')
</body>
</html>
