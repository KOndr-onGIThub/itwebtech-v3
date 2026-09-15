@extends('layouts.landing')

@section('title', __('landing.meta.title'))
@section('description', __('landing.meta.description'))

@section('content')
@php
    $leadAction = request()->routeIs('landing.preview')
        ? route('landing.lead.preview')
        : route('landing.lead.store');
@endphp

<a href="#poptavka" class="landing-sticky-cta" id="landing-sticky-cta">
    {{ __('landing.hero.primary_cta') }}
</a>

<div class="landing-page">
    <section class="landing-hero" id="landing-hero">
        <div class="container-site landing-hero__inner">
            <div class="landing-hero__content" data-reveal>
                <p class="section-subheading">{{ __('landing.hero.eyebrow') }}</p>
                <h1>{{ __('landing.hero.title') }}</h1>
                <p class="landing-hero__lead">{{ __('landing.hero.description') }}</p>

                <div class="landing-badge-list" aria-label="{{ __('landing.hero.eyebrow') }}">
                    @foreach (__('landing.hero.chips') as $chip)
                        <span class="landing-badge">{{ $chip }}</span>
                    @endforeach
                </div>

                <div class="landing-hero__actions">
                    <a href="#poptavka" class="btn btn-primary landing-hero__primary">
                        {{ __('landing.hero.primary_cta') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </a>
                </div>

                <a href="{{ lroute('price') }}" class="landing-text-link">
                    {{ __('landing.hero.secondary_cta') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>

                <p class="landing-microcopy">{{ __('landing.hero.microcopy') }}</p>
            </div>

            <aside class="landing-hero__aside" data-reveal>
                <x-responsive-image
                    path="hero/hero-uvod.webp"
                    alt="Ondřej Kriška"
                    sizes="(min-width: 1024px) 420px, 100vw"
                    loading="eager"
                    fetchpriority="high"
                    classPicture="landing-hero__portrait-picture"
                    classImg="landing-hero__portrait"
                />

                <div class="landing-panel">
                    <h2>{{ __('landing.hero.trust.title') }}</h2>
                    <ul class="landing-checklist">
                        @foreach (__('landing.hero.trust.items') as $item)
                            <li>
                                <x-icon.circle-check-big class="w-5 h-5 shrink-0" />
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    <section class="landing-section landing-section--alt" data-reveal>
        <div class="container-site">
            <header class="section-header section-header--left">
                <h2>{{ __('landing.problem.title') }}</h2>
                <p class="section-header__desc">{{ __('landing.problem.intro') }}</p>
            </header>

            <div class="landing-card-grid" data-reveal-group>
                @foreach (__('landing.problem.items') as $item)
                    <article class="landing-card landing-card--pain">
                        <p>{{ $item }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="landing-section" data-reveal>
        <div class="container-site">
            <header class="section-header">
                <p class="section-subheading">{{ __('landing.benefits.eyebrow') }}</p>
                <h2>{{ __('landing.benefits.title') }}</h2>
                <p class="section-header__desc">{{ __('landing.benefits.intro') }}</p>
            </header>

            <div class="landing-card-grid landing-card-grid--offer" data-reveal-group>
                @foreach (__('landing.benefits.items') as $item)
                    <article class="landing-card">
                        <x-dynamic-component :component="'icon.' . $item['icon']" class="landing-card__icon" />
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="landing-section landing-section--alt" data-reveal>
        <div class="container-site landing-proof">
            <div class="landing-proof__content">
                <header class="section-header section-header--left">
                    <p class="section-subheading">{{ __('landing.why.eyebrow') }}</p>
                    <h2>{{ __('landing.why.title') }}</h2>
                    <p class="section-header__desc">{{ __('landing.why.text') }}</p>
                </header>

                <ul class="landing-checklist">
                    @foreach (__('landing.why.items') as $item)
                        <li>
                            <x-icon.circle-check-big class="w-5 h-5 shrink-0" />
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="landing-panel landing-panel--accent">
                <h3>{{ __('landing.hero.primary_cta') }}</h3>
                <p>{{ __('landing.hero.microcopy') }}</p>

                <div class="landing-panel__actions">
                    <a href="#poptavka" class="btn btn-primary">{{ __('landing.hero.primary_cta') }}</a>
                    <a href="{{ lroute('price') }}" class="landing-inline-link">{{ __('landing.hero.secondary_cta') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section id="process" class="landing-section" data-reveal>
        <div class="container-site">
            <header class="section-header section-header--left">
                <p class="section-subheading">{{ __('landing.process.eyebrow') }}</p>
                <h2>{{ __('landing.process.title') }}</h2>
                <p class="section-header__desc">{{ __('landing.process.microcopy') }}</p>
            </header>

            <ol class="landing-process" data-reveal-group>
                @foreach (__('landing.process.items') as $index => $item)
                    <li class="landing-process__item">
                        <span class="landing-process__number">{{ $index + 1 }}</span>
                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="landing-section landing-section--alt" data-reveal>
        <div class="container-site">
            <header class="section-header">
                <p class="section-subheading">{{ __('landing.results.eyebrow') }}</p>
                <h2>{{ __('landing.results.title') }}</h2>
                <p class="section-header__desc">{{ __('landing.results.intro') }}</p>
            </header>

            <div class="landing-logo-strip" data-reveal-group>
                @foreach (__('landing.results.logos') as $logo)
                    <div class="landing-logo-item">
                        <img src="{{ asset('img/' . $logo['src']) }}" alt="{{ $logo['alt'] }}" loading="lazy">
                    </div>
                @endforeach
            </div>

            <div class="landing-results-grid" data-reveal-group>
                @foreach (__('landing.results.snapshots') as $snapshot)
                    <article class="landing-card">
                        <span class="landing-card__eyebrow">{{ $snapshot['type'] }}</span>
                        <h3>{{ $snapshot['title'] }}</h3>
                        <p>{{ $snapshot['summary'] }}</p>
                    </article>
                @endforeach
            </div>

            <header class="section-header section-header--left landing-section__subheader">
                <h3>{{ __('landing.results.references_heading') }}</h3>
            </header>

            <div class="landing-reference-grid" data-reveal-group>
                @foreach (__('landing.results.references') as $reference)
                    <article class="landing-reference-card">
                        <x-responsive-image
                            :path="'testimonials/' . $reference['image']"
                            :alt="$reference['name']"
                            sizes="96px"
                            loading="lazy"
                            classPicture="landing-reference-card__picture"
                            classImg="landing-reference-card__image"
                        />

                        <div class="landing-reference-card__meta">
                            <strong>{{ $reference['name'] }}</strong>
                            <span>{{ $reference['company'] }} · {{ $reference['role'] }}</span>
                        </div>

                        <p>{{ $reference['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="landing-section landing-section--faq" data-reveal>
        <div class="container-site">
            <header class="section-header">
                <p class="section-subheading">{{ __('landing.faq.eyebrow') }}</p>
                <h2>{{ __('landing.faq.title') }}</h2>
            </header>

            <div class="faq-list">
                @foreach (__('landing.faq.items') as $item)
                    <details class="faq-item">
                        <summary>{{ $item['question'] }}</summary>
                        <p class="faq-answer">{{ $item['answer'] }}</p>
                    </details>
                @endforeach
            </div>

            <div class="landing-inline-cta">
                <a href="#poptavka" class="landing-text-link">
                    {{ __('landing.faq.cta') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
            </div>
        </div>
    </section>

    <section id="poptavka" class="landing-section landing-section--form" data-reveal>
        <div class="container-site landing-form-shell">
            <div class="landing-form-intro">
                <p class="section-subheading">{{ __('landing.form.eyebrow') }}</p>
                <h2>{{ __('landing.form.title') }}</h2>
                <p class="section-header__desc">{{ __('landing.form.description') }}</p>

                <div class="landing-panel landing-panel--accent landing-form-trust">
                    <h3>{{ __('landing.form.trust_title') }}</h3>
                    <ul class="landing-checklist">
                        @foreach (__('landing.form.trust_items') as $item)
                            <li>
                                <x-icon.circle-check-big class="w-5 h-5 shrink-0" />
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div id="lead-form" class="landing-form-panel">
                @if (session('landing_lead_success'))
                    <div class="landing-alert landing-alert--success" role="status">
                        {{ __('landing.form.success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="landing-alert landing-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ $leadAction }}" novalidate x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf

                    <div class="landing-form-grid">
                        <div class="form-group">
                            <label for="name">{{ __('landing.form.name') }} <span aria-hidden="true">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="{{ __('landing.form.placeholders.name') }}">
                            @error('name') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="company">{{ __('landing.form.company') }}</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}" placeholder="{{ __('landing.form.placeholders.company') }}">
                            @error('company') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('landing.form.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('landing.form.placeholders.email') }}">
                            @error('email') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ __('landing.form.phone') }}</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('landing.form.placeholders.phone') }}">
                            @error('phone') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group form-group--full">
                            <label for="budget">{{ __('landing.form.budget') }}</label>
                            <select id="budget" name="budget">
                                <option value="">-</option>
                                @foreach (__('landing.form.budget_options') as $option)
                                    <option value="{{ $option }}" @selected(old('budget') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('budget') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group form-group--full">
                            <label for="message">{{ __('landing.form.message') }} <span aria-hidden="true">*</span></label>
                            <textarea id="message" name="message" rows="5" required placeholder="{{ __('landing.form.placeholders.message') }}">{{ old('message') }}</textarea>
                            @error('message') <p class="landing-field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group form-group--checkbox">
                        <label>
                            <input type="checkbox" name="gdpr" value="1" @checked(old('gdpr')) required>
                            {{ __('landing.form.privacy_prefix') }}
                            <a href="{{ lroute('privacy') }}">{{ __('landing.form.privacy_link') }}</a>
                        </label>
                        @error('gdpr') <p class="landing-field-error">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary landing-form__submit" :disabled="submitting">
                        <span x-show="!submitting" style="display:flex;align-items:center;gap:.5rem;">
                            {{ __('landing.form.submit') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </span>
                        <span x-show="submitting" x-cloak>{{ __('landing.form.submitting') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hero = document.getElementById('landing-hero');
            const stickyCta = document.getElementById('landing-sticky-cta');

            if (hero && stickyCta && window.matchMedia('(max-width: 767px)').matches) {
                const observer = new IntersectionObserver(([entry]) => {
                    stickyCta.classList.toggle('is-visible', !entry.isIntersecting);
                }, { threshold: 0.15 });

                observer.observe(hero);
            }

            @if ($errors->any() || session('landing_lead_success'))
                document.getElementById('poptavka')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            @endif
        });
    </script>
@endpush
