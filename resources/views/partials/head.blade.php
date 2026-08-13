<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'PakaPay')</title>
<meta name="description" content="@yield('description', "PakaPay is a mobile wallet built for Nigeria's markets.")">
@hasSection('robots')
<meta name="robots" content="@yield('robots')">
@endif
<meta property="og:title" content="@yield('title', 'PakaPay')">
<meta property="og:description" content="@yield('description')">
<meta property="og:type" content="website">
<meta property="og:url" content="@yield('canonical', url('/'))">
<meta property="og:image" content="{{ asset('og-image.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'PakaPay')">
<meta name="twitter:description" content="@yield('description')">
<meta name="twitter:image" content="{{ asset('og-image.png') }}">
<meta name="theme-color" content="#03556A">
<link rel="canonical" href="@yield('canonical', url('/'))">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/page.css') }}">
@stack('styles')

@stack('jsonld')
