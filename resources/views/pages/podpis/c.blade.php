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

{{-- Hero foto: Ondrův pokyn 2026-09-17 — hero-uvod.webp „tak jak je"
     přes velkou část hero sekce, bez filtrů a bez overlaye přes obličej;
     dlouhovlasý portrét (about/ondrej_kriska.jpg) se nepoužívá nikde. --}}
<section class="pc-hero" id="pc-hero-tilt">
    {{-- Zrnitá textura (SVG feTurbulence, CSS-only) — hmatový, "drahý" povrch
         přes tmavé plochy. Statická, žádná animace, nulové riziko pro FCP. --}}
    <div class="pc-grain" aria-hidden="true"></div>

    <div class="pc-hero__photo" data-tilt>
        <x-responsive-image
            path="hero/hero-uvod.webp"
            alt="Ondřej Kriška"
            sizes="(min-width: 1024px) 54vw, 100vw"
            loading="eager"
            fetchpriority="high"
        />
    </div>
    <div class="container-site">
        <div class="pc-hero__content">
            <p class="pc-eyebrow">{{ __('home.hero.page_mark_label') }} — {{ __('home.hero.upline') }}</p>

            <h1 class="pc-heading">{!! __('home.hero.heading_html') !!}</h1>

            <p class="pc-sub">{{ __('home.hero.subline') }}</p>

            {{-- Podpis autora — jméno ze schválené copy + kreslený autogram
                 jako grafická značka. Doslovné naplnění zadání "vizuální
                 podpis": ne metafora, ale skutečný rukopisný tah vedle jména.
                 Statický SVG, žádná animace vázaná na viditelnost obsahu. --}}
            <p class="pc-sign">
                &mdash; Ondřej Kriška
                <svg class="pc-sign__mark" viewBox="0 0 220 60" fill="none" aria-hidden="true">
                    <path d="M4 40C16 12 28 8 34 26C40 44 46 20 54 18C62 16 60 38 70 38C82 38 84 10 96 10C110 10 104 44 118 44C136 44 132 14 150 14C166 14 158 34 172 30C182 27 184 16 194 16C202 16 200 26 210 24"
                          stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </p>

            <div class="pc-actions">
                <a href="#{{ __('home.anchors.poptavka') }}" class="pc-cta" data-analytics="hero_cta_primary_click">
                    {{ __('home.hero.cta_primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
                <x-phone-cta class="pc-phone" :label="__('home.hero.phone_label')" />
            </div>
            <p class="pc-note">{{ __('home.hero.note') }}</p>
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

{{-- Štítkový pás — social proof jako razítka/štítky --}}
<section class="pc-section-dark pc-strip" aria-label="{{ __('home.social_proof.rating_aria') }}">
    <div class="container-site">
        <ul class="pc-strip__list">
            <li class="pc-strip__item pc-strip__item--accent"><span class="pc-strip__stars" aria-hidden="true">★★★★★</span> {{ __('home.social_proof.rating_value') }} {{ __('home.social_proof.reviews') }}</li>
            <li class="pc-strip__item">{{ __('home.social_proof.projects') }}</li>
            <li class="pc-strip__item">{{ __('home.social_proof.experience') }}</li>
            <li class="pc-strip__item">{{ __('home.social_proof.response') }}</li>
            <li class="pc-strip__item">{{ __('home.social_proof.award') }}</li>
        </ul>
    </div>
</section>

{{-- Metoda — tmavá dílna, citace jako papírový lísteček --}}
<section class="pc-section-dark">
    <div class="container-site">
        <h2 class="pc-paper__heading" style="color: var(--color-fg-primary)">{{ __('home.problems.heading') }}</h2>
        <p class="pc-lead">{{ __('home.problems.lead') }}</p>

        <p class="pc-avoid">{{ __('home.problems.transition_heading') }}</p>
        <p class="pc-avoid-sub">{{ __('home.problems.transition_text') }}</p>

        <div class="pc-issues">
            @foreach (__('home.problems.items') as $item)
            <article class="pc-issue">
                <h3>{{ $item['heading'] }}</h3>
                <p>{{ $item['text'] }}</p>
                @if (!empty($item['quote_text']))
                <blockquote class="pc-papernote">
                    {{ $item['quote_text'] }}
                    <footer>— {{ $item['quote_author'] }}</footer>
                </blockquote>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Cenová kotva — tištěný ceník na papíře --}}
<section class="pc-section-paper">
    <div class="container-site">
        <h2 class="pc-paper__heading">{{ __('home.price_anchor.heading') }}</h2>
        <p class="pc-paper__intro">{{ __('home.price_anchor.intro') }}</p>

        <ul class="pc-price-list">
            @foreach (__('home.price_anchor.items') as $item)
            <li class="pc-price-row">
                <div class="pc-price-row__line">
                    <h3 class="pc-price-row__title">{{ $item['title'] }}@if ($item['featured'] ?? false)<span class="pc-price-row__badge">{{ __('home.price_anchor.featured_label') }}</span>@endif</h3>
                    <span class="pc-price-row__leader" aria-hidden="true"></span>
                    <span class="pc-price-row__price">{{ $item['price'] }}</span>
                </div>
                <p class="pc-price-row__desc">{{ $item['desc'] }}</p>
            </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Reference — lístky na stole dílny --}}
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
<section class="pc-section-dark">
    <div class="container-site">
        <h2 class="pc-paper__heading" style="color: var(--color-fg-primary)">{{ __('home.testimonials.heading') }}</h2>

        <div class="pc-testi-grid">
            @foreach ($homeTestimonials as $review)
            <article class="pc-testi">
                <p class="pc-testi__text">{{ $review['text'] }}</p>
                <p class="pc-testi__meta">
                    <strong>{{ $review['name'] }}</strong>
                    {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif
                    <span class="pc-testi__stars" aria-hidden="true">★★★★★</span>
                </p>
            </article>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Ondra 2026-09-17: "chybí wow, co mají jen nejlepší weby světa" — jemný
    // paralax náklon portrétu podle kurzoru. Vanilla JS, ~15 řádků, žádná
    // knihovna. No-op na dotykových zařízeních a při prefers-reduced-motion
    // (fotka zůstává v klidu, nic se neskrývá ani nestartuje neviditelné).
    (function () {
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var hasHover = window.matchMedia('(hover: hover)').matches;
        if (reduceMotion || !hasHover) return;

        var section = document.getElementById('pc-hero-tilt');
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
