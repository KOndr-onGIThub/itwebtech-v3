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
        <h1 class="section-hero__heading">
            {{ __('home.hero.heading') }}
        </h1>
        <div class="hero-chips" aria-hidden="true">
            @foreach (__('home.hero.chips') as $i => $chip)
                <span class="hero-chip" style="--i:{{ $i }}">{{ $chip }}</span>
            @endforeach
        </div>
        <p class="section-hero__subline">{{ __('home.hero.subline') }}</p>
        <div class="section-hero__actions">
            <button
                class="btn btn-primary"
                @click="$dispatch('open-consultation-modal')"
                type="button"
            >
                {{ __('home.hero.cta_primary') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </button>
            <a href="#{{ __('home.anchors.how_i_work') }}" class="btn btn-secondary">
                {{ __('home.hero.cta_secondary') }}
            </a>
        </div>
    </div>
</section>

{{-- ===================================================
     SOCIAL PROOF BAR
     =================================================== --}}
<section class="section-wrapper section-alt section-social-proof" aria-label="Klienti">
    <div class="container-site">
        <div class="brands-grid">
            @foreach (__('home.social_proof.brands') as $brand)
            <div class="brand-item">
                @if ($brand['image'])
                    <img
                        src="{{ asset('img/brands/' . $brand['image']) }}"
                        alt="{{ $brand['name'] }}"
                        loading="lazy"
                        width="120"
                        height="48"
                    >
                @else
                    <span class="brand-item__name">{{ $brand['name'] }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     PROBLÉMY NA TRHU
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.problems.heading') }}</h2>
        </header>

        <div class="pain-grid" data-reveal-group>
            @foreach (__('home.problems.items') as $item)
            <div class="pain-card">
                <h3>{{ $item['heading'] }}</h3>
                <p>{{ $item['text'] }}</p>
                @if (!empty($item['quote_text']))
                <blockquote class="inline-quote">
                    <p>{{ $item['quote_text'] }}</p>
                    <footer>— {{ $item['quote_author'] }}</footer>
                </blockquote>
                @endif
            </div>
            @endforeach
        </div>

        <div class="problems-transition" data-reveal>
            <strong>{{ __('home.problems.transition_heading') }}</strong>
            <p>{{ __('home.problems.transition_text') }}</p>
        </div>
    </div>
</section>

{{-- ===================================================
     JAK PRACUJI
     =================================================== --}}
<section id="{{ __('home.anchors.how_i_work') }}" class="section-wrapper section-alt" data-reveal>
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
    </div>
</section>

{{-- ===================================================
     AI — ROVNOU K VĚCI
     =================================================== --}}
<section class="section-wrapper section-wrapper--glow" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.ai.heading') }}</h2>
        </header>

        <div class="ai-intro" data-reveal>
            <p class="section-prose-text">{{ __('home.ai.intro') }}</p>
            <p class="section-prose-text">{{ __('home.ai.problem') }}</p>
            <p class="section-prose-text">{{ __('home.ai.conclusion') }}</p>
        </div>

        <div class="ai-comparison" data-reveal>
            <div class="ai-comparison__col ai-comparison__col--laik">
                <h3>{{ __('home.ai.laik_heading') }}</h3>
                <p>{{ __('home.ai.laik_text') }}</p>
            </div>
            <div class="ai-comparison__divider" aria-hidden="true"></div>
            <div class="ai-comparison__col ai-comparison__col--expert">
                <h3>{{ __('home.ai.expert_heading') }}</h3>
                <p>{{ __('home.ai.expert_text') }}</p>
            </div>
        </div>

        <p class="ai-note">{{ __('home.ai.note') }}</p>

        <p class="section-prose-text ai-outro" data-reveal>{{ __('home.ai.outro') }}</p>
    </div>
</section>

{{-- ===================================================
     TOYOTA — ODKUD POCHÁZEJÍ MÉ PRINCIPY
     =================================================== --}}
<section class="section-wrapper section-alt" data-reveal>
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
     PORTFOLIO — PLACEHOLDER
     =================================================== --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.portfolio.heading') }}</h2>
        </header>

        <div class="portfolio-grid" data-reveal-group>
            @foreach (range(1, 5) as $i)
            <article class="portfolio-card portfolio-card--placeholder">
                <div class="portfolio-card__visual" aria-hidden="true">
                    <span class="portfolio-card__placeholder-text">{{ __('home.portfolio.placeholder') }}</span>
                </div>
                <div class="portfolio-card__body">
                    <p class="portfolio-card__placeholder-line"></p>
                    <p class="portfolio-card__placeholder-line portfolio-card__placeholder-line--short"></p>
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

{{-- ===================================================
     REFERENCE KLIENTŮ
     =================================================== --}}
<section class="section-wrapper section-alt section-wrapper--glow" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('home.testimonials.heading') }}</h2>
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
     ZÁVĚREČNÉ CTA
     =================================================== --}}
<section class="section-wrapper section-cta" data-reveal>
    <div class="container-site">
        <h2 style="font-size:clamp(1.75rem,3.5vw,2.5rem);letter-spacing:-0.025em;margin-bottom:1.5rem;width:100%;text-align:center;">
            {!! __('home.cta.heading') ?? __('layout.prefooter.tagline') !!}
        </h2>
        <button type="button" class="btn btn-primary" onclick="window.dispatchEvent(new CustomEvent('open-consultation-modal'))">
            {{ __('home.cta.consultation') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
        </button>
        <a href="{{ lroute('contact') }}" class="btn btn-secondary">
            {{ __('home.cta.message') }}
        </a>
        <blockquote class="final-cta-quote">
            <p>{{ __('home.final_cta.quote_text') }}</p>
            <footer>— {{ __('home.final_cta.quote_author') }}</footer>
        </blockquote>

        <h2 class="final-cta-heading">{{ __('home.final_cta.heading') }}</h2>
        <p class="final-cta-subtext">{{ __('home.final_cta.subtext') }}</p>

        <button
            class="btn btn-primary"
            @click="$dispatch('open-consultation-modal')"
            type="button"
        >
            {{ __('home.final_cta.cta_label') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
        </button>
        <p class="final-cta-note">{{ __('home.final_cta.cta_note') }}</p>
    </div>
</section>

@endsection
