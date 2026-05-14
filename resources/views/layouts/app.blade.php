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
    @php
        $hreflangs = $hreflangs ?? [];
        $hreflangCs = $hreflangs['cs'] ?? lroute($currentPage, 'cs');
        $hreflangEn = $hreflangs['en'] ?? lroute($currentPage, 'en');
        $hreflangDe = $hreflangs['de'] ?? lroute($currentPage, 'de');
    @endphp
    <link rel="alternate" hreflang="cs" href="{{ $hreflangCs }}">
    <link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">
    <link rel="alternate" hreflang="de" href="{{ $hreflangDe }}">
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangCs }}">

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
        "email": "ok@itwebtech.cz",
        "description": "{{ __('layout.meta.description') }}",
        "founder": {
            "@@type": "Person",
            "name": "Ondřej Kriška"
        },
        "serviceType": ["Website Development", "Web Application Development", "SEO", "E-commerce"],
        "areaServed": ["CZ", "SK", "DE", "AT"]
    }
    </script>

    {{-- OND-130 iter 8 — JSON-LD Person sitewide pro E-E-A-T + author rich
         results. Komplementární k LocalBusiness (organization) + per-page
         Article (article entity). sameAs odkazuje na ověřitelný LinkedIn. --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Person",
        "name": "Ondřej Kriška",
        "url": "{!! url('/') !!}",
        "email": "ok@itwebtech.cz",
        "jobTitle": "Web developer",
        "worksFor": {
            "@@type": "Organization",
            "name": "{{ config('app.name') }}"
        },
        "knowsAbout": ["Web Development", "Custom Web Applications", "SEO", "B2B Websites"],
        "sameAs": [
            "https://www.linkedin.com/in/ondrejkriska/"
        ]
    }
    </script>

    {{-- OND-137 P4: Organization JSON-LD vedle LocalBusiness — Google
         doporučuje obě, LocalBusiness pro lokál + Organization pro brand. --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ config('app.name') }}",
        "url": "{!! url('/') !!}",
        "email": "ok@itwebtech.cz",
        "logo": "{{ asset('img/logo/logo_main_svg.svg') }}",
        "founder": {
            "@@type": "Person",
            "name": "Ondřej Kriška"
        }
    }
    </script>

    {{-- OND-137 P4: per-page JSON-LD slot (Service / Article / BreadcrumbList). --}}
    @stack('jsonld')

    @stack('preloads')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Analytics (OND-122) — Plausible / GA4 / Clarity, řízeno přes
         config/site.php (ANALYTICS_ENABLED + provider envs). --}}
    @include('partials.analytics')
</head>
<body class="min-h-screen flex flex-col">

    <x-layout.navbar :hreflangs="$hreflangs ?? []" />

    {{-- Mobile bottom bar (OND-100, T06) — viditelná akce na mobilu --}}
    @php
        $stickyPoptavkaHref = current_page() === 'home'
            ? '#' . __('home.anchors.poptavka')
            : lroute('home') . '#' . __('home.anchors.poptavka');
    @endphp
    <div class="mobile-bottom-bar" role="region" aria-label="{{ __('home.sticky.cta') }}">
        <a
            href="{{ $stickyPoptavkaHref }}"
            class="mobile-bottom-bar__primary"
            data-analytics="sticky_cta_click"
        >
            <span aria-hidden="true" class="mobile-bottom-bar__icon">💬</span>
            <span>{{ __('home.sticky.mobile') }}</span>
        </a>
        <x-phone-cta class="mobile-bottom-bar__phone" />
    </div>

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
                loading="lazy"
                decoding="async"
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
                <a href="{{ lroute('contact') }}"  class="footer-prefooter__link">{{ __('layout.nav.contact') }}</a>
            </nav>

        </div>
    </div>
    @endunless

    {{-- FOOTER BAR --}}
    <footer class="footer-bar">
        <div class="container-site footer-bar__inner">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }} — {{ __('layout.footer.rights') }}</span>
            <a href="{{ lroute('privacy') }}" class="footer-bar__gdpr-link">{{ __('layout.gdpr_form_link') }}</a>
            <a href="/cookies" class="footer-bar__gdpr-link">Cookies</a>
        </div>
    </footer>

    @stack('scripts')

    {{-- Consultation modal — video + Calendly CTA --}}
    <x-consultation-modal />

    {{-- Booking widget (Reservanto) — sekundární CTA, OND-116 (T15) --}}
    @if (config('site.booking.enabled'))
        <script defer id="reservanto-widget-script" type="text/javascript"
                src="{{ config('site.booking.script_url') }}"></script>
    @endif

    {{-- Cookie consent modal (OND-125) — gating pro GA4 + Microsoft Clarity.
         Renderuje se jen pokud je ANALYTICS_ENABLED=true a aspoň jeden
         z GA4/Clarity providerů má vyplněnou konfiguraci. --}}
    @include('partials.cookies-modal')
</body>
</html>
