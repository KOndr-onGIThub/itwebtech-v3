@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))

@section('content')

{{-- ===================================================
     HERO
     =================================================== --}}
<section class="section-hero">
    <x-responsive-image
        path="hero/itwebtech_3.webp"
        alt="Ondřej Kriška — webové stránky a aplikace"
        sizes="100vw"
        loading="eager"
        fetchpriority="high"
        classPicture="section-hero__bg-picture"
        classImg="section-hero__bg-img"
    />

    <div class="container-site section-hero__inner">
        <div class="section-hero__content">
            {{-- OND-135 P2 (plán §3.1) — page-mark eyebrow s indexem.
                 Nahrazuje T08 eyebrow „Webové stránky a aplikace na míru"
                 (přesunut do meta/intro v dolních sekcích). --}}
            <p class="section-hero__page-mark">
                <span class="section-hero__page-mark-label">{{ __('home.hero.page_mark_label') }}</span>
                <span class="section-hero__page-mark-index" aria-hidden="true">{{ __('home.hero.page_mark_index') }}</span>
            </p>

            {{-- §3.1 upline (Fraunces muted, 40 px) — kontext před display headingem --}}
            <p class="section-hero__upline">{{ __('home.hero.upline') }}</p>

            <h1 class="section-hero__heading">
                {{-- heading_html obsahuje <br> a <em> pro key-word amber emphasis.
                     Copy je owned by Content Writer (OND-136 P3) — provizorně
                     plněno per plán §3.1, P3 ladí finální tone v CS/EN/DE. --}}
                {!! __('home.hero.heading_html') !!}
            </h1>

            <p class="section-hero__subline">{{ __('home.hero.subline') }}</p>

            <div class="section-hero__actions">
                {{-- OND-130 (B2 §1, klíčová direktiva 3 + plán §3.1):
                     V hero **jediný** primary CTA „Chci nezávaznou nabídku".
                     Reservanto (Calendly-style booking) zůstává jako sekundární CTA
                     v sekci „Jak pracuji" a v final CTA — v hero by soutěžil
                     o pozornost a rozmělnil primary message. Telefon ponechán
                     jako lehký text-link (ne button). --}}
                <a
                    href="#{{ __('home.anchors.poptavka') }}"
                    class="btn btn-primary"
                    data-analytics="hero_cta_primary_click"
                >
                    {{ __('home.hero.cta_primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                <x-phone-cta class="section-hero__phone-link" :label="__('home.hero.phone_label')" />
            </div>
        </div>

        {{-- T20 — Foto Ondřeje v hero (polo-portrét vpravo, desktop only).
             Decision A1 (OND-102): používáme existující ondrej_kriska.jpg.
             Mobile: skryto (preferujeme compact hero nad foldem). --}}
        <div class="section-hero__portrait" aria-hidden="true">
            <x-responsive-image
                path="about/ondrej_kriska.jpg"
                alt="{{ __('home.why_me.photo_alt') }}"
                sizes="(min-width: 1024px) 360px, 0px"
                loading="eager"
                fetchpriority="high"
                classPicture="section-hero__portrait-picture"
                classImg="section-hero__portrait-img"
            />
        </div>
    </div>
</section>

{{-- ===================================================
     SOCIAL PROOF BAR
     =================================================== --}}
<section class="section-wrapper section-alt section-social-proof" aria-label="Klienti">
    <div class="container-site">
        <ul class="social-proof-bar" aria-label="{{ __('home.social_proof.rating_aria') }}">
            <li class="social-proof-bar__item">
                <span class="social-proof-bar__stars" aria-hidden="true">★★★★★</span>
                <span class="social-proof-bar__value">{{ __('home.social_proof.rating_value') }}</span>
                <span class="social-proof-bar__meta">{{ __('home.social_proof.reviews') }}</span>
            </li>
            <li class="social-proof-bar__item">{{ __('home.social_proof.projects') }}</li>
            <li class="social-proof-bar__item">{{ __('home.social_proof.experience') }}</li>
            <li class="social-proof-bar__item">{{ __('home.social_proof.response') }}</li>
        </ul>

        <div class="brands-grid">
            @foreach (__('home.social_proof.brands') as $brand)
            <div class="brand-item">
                @if ($brand['image'])
                    {{-- OND-123: brand loga přes <x-responsive-image> → AVIF/WebP varianty.
                         Předtím se servíroval ~278×100 PNG jen pro 105×38 displej (PSI image audit).
                         Sizes hint je úzký (do 120 CSS px), takže browser vezme nejmenší AVIF variantu. --}}
                    <x-responsive-image
                        :path="'brands/' . $brand['image']"
                        :alt="$brand['name']"
                        sizes="120px"
                        loading="lazy"
                        decoding="async"
                        width="120"
                        height="48"
                    />
                @else
                    <span class="brand-item__name">{{ $brand['name'] }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     PROBLÉMY NA TRHU (T16 — 3 karty po Sprint 2 P1)
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.problems.heading') }}</h2>
        </header>

        <ol class="pain-list">
            @foreach (__('home.problems.items') as $i => $item)
            <li class="pain-item" data-reveal style="transition-delay: {{ $i * 90 }}ms">
                <span class="pain-item__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="pain-item__body">
                    <h3>{{ $item['heading'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                    @if (!empty($item['quote_text']))
                    <blockquote class="citation-inline">
                        <p class="citation-inline__text">{{ $item['quote_text'] }}</p>
                        <footer class="citation-inline__footer">
                            <span class="citation-inline__avatar" aria-hidden="true">{{ mb_substr($item['quote_author'], 0, 1) }}</span>
                            <cite class="citation-inline__author">
                                <strong>{{ $item['quote_author'] }}</strong>
                            </cite>
                        </footer>
                    </blockquote>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>

        <div class="problems-transition" data-reveal>
            <strong>{{ __('home.problems.transition_heading') }}</strong>
            <p>{{ __('home.problems.transition_text') }}</p>
        </div>
    </div>
</section>

{{-- ===================================================
     SERVICES — primary (weby, aplikace, e-shopy)
     OND-103 §4.4 — primární služby s kotvou „od 20 000 Kč"
     Pořadí dle plánu §3 (OND-118): po Pain, před cenovou kotvou.
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.services.heading_primary') }}</h2>
        </header>

        @php
        $primaryServiceIcons = [
            'weby'     => 'layers',
            'aplikace' => 'boxes',
            'eshop'    => 'store',
        ];
        @endphp

        <div class="services-grid services-grid--primary" data-reveal-group>
            @foreach (['weby','aplikace','eshop'] as $key)
            <article class="service-card service-card--primary">
                <x-dynamic-component :component="'icon.' . $primaryServiceIcons[$key]" class="w-8 h-8 service-card__icon" />
                <h3>{{ __("home.services.primary.{$key}.title") }}</h3>
                <p>{{ __("home.services.primary.{$key}.description") }}</p>
                <ul class="service-card__bullets">
                    @foreach (__("home.services.primary.{$key}.bullets") as $bullet)
                    <li>{{ $bullet }}</li>
                    @endforeach
                </ul>
                <p class="service-card__price">{{ __("home.services.primary.{$key}.price") }}</p>
            </article>
            @endforeach
        </div>

        {{-- T14 — Inline odkaz na doplňkové služby (SEO/design/social) místo samostatné sekce. --}}
        <p class="services-secondary-inline" data-reveal>
            {!! __('home.services.secondary_inline', [
                'pricing_link' => '<a href="' . lroute('price') . '">' . e(__('home.services.secondary_inline_pricing')) . '</a>',
                'contact_link' => '<a href="#' . __('home.anchors.poptavka') . '">' . e(__('home.services.secondary_inline_contact')) . '</a>',
            ]) !!}
        </p>
    </div>
</section>

{{-- ===================================================
     T08 — CENOVÁ KOTVA
     Plán §4.7 / §3 (OND-118). Mezi Primary services a Proč já.
     =================================================== --}}
<section id="section-price" class="section-wrapper" data-reveal data-analytics-view="price_anchor_view">
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.price_anchor.heading') }}</h2>
            <p class="section-header__desc">{{ __('home.price_anchor.intro') }}</p>
        </header>

        <div class="price-anchor-grid" data-reveal-group>
            @foreach (__('home.price_anchor.items') as $i => $item)
            <article class="price-anchor-card" data-reveal style="transition-delay: {{ $i * 80 }}ms">
                <h3 class="price-anchor-card__title">{{ $item['title'] }}</h3>
                <p class="price-anchor-card__price">{{ $item['price'] }}</p>
                <p class="price-anchor-card__desc">{{ $item['desc'] }}</p>
            </article>
            @endforeach
        </div>

        <div class="section-footer-cta">
            <a href="{{ lroute('price') }}" class="btn btn-secondary" data-analytics="price_anchor_cta_click">
                {{ __('home.price_anchor.cta') }}
            </a>
        </div>
    </div>
</section>

{{-- ===================================================
     T09 — PROČ JÁ (sjednoceno z About + Advantages)
     Plán §4.8. 2-sloupcový layout: foto + bio | 4 advantages 2×2.
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.why_me.heading') }}</h2>
        </header>

        <div class="why-me-layout">
            <div class="why-me-bio" data-reveal>
                <div class="why-me-photo">
                    {{-- OND-123 follow-up: tighter sizes (320 CSS px max na mobile)
                         — PSI servoval 768×1152 pro displej 358×537 (úspora 16 KiB). --}}
                    <x-responsive-image
                        path="about/ondrej_kriska.jpg"
                        alt="{{ __('home.why_me.photo_alt') }}"
                        sizes="(min-width: 768px) 360px, 320px"
                        loading="lazy"
                        decoding="async"
                        width="360"
                        height="540"
                        classImg="why-me-photo__img"
                    />
                </div>
                <p class="why-me-bio__text">{{ __('home.why_me.bio') }}</p>
            </div>

            <div class="why-me-advantages" data-reveal-group>
                @foreach (__('home.why_me.advantages') as $i => $adv)
                <article class="why-me-advantage" data-reveal style="transition-delay: {{ $i * 80 }}ms">
                    <h3 class="why-me-advantage__heading">{{ $adv['heading'] }}</h3>
                    <p class="why-me-advantage__text">{{ $adv['text'] }}</p>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     JAK PRACUJI
     =================================================== --}}
<section id="{{ __('home.anchors.how_i_work') }}" class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.how_i_work.heading') }}</h2>
        </header>

        <ol class="steps-list" data-reveal-group>
            @foreach (__('home.how_i_work.steps') as $i => $step)
            <li class="step-item">
                <span class="step-number">{{ $i + 1 }}</span>
                <div class="step-content">
                    <h3>{{ $step['heading'] }}</h3>
                    @if (!empty($step['time']))
                    <p class="step-time">{{ $step['time'] }}</p>
                    @endif
                    <p>{{ $step['text'] }}</p>
                    @if (!empty($step['quote_text']))
                    <blockquote class="inline-quote">
                        <p>{{ $step['quote_text'] }}</p>
                        <footer>— {{ $step['quote_author'] }}</footer>
                    </blockquote>
                    @endif
                    @if (!empty($step['note']))
                    <p class="step-note">{{ $step['note'] }}</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>

        {{-- T17 — CTA pod sekcí: vede rovnou ke kroku 1 (Reservanto). --}}
        <div class="section-footer-cta steps-footer-cta" data-reveal>
            <p class="steps-footer-cta__intro">{{ __('home.how_i_work.cta_intro') }}</p>
            <x-booking.reservanto-widget :ctaText="__('home.how_i_work.cta_label')" />
        </div>
    </div>
</section>

{{-- ===================================================
     REFERENCE KLIENTŮ (T10 — 6 karet vždy)
     Curated 6 nejsilnějších testimonialů (per OND-101 §2.2, OND-118 T10).
     Toyota (Pavel Baudyš) zůstává za feature flagem (publikační souhlas).
     Pokud Toyota off → fallback Peter Vidlička (Yolk studio, dlouhodobý B2B).
     =================================================== --}}
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

<section id="section-testimonials" class="section-wrapper section-alt section-wrapper--glow" data-reveal data-analytics-view="testimonial_view">
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.testimonials.heading') }}</h2>
        </header>

        <div class="testimonials-grid" data-reveal-group>
            @foreach ($homeTestimonials as $review)
            <article class="testimonial-card">
                <header class="testimonial-card__header">

                    @php
                        $publicLogos = ['makoplast.png'];
                        $isPublic = in_array($review['image'], $publicLogos);
                    @endphp

                    @if ($isPublic)
                        <img
                            src="{{ asset('img/testimonials/' . $review['image']) }}"
                            alt="{{ $review['company'] }}"
                            loading="lazy"
                            width="46"
                            height="46"
                            class="testimonial-avatar"
                        >
                    @else
                        <x-responsive-image
                            path="testimonials/{{ $review['image'] }}"
                            alt="{{ $review['name'] }}"
                            sizes="46px"
                            loading="lazy"
                            classImg="testimonial-avatar"
                        />
                    @endif

                    <div class="testimonial-card__info">
                        <h3>{{ $review['name'] }}</h3>
                        <p>
                            <strong>{{ $review['company'] }}</strong>
                            @if ($review['role'])
                            <br><span>{{ $review['role'] }}</span>
                            @endif
                        </p>
                    </div>
                </header>

                @if (!empty($review['badge']))
                <p class="testimonial-card__badge">{{ $review['badge'] }}</p>
                @endif

                <p class="testimonial-card__text">{{ $review['text'] }}</p>

                @php
                    $sourceIcons = [
                        'google'   => 'google.png',
                        'facebook' => 'fb.png',
                        'firmy_cz' => 'firmy_cz.svg',
                    ];
                    $icon = $sourceIcons[$review['source']] ?? null;
                @endphp
                @if ($icon)
                <footer class="testimonial-card__source">
                    <img
                        src="{{ asset('img/testimonials/' . $icon) }}"
                        alt="{{ $review['source'] }}"
                        loading="lazy"
                        width="20"
                        height="20"
                    >
                    <span class="testimonials-stars testimonials-stars--sm" aria-hidden="true">★★★★★</span>
                </footer>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     TOYOTA — ODKUD POCHÁZEJÍ MÉ PRINCIPY
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <div class="toyota-layout">
            <div class="toyota-content">
                <header class="section-header section-header--left">
                    <h2>{{ __('home.toyota.heading') }}</h2>
                </header>
                <p class="section-prose-text">{{ __('home.toyota.text') }}</p>
                <p class="section-prose-text">{{ __('home.toyota.text_2') }}</p>
            </div>
            <div class="toyota-quote" data-reveal>
                <blockquote class="featured-quote">
                    <p>{{ __('home.toyota.quote_text') }}</p>
                    <footer>— {{ __('home.toyota.quote_author') }}</footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     AI COMPARISON — Laik + AI vs. Odborník + AI
     =================================================== --}}
<section class="section-wrapper section-alt section-wrapper--glow" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.ai.subheading') }}</p>
            <h2>{{ __('home.ai.heading') }}</h2>
            <p class="section-header__desc">{{ __('home.ai.intro') }}</p>
        </header>

        <div class="ai-compare">
            {{-- Laik + AI --}}
            <div class="ai-compare__col ai-compare__col--muted" data-reveal style="transition-delay: 60ms">
                <div class="ai-compare__header">
                    <span class="ai-compare__label">{{ __('home.ai.laik.label') }}</span>
                    <p class="ai-compare__outcome">{{ __('home.ai.laik.outcome') }}</p>
                </div>
                <ul class="ai-compare__list">
                    @foreach (__('home.ai.laik.items') as $item)
                    <li>
                        <svg class="ai-compare__icon ai-compare__icon--no" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="ai-compare__note">{{ __('home.ai.laik.note') }}</p>
            </div>

            {{-- Odborník + AI --}}
            <div class="ai-compare__col ai-compare__col--featured" data-reveal style="transition-delay: 160ms">
                <div class="ai-compare__header">
                    <span class="ai-compare__label">{{ __('home.ai.expert.label') }}</span>
                    <p class="ai-compare__outcome">{{ __('home.ai.expert.outcome') }}</p>
                </div>
                <ul class="ai-compare__list">
                    @foreach (__('home.ai.expert.items') as $item)
                    <li>
                        <svg class="ai-compare__icon ai-compare__icon--yes" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="ai-compare__note">{{ __('home.ai.expert.note') }}</p>
            </div>
        </div>

        <p class="ai-compare__closing" data-reveal style="transition-delay: 240ms">{{ __('home.ai.closing') }}</p>
    </div>
</section>

{{-- ===================================================
     PORTFOLIO — REALIZOVANÉ PROJEKTY (OND-120)
     3 reference s písemným souhlasem klienta (PitArena, BARANA, Nové interiéry).
     Zapnout přes env SHOW_PORTFOLIO_SECTION=true (config/site.php).
     Data tečou z DB (PortfolioProject), 1-věty výsledku z lang/*/home.php
     (klíč `home.portfolio.cards.{slug}`). Loga v public/images/portfolio/logos/.
     =================================================== --}}
@if (config('site.features.show_portfolio_section') && ($featuredHomeProjects ?? collect())->isNotEmpty())
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.portfolio.heading') }}</h2>
        </header>

        <div class="home-projects-grid" data-reveal-group>
            @foreach ($featuredHomeProjects as $project)
                @php
                    $t = $project->translation();
                    $cardCopy = __('home.portfolio.cards.' . $project->slug);
                    $clientLabel = is_array($cardCopy) && !empty($cardCopy['client'])
                        ? $cardCopy['client']
                        : ($project->client_name ?: ($t?->title ?? $project->slug));
                    $outcome = is_array($cardCopy) && !empty($cardCopy['outcome'])
                        ? $cardCopy['outcome']
                        : ($t?->subtitle ?? '');

                    $screens = $project->screenshots ?? collect();
                    $hero = $screens->firstWhere('type', 'hero')
                        ?? $screens->firstWhere('type', 'thumbnail')
                        ?? $screens->first();

                    $detailHref = lroute('projects') . '/' . $project->slug;
                    // Karty mají tmavé pozadí (--bg-card), používáme bílé varianty log.
                    $logoSrc = asset('images/portfolio/logos/' . $project->slug . '-white.svg');
                @endphp

                <article class="home-projects-card" data-reveal>
                    <a
                        href="{{ $detailHref }}"
                        class="home-projects-card__visual"
                        aria-label="{{ $clientLabel }} — {{ __('projects.view_project') }}"
                        data-analytics="project_card_click"
                        data-analytics-props='{"slug":"{{ $project->slug }}"}'
                    >
                        @if ($hero)
                            <x-portfolio.screenshot
                                :path="$hero->path"
                                :alt="$clientLabel"
                                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                loading="lazy"
                            />
                        @else
                            <div class="home-projects-card__visual-placeholder" aria-hidden="true"></div>
                        @endif
                    </a>

                    <div class="home-projects-card__body">
                        <header class="home-projects-card__head">
                            <img
                                src="{{ $logoSrc }}"
                                alt="{{ $clientLabel }}"
                                class="home-projects-card__logo"
                                loading="lazy"
                                width="160"
                                height="40"
                            >
                            <span class="home-projects-card__client">{{ $clientLabel }}</span>
                        </header>

                        @if ($outcome)
                            <p class="home-projects-card__outcome">{{ $outcome }}</p>
                        @endif

                        <a
                            href="{{ $detailHref }}"
                            class="home-projects-card__cta"
                            data-analytics="project_card_click"
                            data-analytics-props='{"slug":"{{ $project->slug }}"}'
                        >
                            {{ __('home.portfolio.detail_cta') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="section-footer-cta">
            <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                {{ __('home.portfolio.cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===================================================
     GARANCE
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.guarantee.heading') }}</h2>
        </header>

        <div class="guarantee-grid" data-reveal-group>
            @foreach (__('home.guarantee.items') as $item)
            <div class="guarantee-card">
                <h3>{{ $item['heading'] }}</h3>
                <p>{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     FAQ — 4 otázky (OND-121 T19 + OND-119 JSON-LD/analytics)
     Plán §4.10 / §3 (pozice 9). 4 otázky v cs/en/de.
     =================================================== --}}
@php
    $faqItems = __('home.faq.items');
@endphp
<section id="faq" class="section-wrapper section-faq" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.faq.heading') }}</h2>
        </header>

        <div class="faq-list" data-reveal-group>
            @foreach ($faqItems as $i => $item)
            <details
                class="faq-item"
                data-reveal
                data-q-id="{{ $i }}"
                style="transition-delay: {{ $i * 60 }}ms"
            >
                <summary
                    class="faq-item__question"
                    data-analytics="faq_item_open"
                    data-faq-key="{{ $item['key'] ?? 'item-' . $i }}"
                >
                    <span>{{ $item['question'] }}</span>
                    <span class="faq-item__icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-item__answer">
                    <p>{{ $item['answer'] }}</p>
                </div>
            </details>
            @endforeach
        </div>

        {{-- OND-119 — JSON-LD FAQPage pro rich snippets v Google SERP.
             Generováno ze stejných lang klíčů jako accordion (single source of truth).
             Validace: https://search.google.com/test/rich-results --}}
        <script type="application/ld+json">
        @php
            $faqLd = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_map(static function (array $item): array {
                    return [
                        '@type'          => 'Question',
                        'name'           => $item['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => $item['answer'],
                        ],
                    ];
                }, $faqItems),
            ];
        @endphp
        {!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>

        {{-- T18 — Mikro-formulář po FAQ: zachytí lead, který nenašel odpověď v FAQ.
             Posílá na stejný endpoint /poptavka (home.lead.store), source = home.faq. --}}
        <div class="faq-form" data-reveal>
            <div class="faq-form__intro">
                <p class="section-subheading">{{ __('home.faq_form.eyebrow') }}</p>
                <h3 class="faq-form__heading">{{ __('home.faq_form.heading') }}</h3>
                <p class="faq-form__desc">{{ __('home.faq_form.description') }}</p>
            </div>

            @if (session('faq_lead_success'))
                <div class="landing-alert landing-alert--success" role="status">
                    {{ __('home.faq_form.success') }}
                </div>
            @endif

            @if ($errors->any() && session('home_lead_target') === 'faq')
                <div class="landing-alert landing-alert--error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('home.lead.store') }}"
                novalidate
                x-data="{ submitting: false }"
                @submit="submitting = true"
                class="faq-form__form"
            >
                @csrf
                <input type="hidden" name="source" value="home.faq">

                <div class="faq-form__grid">
                    <div class="form-group">
                        <label for="faq-lead-name">{{ __('home.faq_form.name') }} <span aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            id="faq-lead-name"
                            name="name"
                            value="{{ session('home_lead_target') === 'faq' ? old('name') : '' }}"
                            required
                            placeholder="{{ __('home.faq_form.placeholders.name') }}"
                            autocomplete="name"
                        >
                    </div>

                    <div class="form-group">
                        <label for="faq-lead-email">{{ __('home.faq_form.email') }} <span aria-hidden="true">*</span></label>
                        <input
                            type="email"
                            id="faq-lead-email"
                            name="email"
                            value="{{ session('home_lead_target') === 'faq' ? old('email') : '' }}"
                            required
                            placeholder="{{ __('home.faq_form.placeholders.email') }}"
                            autocomplete="email"
                        >
                    </div>

                    <div class="form-group form-group--full">
                        <label for="faq-lead-message">{{ __('home.faq_form.message') }} <span aria-hidden="true">*</span></label>
                        <textarea
                            id="faq-lead-message"
                            name="message"
                            rows="3"
                            required
                            placeholder="{{ __('home.faq_form.placeholders.message') }}"
                        >{{ session('home_lead_target') === 'faq' ? old('message') : '' }}</textarea>
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary faq-form__submit"
                    :disabled="submitting"
                    data-analytics="faq_form_submit_attempt"
                >
                    <span class="btn__inner" x-show="!submitting">
                        {{ __('home.faq_form.submit') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </span>
                    <span class="btn__inner" x-show="submitting" x-cloak>{{ __('home.faq_form.submitting') }}</span>
                </button>
            </form>
        </div>
    </div>
</section>

{{-- ===================================================
     INLINE POPTÁVKA (OND-100, T05)
     =================================================== --}}
@include('partials.home-inline-form')

{{-- ===================================================
     ZÁVĚREČNÉ CTA
     =================================================== --}}
<section class="section-wrapper section-cta" data-reveal>
    <div class="container-site">
        <div class="final-cta__intro">
            <h2 class="final-cta-heading">
                {!! __('home.cta.heading') ?? __('layout.prefooter.tagline') !!}
            </h2>
            <div class="final-cta__actions">
                <a href="#{{ __('home.anchors.poptavka') }}" class="btn btn-primary" data-analytics="final_cta_primary_click">
                    {{ __('home.cta.consultation') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                {{-- Sekundární CTA — Reservanto widget (OND-116/T15) --}}
                <x-booking.reservanto-widget />
            </div>
            <blockquote class="final-cta-quote">
                <p>{{ __('home.final_cta.quote_text') }}</p>
                <footer>— {{ __('home.final_cta.quote_author') }}</footer>
            </blockquote>
        </div>

        <div class="final-cta__closing">
            <h2 class="final-cta-heading">{{ __('home.final_cta.heading') }}</h2>
            <p class="final-cta-subtext">{{ __('home.final_cta.subtext') }}</p>

            <div class="final-cta__actions">
                <a
                    href="#{{ __('home.anchors.poptavka') }}"
                    class="btn btn-primary"
                    data-analytics="final_cta_closing_primary_click"
                >
                    {{ __('home.final_cta.cta_label') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                {{-- Sekundární CTA — Reservanto widget (OND-116/T15) --}}
                <x-booking.reservanto-widget />
            </div>
            <p class="final-cta-note">{{ __('home.final_cta.cta_note') }}</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @php
            $leadTarget = session('home_lead_target');
            $scrollAnchor = $leadTarget === 'faq' ? 'faq' : __('home.anchors.poptavka');
        @endphp
        @if ($errors->any() || session('home_lead_success') || session('faq_lead_success'))
        document.getElementById('{{ $scrollAnchor }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        @endif

        @if (session('home_lead_success'))
        window.dispatchEvent(new CustomEvent('inline-form-submit-success'));
        @endif

        @if (session('faq_lead_success'))
        window.dispatchEvent(new CustomEvent('faq-form-submit-success'));
        @endif
    });
</script>
@endpush
