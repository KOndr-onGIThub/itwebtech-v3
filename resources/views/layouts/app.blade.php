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
    <meta name="color-scheme" content="only dark">
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

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "LocalBusiness",
        "name": "{{ config('app.name') }}",
        "url": "{!! url('/') !!}",
        "email": "ok@ondraweb.cz",
        "description": "{{ __('layout.meta.description') }}",
        "founder": {
            "@@type": "Person",
            "name": "Ondřej Kriška"
        },
        "serviceType": ["Website Development", "Web Application Development", "SEO", "E-commerce"],
        "areaServed": ["CZ", "SK", "DE", "AT"]
    }
    </script>

    @stack('preloads')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    <x-layout.navbar />

    {{-- Floating Contact FAB — zobrazí se po scrollu --}}
    <a href="{{ lroute('contact') }}" class="contact-fab" id="contact-fab" aria-label="{{ __('layout.cta.contact') }}">
        <span class="contact-fab__btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.73a16 16 0 0 0 6.29 6.29l1.62-1.62a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            {{ __('layout.cta.contact') }}
        </span>
    </a>

    <main class="site-main">
        @yield('content')
    </main>

    {{-- PRE-FOOTER CTA — hide by adding @section('hide_prefooter') true @endsection on a page --}}
    @unless(View::hasSection('hide_prefooter'))
    <div class="footer-prefooter">
        <div class="container-site footer-prefooter__inner">

            <img
                src="{{ asset('img/logo/logo_main_svg.svg') }}"
                alt="{{ config('app.name') }}"
                class="footer-prefooter__logo"
                width="274" height="58"
            >

            <p class="footer-prefooter__tagline">
                {{ __('layout.prefooter.tagline') }}
            </p>

            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('layout.prefooter.cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>

            <nav class="footer-prefooter__nav" aria-label="{{ __('layout.prefooter.nav_label') }}">
                <a href="{{ lroute('home') }}"     class="footer-prefooter__link">{{ __('layout.nav.home') }}</a>
                <a href="{{ lroute('projects') }}" class="footer-prefooter__link">{{ __('layout.nav.projects') }}</a>
                <a href="{{ lroute('price') }}"    class="footer-prefooter__link">{{ __('layout.nav.price') }}</a>
                <a href="{{ lroute('blog') }}"     class="footer-prefooter__link">{{ __('layout.nav.blog') }}</a>
                <a href="{{ lroute('about') }}"    class="footer-prefooter__link">{{ __('layout.nav.about') }}</a>
                <a href="{{ lroute('contact') }}"  class="footer-prefooter__link">{{ __('layout.nav.contact') }}</a>
            </nav>

        </div>
    </div>
    @endunless

    {{-- FOOTER BAR --}}
    <footer class="footer-bar">
        <div class="container-site footer-bar__inner">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }} — {{ __('layout.footer.rights') }}</span>
            {{-- TODO ONDRA: telefonní číslo — zobrazí se automaticky, jakmile je CONTACT_PHONE vyplněné --}}
            @if(config('contact.phone'))
            <a href="tel:{{ preg_replace('/\s+/', '', config('contact.phone')) }}" class="footer-bar__phone">{{ config('contact.phone') }}</a>
            @endif
            <a href="{{ lroute('privacy') }}" class="footer-bar__gdpr-link">{{ __('layout.gdpr_form_link') }}</a>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
