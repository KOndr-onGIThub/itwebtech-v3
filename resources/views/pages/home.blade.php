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

    <div class="container-site">
        <p class="section-subheading">{{ __('home.hero.subheading') }}</p>
        <h1 class="section-hero__heading">
            {!! __('home.hero.heading') !!}
        </h1>
        <div class="hero-chips" aria-hidden="true">
            @foreach (__('home.hero.chips') as $i => $chip)
                <span class="hero-chip" style="--i:{{ $i }}">{{ $chip }}</span>
            @endforeach
        </div>
        <blockquote class="hero-quote">
            <div class="hero-quote__text">
                @foreach (array_slice(__('home.hero.bio'), 0, -1) as $line)
                    <span>{!! $line !!}</span>
                @endforeach
            </div>
            <footer class="hero-quote__author">
                <span class="hero-quote__dash"></span>
                <cite>{!! last(__('home.hero.bio')) !!}</cite>
            </footer>
        </blockquote>
        <div class="section-hero__actions">
            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('home.hero.cta_contact') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
            <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                {{ __('home.hero.cta_consultation') }}
            </a>
        </div>
    </div>
</section>

{{-- ===================================================
     BRAND LOGOS (klienti)
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal aria-label="Klienti">
    <div class="container-site">
        <div class="brands-grid">
            @foreach ([
                ['src' => 'brands/upstyle.png',           'alt' => 'Upstyle systems'],
                ['src' => 'brands/toyota.png',             'alt' => 'Toyota'],
                ['src' => 'brands/yolk_studio.png',        'alt' => 'Yolk studio'],
            ] as $brand)
            <div class="brand-item">
                <img
                    src="{{ asset('img/' . $brand['src']) }}"
                    alt="{{ $brand['alt'] }}"
                    loading="lazy"
                    width="120"
                    height="48"
                >
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     PAIN — rozpoznání problémů zákazníka
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.pain.subheading') }}</p>
            <h2>{{ __('home.pain.heading') }}</h2>
        </header>

        <div class="pain-grid" data-reveal-group>
            @foreach (__('home.pain.items') as $item)
            <div class="pain-card">
                <h3>{{ $item['heading'] }}</h3>
                <p>{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     SERVICES — primary (websites, webapps, eshop)
     =================================================== --}}
<section class="section-wrapper section-wrapper--glow" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.services.subheading') }}</p>
            <h2>{{ __('home.services.heading') }}</h2>
            <p class="section-header__desc">{{ __('home.services.description') }}</p>
        </header>

        @php
        $serviceIcons = [
            'websites' => 'layers',
            'webapps'  => 'boxes',
            'eshop'    => 'store',
        ];
        @endphp

        <div class="services-grid" data-reveal-group>
            @foreach (['websites','webapps','eshop'] as $key)
            <article class="service-card">
                <x-dynamic-component :component="'icon.' . $serviceIcons[$key]" class="w-8 h-8 service-card__icon" />
                <h3>{{ __("home.services.{$key}.title") }}</h3>
                <p>{{ __("home.services.{$key}.description") }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     COMMITMENT
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.commitment.heading') }}</h2>
        </header>
        <p class="section-prose-text">{!! nl2br(e(__('home.commitment.text'))) !!}</p>

        <ol class="commitment-steps" data-reveal-group>
            @foreach (__('home.commitment.steps') as $step)
            <li>
                <strong>{{ $step['title'] }}</strong>
                <p>{{ $step['description'] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ===================================================
     ABOUT — presentation + checklist
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.about.subheading') }}</p>
            <h2>{{ __('home.about.heading') }}</h2>
            <p class="section-header__desc">{{ __('home.about.description') }}</p>
        </header>

        <div class="about-layout">
            <div class="about-content">
                <h3>{{ __('home.about.content_title') }}</h3>
                <ul>
                    @foreach (__('home.about.content') as $item)
                    <li>
                        <x-icon.circle-check-big class="w-5 h-5 shrink-0" />
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <aside class="about-stats">
                <div class="about-video" data-video-player>
                    <video
                        class="about-video__player"
                        src="{{ asset('videos/001_titulky_fs.mp4') }}"
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        data-video
                    ></video>

                    <div class="vp-overlay" data-vp-overlay>
                        <div class="vp-progress" data-vp-progress role="slider" aria-label="Pozice videa" tabindex="0">
                            <div class="vp-progress__fill" data-vp-fill></div>
                            <div class="vp-progress__thumb"></div>
                        </div>
                        <div class="vp-bar">
                            <button class="vp-btn" data-vp-play aria-label="Přehrát">
                                <svg class="vp-icon vp-icon--play" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 3l14 9-14 9V3z"/></svg>
                                <svg class="vp-icon vp-icon--pause" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                            </button>
                            <span class="vp-time" data-vp-time>0:00 / 0:00</span>
                            <div class="vp-bar__right">
                                <button class="vp-btn" data-vp-mute aria-label="Ztlumit">
                                    <svg class="vp-icon vp-icon--vol-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>
                                    <svg class="vp-icon vp-icon--vol-on"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                                </button>
                                <input class="vp-volume" data-vp-vol type="range" min="0" max="100" step="1" value="100" aria-label="Hlasitost">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-block">
                    <span class="stat-number" data-counter>18</span>
                    <span class="stat-label">{{ __('home.about.years_label') }}</span>
                </div>
                <blockquote>
                    <strong>{{ __('home.about.guarantee_h') }}</strong>
                    <p>{{ __('home.about.guarantee_text') }}</p>
                </blockquote>
            </aside>
        </div>
    </div>
</section>

{{-- ===================================================
     ADVANTAGES
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.advantages.subheading') }}</p>
            <h2>{{ __('home.advantages.heading') }}</h2>
        </header>

        <div class="advantages-grid" data-reveal-group>
            @foreach (__('home.advantages.items') as $adv)
            <div class="advantage-card">
                <h3>{{ $adv['heading'] }}</h3>
                <p>{!! $adv['text'] !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     STEPS — how we work
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.steps.subheading') }}</p>
            <h2>{{ __('home.steps.heading') }}</h2>
        </header>

        <ol class="steps-list" data-reveal-group>
            @foreach (__('home.steps.items') as $i => $step)
            <li class="step-item">
                <span class="step-number">{{ $i + 1 }}</span>
                <div>
                    <h3>{{ $step['heading'] }}</h3>
                    <p>{!! $step['text'] !!}</p>
                </div>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ===================================================
     SERVICES — secondary (seo, design, social)
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.services.heading_other') }}</h2>
        </header>

        @php
        $serviceIcons2 = [
            'seo'    => 'search',
            'design' => 'palette',
            'social' => 'thumb-up',
        ];
        @endphp

        <div class="services-grid" data-reveal-group>
            @foreach (['seo','design','social'] as $key)
            <article class="service-card">
                <x-dynamic-component :component="'icon.' . $serviceIcons2[$key]" class="w-8 h-8 service-card__icon" />
                <h3>{{ __("home.services.{$key}.title") }}</h3>
                <p>{{ __("home.services.{$key}.description") }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     PROJECTS PREVIEW
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.projects.subheading') }}</p>
            <h2>{{ __('home.projects.heading') }}</h2>
        </header>

        {{-- TODO: render latest projects from DB (Fáze 3) --}}
        <div class="projects-preview-placeholder">
            <x-icon.layers class="w-10 h-10 mx-auto mb-3 opacity-30" />
            <p>{{ __('projects.empty') }}</p>
        </div>

        <div class="section-footer-cta">
            <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                {{ __('home.projects.cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
            </a>
        </div>
    </div>
</section>

{{-- ===================================================
     TESTIMONIALS
     =================================================== --}}
<section class="section-wrapper section-wrapper--glow" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.testimonials.subheading') }}</p>
            <h2>{{ __('home.testimonials.heading') }}</h2>
            <div class="testimonials-rating">
                <span class="testimonials-stars" aria-hidden="true">★★★★★</span>
                <strong>{{ __('testimonials.meta.rating') }}</strong>
                <span class="text-muted" style="font-size:.9375rem;">({{ __('testimonials.meta.total') }} {{ __('home.testimonials.reviews_label') }})</span>
            </div>
        </header>

        <div class="testimonials-grid" data-reveal-group>
            @foreach (__('testimonials.items') as $review)
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
     PRICE TEASER
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <div class="price-teaser">
            <div class="price-teaser__content">
                <p class="section-subheading">{{ __('home.price.subheading') ?? 'Transparentní ceník' }}</p>
                <h2>{{ __('home.price.heading') }}</h2>
                <p class="section-header__desc" style="text-align:left;margin-inline:0;">{{ __('home.price.description') ?? '' }}</p>
            </div>
            <div class="price-teaser__action">
                <a href="{{ lroute('price') }}" class="btn btn-primary">
                    {{ __('home.price.cta') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     FAQ — časté otázky
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('home.faq.subheading') }}</p>
            <h2>{{ __('home.faq.heading') }}</h2>
        </header>

        <div class="faq-list" data-reveal-group>
            @foreach (__('home.faq.items') as $item)
            <details class="faq-item">
                <summary>{{ $item['q'] }}</summary>
                <p class="faq-answer">{{ $item['a'] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     CTA — final
     =================================================== --}}
<section class="section-wrapper section-cta" data-reveal>
    <div class="container-site">
        <h2 style="font-size:clamp(1.75rem,3.5vw,2.5rem);letter-spacing:-0.025em;margin-bottom:1.5rem;width:100%;text-align:center;">
            {!! __('home.cta.heading') ?? __('layout.prefooter.tagline') !!}
        </h2>
        <a href="{{ lroute('contact') }}" class="btn btn-primary">
            {{ __('home.cta.quotation') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
        </a>
        <a href="{{ lroute('contact') }}" class="btn btn-secondary">
            {{ __('home.cta.message') }}
        </a>
    </div>
</section>

@endsection
