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

{{-- Dateline — social proof jako sázený řádek novin --}}
<section class="pb-section pb-strip" aria-label="{{ __('home.social_proof.rating_aria') }}">
    <div class="container-site">
        <ul class="pb-strip__list">
            <li><strong>{{ __('home.social_proof.rating_value') }}</strong> {{ __('home.social_proof.reviews') }}</li>
            <li><strong>{{ __('home.social_proof.projects') }}</strong></li>
            <li><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li>{{ __('home.social_proof.response') }}</li>
            <li>{{ __('home.social_proof.award') }}</li>
        </ul>
    </div>
</section>

{{-- Metoda — standfirst + rejstřík vymezení --}}
<section class="pb-section">
    <div class="container-site">
        <header class="pb-proof__head">
            <h2 class="pb-proof__heading">{{ __('home.problems.heading') }}</h2>
            <span class="pb-proof__index" aria-hidden="true">03</span>
        </header>
        <p class="pb-standfirst">{{ __('home.problems.lead') }}</p>

        <p class="pb-avoid">{{ __('home.problems.transition_heading') }}</p>
        <p class="pb-avoid-sub">{{ __('home.problems.transition_text') }}</p>

        <div class="pb-issues">
            @foreach (__('home.problems.items') as $i => $item)
            <article class="pb-issue">
                <span class="pb-issue__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h3>{{ $item['heading'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                    @if (!empty($item['quote_text']))
                    <blockquote>
                        {{ $item['quote_text'] }}
                        <footer>— {{ $item['quote_author'] }}</footer>
                    </blockquote>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Cenová kotva — tři sloupce s vlasovými linkami, žádné boxy --}}
<section class="pb-section">
    <div class="container-site">
        <header class="pb-proof__head">
            <h2 class="pb-proof__heading">{{ __('home.price_anchor.heading') }}</h2>
            <span class="pb-proof__index" aria-hidden="true">04</span>
        </header>
        <p class="pb-proof__intro">{{ __('home.price_anchor.intro') }}</p>

        <div class="pb-price-grid">
            @foreach (__('home.price_anchor.items') as $item)
            <div class="pb-price-col">
                <h3 class="pb-price-col__title">{{ $item['title'] }}@if ($item['featured'] ?? false) <em>· {{ __('home.price_anchor.featured_label') }}</em>@endif</h3>
                <p class="pb-price-col__price">{{ $item['price'] }}</p>
                <p class="pb-price-col__desc">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Reference — specimen citací --}}
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
<section class="pb-section">
    <div class="container-site">
        <header class="pb-proof__head">
            <h2 class="pb-proof__heading">{{ __('home.testimonials.heading') }}</h2>
            <span class="pb-proof__index" aria-hidden="true">05</span>
        </header>

        <div class="pb-testi-grid">
            @foreach ($homeTestimonials as $i => $review)
            <article class="pb-testi">
                <span class="pb-testi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <p class="pb-testi__text">{{ $review['text'] }}</p>
                    <p class="pb-testi__meta">{{ $review['name'] }} — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

@endsection
