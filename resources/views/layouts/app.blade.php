<!DOCTYPE html>
@php $currentPage = current_page(); @endphp
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $metaTitle    = View::hasSection('title') ? View::getSection('title') : config('app.name');
        $metaDesc     = View::hasSection('description') ? View::getSection('description') : __('layout.meta.description');
        $ogImage      = View::hasSection('og_image') ? View::getSection('og_image') : asset('img/og/og-default.jpg');
        $ogLocale     = ['cs' => 'cs_CZ', 'en' => 'en_US', 'de' => 'de_DE'][app()->getLocale()] ?? 'cs_CZ';
        $ogAlternates = array_filter(['cs_CZ', 'en_US', 'de_DE'], fn($l) => $l !== $ogLocale);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDesc !!}">
    {{-- prevent dark mode --}}
    <meta name="color-scheme" content="only light">
    <!-- Open Graph -->
    <meta property="og:type"         content="website">
    <meta property="og:url"          content="{{ url()->current() }}">
    <meta property="og:title"        content="{!! $metaTitle !!}">
    <meta property="og:description"  content="{!! $metaDesc !!}">
    <meta property="og:image"        content="{{ $ogImage }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale"       content="{{ $ogLocale }}">
    @foreach($ogAlternates as $alt)
    <meta property="og:locale:alternate" content="{{ $alt }}">
    @endforeach
    <meta property="og:site_name"    content="{{ config('app.name') }}">
    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDesc !!}">
    <meta name="twitter:image"       content="{{ $ogImage }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="cs" href="{{ lroute($currentPage, 'cs') }}">
    <link rel="alternate" hreflang="en" href="{{ lroute($currentPage, 'en') }}">
    <link rel="alternate" hreflang="de" href="{{ lroute($currentPage, 'de') }}">
    <link rel="alternate" hreflang="x-default" href="{{ lroute($currentPage, 'cs') }}">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}" />
    <link rel="manifest" href="/site.webmanifest" />

    {{-- TODO: add your JSON-LD Organization schema here --}}
    {{-- Example:
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ config('app.name') }}",
        "url": "{!! url('/') !!}"
    }
    </script>
    --}}

    @stack('preloads')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white flex flex-col">

    <x-layout.navbar />

    <main class="site-main">
        @yield('content')
    </main>

    {{-- PRE-FOOTER CTA — hide by adding @section('hide_prefooter') true @endsection on a page --}}
    @unless(View::hasSection('hide_prefooter'))
    <div class="footer-wave" aria-hidden="true">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,80 L1440,80 L1440,60 C1080,0 360,0 0,60 Z" style="fill: var(--bg-neutral)"/>
        </svg>
    </div>
    <div class="footer-prefooter">
        <div class="container-site footer-prefooter__inner">

            {{-- TODO: replace with your logo --}}
            <img
                src="{{ asset('img/logo/logo_main_svg.svg') }}"
                alt="{{ config('app.name') }}"
                class="footer-prefooter__logo"
                width="274" height="58"
            >

            <p class="footer-prefooter__tagline">
                {{ __('layout.prefooter.tagline') }}
            </p>

            {{-- TODO: update CTA route --}}
            <a href="{{ lroute('home') }}" class="btn btn-primary">
                {{ __('layout.prefooter.cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>

            <nav class="footer-prefooter__nav" aria-label="{{ __('layout.prefooter.nav_label') }}">
                <a href="{{ lroute('home') }}" class="footer-prefooter__link">{{ __('layout.nav.home') }}</a>
                {{-- TODO: add your footer nav links here --}}
            </nav>

        </div>
    </div>
    @endunless

    {{-- FOOTER BAR --}}
    <footer class="footer-bar">
        <div class="container-site footer-bar__inner">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
            {{-- TODO: add privacy policy link if needed --}}
            {{-- <a href="{{ lroute('gdpr') }}" class="footer-bar__gdpr-link">{{ __('layout.gdpr_form_link') }}</a> --}}
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
