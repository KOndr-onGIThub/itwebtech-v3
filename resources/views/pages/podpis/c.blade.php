{{-- ===================================================
     OND-227 — VARIANTA C „DÍLNA" (?podpis=c)
     Tmavá dílna × teplý papír. Portrét jako plakátový ČB tisk
     s amber deskou, klíčové slovo jako nalepený štítek,
     důkazy jako fyzické tisky na cream papíře (token
     --color-bg-inverse poprvé v roli). Podpis autora kurzívou.
     Prototyp = hero + sekce důkazů; texty beze změny z lang/.
     =================================================== --}}
@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))
@section('hide_prefooter', 'true')

@push('preloads')
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2') }}">
@endpush

@section('content')

<section class="pc-hero">
    <div class="container-site">
        <div class="pc-grid">
            <div>
                <p class="pc-eyebrow">{{ __('home.hero.page_mark_label') }} — {{ __('home.hero.upline') }}</p>

                <h1 class="pc-heading">{!! __('home.hero.heading_html') !!}</h1>

                <p class="pc-sub">{{ __('home.hero.subline') }}</p>

                {{-- Podpis autora — jméno ze schválené copy, kurzíva v body roli --}}
                <p class="pc-sign">&mdash; Ondřej Kriška</p>

                <div class="pc-actions">
                    <a href="#{{ __('home.anchors.poptavka') }}" class="pc-cta" data-analytics="hero_cta_primary_click">
                        {{ __('home.hero.cta_primary') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                    </a>
                    <x-phone-cta class="pc-phone" :label="__('home.hero.phone_label')" />
                </div>
                <p class="pc-note">{{ __('home.hero.note') }}</p>
            </div>

            <div class="pc-portrait" aria-hidden="true">
                <x-responsive-image
                    path="about/ondrej_kriska.jpg"
                    alt="{{ __('home.why_me.photo_alt') }}"
                    sizes="(min-width: 1024px) 380px, 0px"
                    loading="eager"
                    fetchpriority="high"
                />
            </div>
        </div>
    </div>
</section>

<section class="pc-paper">
    <div class="container-site">
        <h2 class="pc-paper__heading">{{ __('home.showcase.heading') }}</h2>
        <p class="pc-paper__intro">{{ __('home.showcase.intro') }}</p>

        <div class="pc-prints">
            @foreach (__('home.showcase.sites') as $i => $site)
            <a
                href="{{ $site['url'] }}"
                target="_blank"
                rel="noopener"
                class="pc-print"
                aria-label="{{ __('home.showcase.aria', ['domain' => $site['domain']]) }}"
                data-analytics="showcase_site_click"
                data-analytics-props='{"site":"{{ $site['slug'] }}"}'
            >
                <span class="pc-print__img">
                    <x-responsive-image
                        path="showcase/{{ $site['slug'] }}-desktop.webp"
                        alt="{{ $site['domain'] }} — {{ $site['desc'] }}"
                        sizes="(max-width: 767px) 100vw, 33vw"
                        loading="lazy"
                        decoding="async"
                        width="1600"
                        height="1000"
                    />
                </span>
                <h3 class="pc-print__domain">{{ $site['domain'] }}</h3>
                <p class="pc-print__desc">{{ $site['desc'] }}</p>
                <span class="pc-print__visit">{{ __('home.showcase.visit') }} &rarr;</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
