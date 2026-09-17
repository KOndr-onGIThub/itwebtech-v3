{{-- ===================================================
     OND-227 — VARIANTA B „SAZBA" (?podpis=b)
     Typografický specimen: sazba je jediný obraz. Extrémy
     Plex variable osy (700 × 200), vlasové linky, marginálie,
     tiskařský monogram OK, kolofon. Amber jen jako inkoust.
     Žádné tlačítko, žádná karta — podtržené odkazy a rejstřík.
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

<section class="pb-hero">
    {{-- Tiskařský monogram — iniciály autora jako značka v pozadí --}}
    <span class="pb-mark" aria-hidden="true">OK</span>

    <div class="container-site">
        <div class="pb-grid">
            <aside class="pb-margin">
                <span class="pb-margin__index" aria-hidden="true">01</span>
                {{ __('home.hero.page_mark_label') }}
            </aside>

            <div>
                <p class="pb-upline">{{ __('home.hero.upline') }}</p>

                <h1 class="pb-heading">{!! __('home.hero.heading_html') !!}</h1>

                <p class="pb-sub">{{ __('home.hero.subline') }}</p>

                <div class="pb-actions">
                    <a href="#{{ __('home.anchors.poptavka') }}" class="pb-cta" data-analytics="hero_cta_primary_click">
                        {{ __('home.hero.cta_primary') }}
                        <span class="pb-cta__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                    <x-phone-cta class="pb-phone" :label="__('home.hero.phone_label')" />
                </div>
                <p class="pb-note">{{ __('home.hero.note') }}</p>

                {{-- Kolofon — tiráž sazeče (jméno autora ze schválené copy) --}}
                <p class="pb-colophon">Ondřej Kriška</p>
            </div>
        </div>
    </div>
</section>

<section class="pb-proof">
    <div class="container-site">
        <header class="pb-proof__head">
            <h2 class="pb-proof__heading">{{ __('home.showcase.heading') }}</h2>
            <span class="pb-proof__index" aria-hidden="true">02</span>
        </header>
        <p class="pb-proof__intro">{{ __('home.showcase.intro') }}</p>

        <div class="pb-entries">
            @foreach (__('home.showcase.sites') as $i => $site)
            <a
                href="{{ $site['url'] }}"
                target="_blank"
                rel="noopener"
                class="pb-entry"
                aria-label="{{ __('home.showcase.aria', ['domain' => $site['domain']]) }}"
                data-analytics="showcase_site_click"
                data-analytics-props='{"site":"{{ $site['slug'] }}"}'
            >
                <span class="pb-entry__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="pb-entry__body">
                    <h3 class="pb-entry__domain">{{ $site['domain'] }}</h3>
                    <p class="pb-entry__desc">{{ $site['desc'] }}</p>
                    <span class="pb-entry__visit">{{ __('home.showcase.visit') }} &rarr;</span>
                </span>
                <span class="pb-entry__plate">
                    <x-responsive-image
                        path="showcase/{{ $site['slug'] }}-desktop.webp"
                        alt="{{ $site['domain'] }} — {{ $site['desc'] }}"
                        sizes="(max-width: 1023px) 100vw, 340px"
                        loading="lazy"
                        decoding="async"
                        width="1600"
                        height="1000"
                    />
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
