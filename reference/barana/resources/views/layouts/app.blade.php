<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @php
        $metaTitle = View::hasSection('title') ? View::getSection('title') : config('app.name') . ' — Bioklimatické pergoly, brány a ploty';
        $metaDesc  = View::hasSection('description') ? View::getSection('description') : 'BARANA s.r.o. — navrhujeme, vyrábíme a montujeme prémiové hliníkové bioklimatické pergoly, brány a ploty v Jihomoravském kraji.';
        $ogImage   = View::hasSection('og_image') ? View::getSection('og_image') : asset('img/og/og-default.jpg');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDesc !!}">
    <meta name="color-scheme" content="only light">

    <!-- Open Graph -->
    <meta property="og:type"         content="website">
    <meta property="og:url"          content="{{ url()->current() }}">
    <meta property="og:title"        content="{!! $metaTitle !!}">
    <meta property="og:description"  content="{!! $metaDesc !!}">
    <meta property="og:image"        content="{{ $ogImage }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name"    content="BARANA s.r.o.">
    <meta property="og:locale"       content="cs_CZ">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDesc !!}">
    <meta name="twitter:image"       content="{{ $ogImage }}">

    <meta name="app-env" content="{{ app()->environment() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="BARANA" />
    <link rel="manifest" href="/site.webmanifest" />

    <!-- JSON-LD Organization schema -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "LocalBusiness",
        "name": "BARANA s.r.o.",
        "description": "Prémiové bioklimatické pergoly, hliníkové brány a ploty na míru — Jihomoravský kraj",
        "url": "{{ url('/') }}",
        "telephone": "+420123456789",
        "email": "info@barana.cz",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Hlavní 49",
            "addressLocality": "Březí",
            "postalCode": "69181",
            "addressCountry": "CZ"
        },
        "areaServed": {
            "@@type": "AdministrativeArea",
            "name": "Jihomoravský kraj"
        },
        "priceRange": "$$",
        "openingHours": "Mo-Fr 08:00-17:00",
        "sameAs": [
            "https://www.facebook.com/baranasro",
            "https://www.instagram.com/baranasro"
        ]
    }
    </script>

    @stack('preloads')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    <x-layout.navbar />

    <main class="site-main">
        @yield('content')
    </main>

    {{-- PRE-FOOTER CTA — potlačit přidáním @section('hide_prefooter') true @endsection na stránce --}}
    @unless(View::hasSection('hide_prefooter'))
    <div class="footer-prefooter">
        <div class="footer-prefooter__inner container-site">

            <img
                src="{{ asset('assets/img/logo_main.svg') }}"
                alt="BARANA s.r.o."
                class="footer-prefooter__logo"
                width="274" height="58"
            >

            <p class="footer-prefooter__tagline">
                Vytvořte si prostor, ve kterém budete chtít trávit čas.
            </p>

            <a href="{{ route('kontakt') }}" class="btn btn-primary btn-lg">
                Nezávazně poptat
                <x-icon.arrow-right class="w-5 h-5 shrink-0" />
            </a>

            <nav class="footer-prefooter__nav" aria-label="Patička navigace">
                <a href="{{ route('home') }}"          class="footer-prefooter__link">Úvod</a>
                <a href="{{ route('pergoly') }}"       class="footer-prefooter__link">Pergoly</a>
                <a href="{{ route('brany-a-ploty') }}" class="footer-prefooter__link">Brány a ploty</a>
                <a href="{{ route('o-nas') }}"         class="footer-prefooter__link">O nás</a>
                <a href="{{ route('jak-to-probiha') }}" class="footer-prefooter__link">Jak to probíhá</a>
                <a href="{{ route('realizace') }}"     class="footer-prefooter__link">Realizace</a>
                <a href="{{ route('kontakt') }}"       class="footer-prefooter__link">Kontakt</a>
            </nav>

        </div>
    </div>
    @endunless

    {{-- FOOTER BAR --}}
    <footer class="footer-bar">
        <div class="footer-bar__inner container-site">
            <span>&copy; {{ date('Y') }} BARANA s.r.o. &nbsp;·&nbsp; IČO: 24568341</span>
            <div class="footer-bar__links">
                <a href="{{ route('zasady-cookies') }}">Zásady cookies</a>
                <a href="{{ route('ochrana-osobnich-udaju') }}">Ochrana osobních údajů</a>
            </div>
        </div>
        <div class="footer-credit">
            Web vytvořil: <a href="https://ondraweb.cz" class="footer-credit__link" target="_blank" rel="noopener">OndraWeb.cz 🍀</a>
        </div>
    </footer>

    {{-- Floating contact FAB (appears after scrolling) --}}
    <div
        x-data="contactFab()"
        x-init="init()"
        :class="{ 'is-visible': visible }"
        class="contact-fab flex"
        x-cloak
    >
        <div
            x-show="open"
            class="flex flex-col items-end gap-2"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
        >
            <a href="tel:+420123456789" class="contact-fab__action">
                <x-icon.phone class="w-4 h-4 shrink-0" />
                +420 123 456 789
            </a>
            <a href="{{ route('kontakt') }}" class="contact-fab__action contact-fab__action--primary">
                Poptat zdarma
                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
            </a>
        </div>
        <button
            @click="open = !open"
            class="contact-fab__toggle"
            :aria-label="open ? 'Zavřít kontakt' : 'Otevřít kontakt'"
            :aria-expanded="open"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>


    @stack('scripts')

    <x-cookie-banner />
</body>
</html>
