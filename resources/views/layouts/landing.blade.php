<!DOCTYPE html>
<html lang="cs">
<head>
    @php
        $metaTitle = View::hasSection('title') ? View::getSection('title') : config('app.name');
        $metaDesc = View::hasSection('description') ? View::getSection('description') : __('landing.meta.description');
        $ogImage = View::hasSection('og_image') ? View::getSection('og_image') : asset_v('img/og/og-default.jpg');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDesc !!}">
    <meta name="color-scheme" content="only dark">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{!! $metaTitle !!}">
    <meta property="og:description" content="{!! $metaDesc !!}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="cs_CZ">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDesc !!}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" type="image/png" href="{{ asset_v('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset_v('apple-touch-icon.png') }}" />
    <link rel="manifest" href="/site.webmanifest" />

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Service",
        "name": "{{ __('landing.meta.schema_name') }}",
        "description": "{{ __('landing.meta.description') }}",
        "provider": {
            "@@type": "Person",
            "name": "Ondřej Kriška"
        },
        "areaServed": "CZ",
        "serviceType": "Tvorba webových stránek na míru"
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen landing-body">
    <header class="landing-topbar">
        <div class="container-site landing-topbar__inner">
            <a href="{{ url()->current() }}" class="landing-topbar__brand" aria-label="{{ config('app.name') }}">
                <img
                    src="{{ asset_v('img/logo/logo_main_svg.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="220"
                    height="26"
                    class="landing-topbar__logo"
                >
            </a>

            <div class="landing-topbar__meta">
                <a href="mailto:ok@ondraweb.cz" class="landing-topbar__link">ok@ondraweb.cz</a>
                <a href="#lead-form" class="btn btn-primary">{{ __('landing.topbar.cta') }}</a>
            </div>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="landing-footer">
        <div class="container-site landing-footer__inner">
            <p>{{ __('landing.footer.copy') }}</p>
            <div class="landing-footer__links">
                <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>
                <a href="{{ lroute('privacy') }}">{{ __('landing.footer.privacy') }}</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
