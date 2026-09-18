{{-- ===================================================
     OND-227 — VARIANTA A „VÝKRES" (?podpis=a)
     Konstrukční výkres inženýra: viditelná mřížka, kótovací
     čáry s reálnými čísly z lang, revizní rámeček kolem
     klíčového slova, razítkový blok autora. Prototyp =
     hero + sekce důkazů; texty beze změny z lang/.
     Drobné popisky razítka jsou zatím hardcoded — při
     finální implementaci se přesunou do lang/.
     =================================================== --}}
@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))
@section('hide_prefooter', 'true')

@push('preloads')
    {{-- Prototypova routa ?podpis=* nesmi do indexu (prevazi nad výchozím index,follow) --}}
    <meta name="robots" content="noindex, nofollow">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2') }}">
@endpush

@section('content')

<section class="pa-hero">
    <div class="container-site">
        <p class="pa-eyebrow">{{ __('home.hero.page_mark_label') }}</p>

        <p class="pa-upline">{{ __('home.hero.upline') }}</p>

        <h1 class="pa-heading">{!! __('home.hero.heading_html') !!}</h1>

        {{-- Kótovací čára — reálné údaje ze social proof, žádná dekorace --}}
        <p class="pa-dim" aria-label="{{ __('home.social_proof.experience') }}, {{ __('home.social_proof.projects') }}">
            <span>{{ __('home.social_proof.experience') }}</span>
            <span class="pa-dim__sep" aria-hidden="true">·</span>
            <span>{{ __('home.social_proof.projects') }}</span>
        </p>

        <p class="pa-sub">{{ __('home.hero.subline') }}</p>

        <div class="pa-actions">
            <a href="#{{ __('home.anchors.poptavka') }}" class="pa-cta" data-analytics="hero_cta_primary_click">
                {{ __('home.hero.cta_primary') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
            </a>
            <x-phone-cta class="pa-phone" :label="__('home.hero.phone_label')" />
        </div>
        <p class="pa-note">{{ __('home.hero.note') }}</p>

        {{-- Razítkový blok výkresu — podpis jednoho autora --}}
        <div class="pa-titleblock" role="presentation">
            <div class="pa-titleblock__cell">
                <span class="pa-titleblock__label">Navrhl a postavil</span>
                <span class="pa-titleblock__value">Ondřej Kriška</span>
            </div>
            <div class="pa-titleblock__cell">
                <span class="pa-titleblock__label">Kód</span>
                <span class="pa-titleblock__value">vlastní, bez šablony</span>
            </div>
            <div class="pa-titleblock__cell">
                <span class="pa-titleblock__label">List</span>
                <span class="pa-titleblock__value">01 — hero</span>
            </div>
            <div class="pa-titleblock__cell">
                <span class="pa-titleblock__label">Měřítko</span>
                <span class="pa-titleblock__value">1 : 1</span>
            </div>
        </div>
    </div>
</section>

<section class="pa-proof">
    <div class="container-site">
        <header class="pa-proof__head">
            <span class="pa-proof__index" aria-hidden="true">02</span>
            <h2 class="pa-proof__heading">{{ __('home.showcase.heading') }}</h2>
        </header>
        <p class="pa-proof__intro">{{ __('home.showcase.intro') }}</p>

        <div class="pa-rows">
            @foreach (__('home.showcase.sites') as $i => $site)
            <article class="pa-row">
                <div class="pa-row__body">
                    <p class="pa-row__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ $site['slug'] }}</p>
                    <h3 class="pa-row__domain">{{ $site['domain'] }}</h3>
                    <p class="pa-row__desc">{{ $site['desc'] }}</p>
                    <a
                        href="{{ $site['url'] }}"
                        target="_blank"
                        rel="noopener"
                        class="pa-row__cta"
                        aria-label="{{ __('home.showcase.aria', ['domain' => $site['domain']]) }}"
                        data-analytics="showcase_site_click"
                        data-analytics-props='{"site":"{{ $site['slug'] }}"}'
                    >{{ __('home.showcase.visit') }} &rarr;</a>
                </div>
                <div class="pa-row__media">
                    <span class="pa-plate">
                        <x-responsive-image
                            path="showcase/{{ $site['slug'] }}-desktop.webp"
                            alt="{{ $site['domain'] }} — {{ $site['desc'] }}"
                            sizes="(max-width: 1023px) 100vw, 58vw"
                            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                            decoding="async"
                            width="1600"
                            height="1000"
                        />
                    </span>
                    <p class="pa-row__caption" aria-hidden="true">{{ $site['domain'] }} — 1600 × 1000</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Kótovací pás — social proof jako řada měr s pravítkem --}}
<section class="pa-section pa-strip" aria-label="{{ __('home.social_proof.rating_aria') }}">
    <div class="container-site">
        <ul class="pa-strip__list">
            <li class="pa-strip__item"><span class="pa-strip__stars" aria-hidden="true">★★★★★</span> <strong>{{ __('home.social_proof.rating_value') }}</strong> {{ __('home.social_proof.reviews') }}</li>
            <li class="pa-strip__item"><strong>{{ __('home.social_proof.projects') }}</strong></li>
            <li class="pa-strip__item"><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li class="pa-strip__item">{{ __('home.social_proof.response') }}</li>
            <li class="pa-strip__item">{{ __('home.social_proof.award') }}</li>
        </ul>
    </div>
</section>

{{-- Metoda — Jak weby stavím + vymezení jako revizní poznámky --}}
<section class="pa-section">
    <div class="container-site">
        <header class="pa-proof__head">
            <span class="pa-proof__index" aria-hidden="true">03</span>
            <h2 class="pa-proof__heading">{{ __('home.problems.heading') }}</h2>
        </header>
        <p class="pa-lead">{{ __('home.problems.lead') }}</p>

        <p class="pa-avoid">{{ __('home.problems.transition_heading') }}</p>
        <p class="pa-avoid-sub">{{ __('home.problems.transition_text') }}</p>

        <div class="pa-issues">
            @foreach (__('home.problems.items') as $i => $item)
            <article class="pa-issue">
                <p class="pa-issue__num" aria-hidden="true">REV {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                <h3>{{ $item['heading'] }}</h3>
                <p>{{ $item['text'] }}</p>
                @if (!empty($item['quote_text']))
                <blockquote>
                    {{ $item['quote_text'] }}
                    <footer>— {{ $item['quote_author'] }}</footer>
                </blockquote>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Cenová kotva — tři tabulky výkresu --}}
<section class="pa-section">
    <div class="container-site">
        <header class="pa-proof__head">
            <span class="pa-proof__index" aria-hidden="true">04</span>
            <h2 class="pa-proof__heading">{{ __('home.price_anchor.heading') }}</h2>
        </header>
        <p class="pa-lead">{{ __('home.price_anchor.intro') }}</p>

        <div class="pa-price-grid">
            @foreach (__('home.price_anchor.items') as $item)
            <article class="pa-price-card {{ ($item['featured'] ?? false) ? 'pa-price-card--featured' : '' }}">
                @if ($item['featured'] ?? false)
                <span class="pa-price-card__badge">{{ __('home.price_anchor.featured_label') }}</span>
                @endif
                <div class="pa-price-card__head">
                    <h3 class="pa-price-card__title">{{ $item['title'] }}</h3>
                    <p class="pa-price-card__price">{{ $item['price'] }}</p>
                </div>
                <p class="pa-price-card__desc">{{ $item['desc'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Reference — ledger citací --}}
@php
    $allTestimonials = collect(__('testimonials.items'));
    $homeTestimonialOrder = config('site.features.show_toyota_testimonial')
        ? ['Pavel Baudyš', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice']
        : ['Peter Vidlička', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice'];
    $homeTestimonials = collect($homeTestimonialOrder)
        ->map(fn ($name) => $allTestimonials->firstWhere('name', $name))
        ->filter()
        ->values();
@endphp
<section class="pa-section">
    <div class="container-site">
        <header class="pa-proof__head">
            <span class="pa-proof__index" aria-hidden="true">05</span>
            <h2 class="pa-proof__heading">{{ __('home.testimonials.heading') }}</h2>
        </header>

        <div class="pa-testi-grid">
            @foreach ($homeTestimonials as $i => $review)
            <article class="pa-testi">
                <p class="pa-testi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ $review['source'] }}</p>
                <p class="pa-testi__text">{{ $review['text'] }}</p>
                <p class="pa-testi__meta"><strong>{{ $review['name'] }}</strong> — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

@endsection
