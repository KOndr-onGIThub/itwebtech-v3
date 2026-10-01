<!DOCTYPE html>
@php $currentPage = current_page(); @endphp
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $metaTitle    = View::hasSection('title') ? View::getSection('title') : config('app.name');
        $metaDesc     = View::hasSection('description') ? View::getSection('description') : __('layout.meta.description');
        $ogImage      = View::hasSection('og_image') ? View::getSection('og_image') : asset_v('img/og/og-default.jpg');
        $ogLocale     = ['cs' => 'cs_CZ', 'en' => 'en_US', 'de' => 'de_DE'][app()->getLocale()] ?? 'cs_CZ';
        $ogAlternates = array_filter(['cs_CZ', 'en_US', 'de_DE'], fn($l) => $l !== $ogLocale);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- OND-231 F3: příznak „JS běží". Jen pod ním smí obsah startovat
         v opacity: 0 (scroll reveal) — bez JS by tam uvízl navždy. --}}
    <script>document.documentElement.classList.add('js');</script>
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
    <meta name="robots" content="{{ config('site.noindex') ? 'noindex, nofollow' : 'index, follow' }}">
    @php
        // OND-162 F4: canonical pro home musí mít trailing slash (web serveruje
        // `/` a `/en/`), aby seděla s hreflang URL z lroute('home', ...).
        $canonicalUrl = $currentPage === 'home' ? lroute('home') : url()->current();

        // OND-168 (2026-05-22): /cookies je nově lokalizovaný (cs.cookies /
        // en.cookies / de.cookies), takže sdílí standardní lroute() hreflang
        // chain s ostatními stránkami. Předchozí $isSharedCookiesPage special
        // case z OND-162 odebrán — už není potřeba.
        // OND-212: lroute_safe() kvůli chybovým stránkám — 404 z `cs.article`
        // se renderuje pod routou s povinným parametrem {slug}, který tu není.
        $hreflangs = $hreflangs ?? [];
        $hreflangCs = $hreflangs['cs'] ?? lroute_safe($currentPage, 'cs');
        $hreflangEn = $hreflangs['en'] ?? lroute_safe($currentPage, 'en');
        $hreflangDe = $hreflangs['de'] ?? lroute_safe($currentPage, 'de');
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="cs" href="{{ $hreflangCs }}">
    <link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">
    <link rel="alternate" hreflang="de" href="{{ $hreflangDe }}">
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangCs }}">

    {{-- OND-237: brand assety přes asset_v() — nginx na ně posílá `immutable`
         na rok, takže bez otisku v URL zůstane stará verze v cache navždy.
         `/favicon.ico` výjimku nepotřebuje: base image pro něj má vlastní
         `location = /favicon.ico` bez Cache-Control, revaliduje se normálně. --}}
    <link rel="icon" type="image/png" href="{{ asset_v('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset_v('apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}" />
    {{-- OND-244: `site.webmanifest` je statický soubor, takže na ikony uvnitř
         nedosáhne asset_v() — mají tam `?v=` otisk napsaný ručně. Když někdy
         měníš logo_icon.png / logo_square.png, přepiš i ten otisk v manifestu
         (`md5sum <soubor> | cut -c1-8`), jinak zůstanou staré v cache. --}}
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

    {{-- OND-130 iter 8 — JSON-LD Person sitewide pro E-E-A-T + author rich
         results. Komplementární k LocalBusiness (organization) + per-page
         Article (article entity). sameAs odkazuje na ověřitelný LinkedIn. --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Person",
        "name": "Ondřej Kriška",
        "url": "{!! url('/') !!}",
        "email": "ok@ondraweb.cz",
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
        "email": "ok@ondraweb.cz",
        "logo": "{{ asset_v('img/logo/logo_main_svg.svg') }}",
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

    {{-- OND-438: přednačtení detailu projektu a hlavních podstránek. --}}
    @include('partials.speculation-rules')

    {{-- Analytics (OND-122) — Plausible / GA4 / Clarity, řízeno přes
         config/site.php (ANALYTICS_ENABLED + provider envs). --}}
    @include('partials.analytics')
</head>
<body class="min-h-screen flex flex-col">

    <x-layout.navbar :hreflangs="$hreflangs ?? []" />

    {{-- Mobile bottom bar (OND-100, T06) — viditelná akce na mobilu.
         OND-437: po odeslání poptávky zmizí — výzva „Poptávka" pod potvrzením
         by zvala poslat ji znovu. OND-448: formulář se odesílá bez
         znovunačtení, takže třídu `is-lead-sent` na <body> přidá `contactForm`
         (podpis.css lištu schová a vrátí odsazení).
         OND-448 (B-01): lišta vede k formuláři, který je nejblíž — na homepage
         na sekci „Poptávka", na /kontakt na formulář na téže stránce, jinde na
         /kontakt (stejně jako tlačítko v menu). Dřív vedla z podstránek na
         homepage, i z /kontakt pryč od formuláře.
         OND-511: lišta žije jen uprostřed stránky — ukáže ji
         `resources/js/sticky-cta.js`, až z obrazovky odjede tlačítko hera
         nebo hlava podstránky (`data-sticky-cta="start"`), a schová ji
         u formuláře, závěrečné výzvy a patičky (`data-sticky-cta="hide"`).
         Server ji vykreslí skrytou (`inert`, bez `is-visible`), takže při
         načtení nebliká. Na /kontakt je formulář obsahem stránky a právní
         stránky výzvu nepotřebují — tam se nevykresluje vůbec. --}}
    @unless (in_array(current_page(), ['contact', 'privacy', 'cookies'], true))
    @php
        $stickyPoptavkaHref = current_page() === 'home'
            ? '#' . __('home.anchors.poptavka')
            : lroute('contact');
    @endphp
    <div class="mobile-bottom-bar" role="region" aria-label="{{ __('home.sticky.cta') }}" inert>
        <a
            href="{{ $stickyPoptavkaHref }}"
            class="mobile-bottom-bar__primary"
            data-analytics="sticky_cta_click"
        >
            <span>{{ __('home.sticky.mobile') }}</span>
        </a>
        <x-phone-cta class="mobile-bottom-bar__phone" />
    </div>
    @endunless

    <main class="site-main">
        @yield('content')
    </main>

    {{-- PATIČKA — OND-387 (základ podstránek §3c)
         Předpatička (logo na 50 %, claim, tlačítko „Napsat poptávku", navigace)
         je zrušená. Tlačítko bylo druhou až třetí kopií výzvy, kterou má
         stránka nad sebou, a na /kontakt odkazovalo samo na sebe; homepage
         si ji proto schovávala už od OND-201/5.8. Užitečná z ní byla jen
         navigace a claim — ty jsou teď tady. Právní stránky (OND-266) tím
         o cestu ven nepřicházejí: navigace je v patičce na každé stránce.
         Žádné tlačítko sem nepatří (§3b). --}}
    <footer class="footer-bar" data-sticky-cta="hide">
        <div class="container-site footer-bar__inner">

            <nav class="footer-bar__nav" aria-label="{{ __('layout.footer.nav_label') }}">
                @foreach (['home', 'projects', 'reviews', 'price', 'blog', 'about', 'contact'] as $footerRoute)
                    <a href="{{ lroute($footerRoute) }}"
                       class="footer-bar__nav-link"
                       @if(current_page() === $footerRoute) aria-current="page" @endif>{{ __('layout.nav.' . $footerRoute) }}</a>
                @endforeach
            </nav>

            <div class="footer-bar__base">
                <div class="footer-bar__brand">
                    <img
                        src="{{ asset_v('img/logo/logo_main_svg.svg') }}"
                        alt="{{ config('app.name') }}"
                        class="footer-bar__logo"
                        width="119" height="14"
                        loading="lazy"
                        decoding="async"
                    >
                    <p class="footer-bar__tagline">{{ __('layout.footer.tagline') }}</p>
                </div>

                <div class="footer-bar__meta">
                    <span>&copy; {{ date('Y') }} {{ config('app.name') }} — {{ __('layout.footer.rights') }}</span>
                    {{-- Telefon má default v config/contact.php; přebije ho env CONTACT_PHONE. --}}
                    @if(config('contact.phone'))
                    <a href="tel:{{ preg_replace('/\s+/', '', config('contact.phone')) }}" class="footer-bar__phone">{{ config('contact.phone') }}</a>
                    @endif
                    <a href="{{ lroute('privacy') }}" class="footer-bar__gdpr-link">{{ __('layout.footer_privacy_link') }}</a>
                    <a href="{{ lroute('cookies') }}" class="footer-bar__gdpr-link">{{ __('layout.cookies_link') }}</a>
                </div>
            </div>

        </div>
    </footer>

    @stack('scripts')

    {{-- Cookie consent modal (OND-125) — gating pro GA4 + Microsoft Clarity.
         Renderuje se jen pokud je ANALYTICS_ENABLED=true a aspoň jeden
         z GA4/Clarity providerů má vyplněnou konfiguraci. --}}
    @include('partials.cookies-modal')
</body>
</html>
