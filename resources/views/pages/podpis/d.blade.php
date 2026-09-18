{{-- ===================================================
     OND-227 — VARIANTA D „STUDIO" (?podpis=d&barva=acid|klein|sarlat)
     Iterace 4 (Ondra 18. 9.: „Dark web ano, ale jinak a moderněji.
     Jiné barvy"). Kompozice vítězné C zůstává (fotka nese hero,
     autogram, přímočarost), retro kulisy jdou pryč: žádný papír,
     štítky, grain, rotace, patina. Povrch = současný studiový
     jazyk: vlasové linky, hodně negativního prostoru, ostrá
     typografie, přesný motion. Amber končí — tři barevné směry
     na stejném layoutu, každý s pojmenovanou rolí barvy:
       acid   #D8FF3A — barva jako signální inkoust akcí
       klein  #3B5BFF — barva jako plocha/materiál
       sarlat #FF3B30 — barva jako vzácný tah, CTA bílé
     Texty beze změny z lang/.
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

@php
    $barva = request()->query('barva');
    if (!in_array($barva, ['acid', 'klein', 'sarlat'], true)) {
        $barva = 'acid';
    }

    $allTestimonials = collect(__('testimonials.items'));
    $homeTestimonialOrder = config('site.features.show_toyota_testimonial')
        ? ['Pavel Baudyš', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice']
        : ['Peter Vidlička', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice'];
    $homeTestimonials = collect($homeTestimonialOrder)
        ->map(fn ($name) => $allTestimonials->firstWhere('name', $name))
        ->filter()
        ->values();
@endphp

@section('content')
<div class="pd pd--{{ $barva }}">

{{-- Hero — kompozice z C: fotka „tak jak je" přes pravou část,
     text v negativním prostoru vlevo, autogram, tilt. Bez grainu
     a light-leaku (patina pryč). --}}
<section class="pd-hero" id="pd-hero-tilt">
    <div class="pd-hero__photo" data-tilt>
        <x-responsive-image
            path="hero/hero-uvod.webp"
            alt="Ondřej Kriška"
            sizes="(min-width: 1024px) 54vw, 100vw"
            loading="eager"
            fetchpriority="high"
        />
    </div>
    <div class="container-site">
        <div class="pd-hero__content">
            <p class="pd-eyebrow">{{ __('home.hero.page_mark_label') }} — {{ __('home.hero.upline') }}</p>

            <h1 class="pd-heading">{!! __('home.hero.heading_html') !!}</h1>

            <p class="pd-sub">{{ __('home.hero.subline') }}</p>

            <p class="pd-sign">
                &mdash; Ondřej Kriška
                <svg class="pd-sign__mark" viewBox="0 0 220 60" fill="none" aria-hidden="true">
                    <path d="M4 40C16 12 28 8 34 26C40 44 46 20 54 18C62 16 60 38 70 38C82 38 84 10 96 10C110 10 104 44 118 44C136 44 132 14 150 14C166 14 158 34 172 30C182 27 184 16 194 16C202 16 200 26 210 24"
                          stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </p>

            <div class="pd-actions">
                <a href="#{{ __('home.anchors.poptavka') }}" class="pd-cta" data-analytics="hero_cta_primary_click">
                    {{ __('home.hero.cta_primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                </a>
                <x-phone-cta class="pd-phone" :label="__('home.hero.phone_label')" />
            </div>
            <p class="pd-note">{{ __('home.hero.note') }}</p>
        </div>
    </div>
</section>

{{-- Živé weby — flat desky, vlasové linky, přesný hover --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.showcase.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">02</span>
        </header>
        <p class="pd-intro">{{ __('home.showcase.intro') }}</p>

        <div class="pd-works">
            @foreach (__('home.showcase.sites') as $i => $site)
            <a
                href="{{ $site['url'] }}"
                target="_blank"
                rel="noopener"
                class="pd-work"
                aria-label="{{ __('home.showcase.aria', ['domain' => $site['domain']]) }}"
                data-analytics="showcase_site_click"
                data-analytics-props='{"site":"{{ $site['slug'] }}"}'
            >
                <span class="pd-work__plate">
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
                <span class="pd-work__row">
                    <h3 class="pd-work__domain">{{ $site['domain'] }}</h3>
                    <span class="pd-work__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </span>
                <p class="pd-work__desc">{{ $site['desc'] }}</p>
                <span class="pd-work__visit">{{ __('home.showcase.visit') }} &rarr;</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Social proof — jeden přesný řádek; u směru KLEIN celá plocha barvou --}}
<section class="pd-strip" aria-label="{{ __('home.social_proof.rating_aria') }}">
    <div class="container-site">
        <ul class="pd-strip__list">
            <li><strong>{{ __('home.social_proof.rating_value') }}</strong> {{ __('home.social_proof.reviews') }}</li>
            <li><strong>{{ __('home.social_proof.projects') }}</strong></li>
            <li><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li>{{ __('home.social_proof.response') }}</li>
            <li>{{ __('home.social_proof.award') }}</li>
        </ul>
    </div>
</section>

{{-- Metoda — velké tenké číslice, žádná dekorace --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.problems.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">03</span>
        </header>
        <p class="pd-lead">{{ __('home.problems.lead') }}</p>

        <p class="pd-avoid">{{ __('home.problems.transition_heading') }}</p>
        <p class="pd-avoid-sub">{{ __('home.problems.transition_text') }}</p>

        <div class="pd-issues">
            @foreach (__('home.problems.items') as $i => $item)
            <article class="pd-issue">
                <span class="pd-issue__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
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

{{-- Cenová kotva — tři sloupce, vlasové linky, žádné boxy --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.price_anchor.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">04</span>
        </header>
        <p class="pd-intro">{{ __('home.price_anchor.intro') }}</p>

        <div class="pd-price">
            @foreach (__('home.price_anchor.items') as $item)
            <div class="pd-price__col {{ ($item['featured'] ?? false) ? 'pd-price__col--featured' : '' }}">
                <h3 class="pd-price__title">{{ $item['title'] }}@if ($item['featured'] ?? false) <em>{{ __('home.price_anchor.featured_label') }}</em>@endif</h3>
                <p class="pd-price__value">{{ $item['price'] }}</p>
                <p class="pd-price__desc">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Reference — přesná mřížka citací --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.testimonials.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">05</span>
        </header>

        <div class="pd-testi">
            @foreach ($homeTestimonials as $i => $review)
            <article class="pd-testi__item">
                <span class="pd-testi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <p class="pd-testi__text">{{ $review['text'] }}</p>
                    <p class="pd-testi__meta">{{ $review['name'] }} — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
    // Tilt portrétu převzatý z C — vanilla, no-op na touch a reduced-motion.
    (function () {
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var hasHover = window.matchMedia('(hover: hover)').matches;
        if (reduceMotion || !hasHover) return;

        var section = document.getElementById('pd-hero-tilt');
        var photo = section && section.querySelector('[data-tilt]');
        if (!photo) return;

        section.addEventListener('mousemove', function (e) {
            var rect = section.getBoundingClientRect();
            var px = (e.clientX - rect.left) / rect.width - 0.5;
            var py = (e.clientY - rect.top) / rect.height - 0.5;
            photo.style.transform = 'scale(1.02) rotate(' + (px * -0.6) + 'deg) translate(' + (px * -8) + 'px, ' + (py * -6) + 'px)';
        });
        section.addEventListener('mouseleave', function () {
            photo.style.transform = '';
        });
    })();
</script>
@endpush
