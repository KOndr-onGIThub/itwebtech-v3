@extends('layouts.app')

@section('title', __('contact.meta.title'))
@section('description', __('contact.meta.description'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro /kontakt. --}}
@push('jsonld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),    'item' => lroute('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.contact'), 'item' => lroute('contact')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- Page hero — OND-135 iter 4: plán §3.1 design DNA (page-mark + display italic + amber accent) --}}
<div class="page-hero page-hero--contact">
    <div class="container-site">
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt (itwebtech nemá „pages" hierarchii) —
             aplikováno per CEO PR #78 precedent na home. Label zachován. --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('contact.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('contact.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('contact.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('contact.hero.subline') }}</p>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">

        <div class="contact-layout">

            {{-- Contact info --}}
            <aside class="contact-info">

                <div class="contact-info__photo-wrap">
                    <picture>
                        <source srcset="{{ asset('img/about/ondrej_kriska_preview.webp') }}" type="image/webp">
                        <img
                            src="{{ asset('img/about/ondrej_kriska.jpg') }}"
                            alt="{{ __('contact.hero.photo_alt') }}"
                            class="contact-info__photo"
                            loading="lazy"
                            width="260"
                            height="300"
                        >
                    </picture>
                    <p class="contact-info__role">{{ __('contact.hero.role_label') }}</p>
                </div>

                <dl>
                    <div>
                        <dt>{{ __('contact.address_label') }}</dt>
                        <dd>
                            Ondřej Kriška<br>
                            Česká republika
                        </dd>
                    </div>

                    <div>
                        <dt>E-mail</dt>
                        <dd>
                            <a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
                        </dd>
                    </div>

                    <div>
                        <dt>{{ __('contact.hours_label') }}</dt>
                        <dd>{!! __('contact.open_hours') !!}</dd>
                    </div>
                </dl>

                <a href="#kontaktni-formular" class="btn btn-primary contact-info__cta">
                    {{ __('contact.cta_consultation') }}
                </a>
            </aside>

            {{-- Contact form --}}
            <div id="kontaktni-formular" class="contact-form" x-data="contactForm">

                {{-- Thank-you state — replaces the form on success (OND-136). --}}
                <div class="contact-form__thanks" x-show="submitted" x-cloak>
                    <h2 class="contact-form__title">{{ __('contact.thank_you.heading') }}</h2>
                    <p class="contact-form__subtitle">{{ __('contact.thank_you.subline') }}</p>
                    <p class="contact-form__thanks-next">{{ __('contact.thank_you.next') }}</p>
                    <div class="contact-form__thanks-ctas">
                        <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                            {{ __('contact.thank_you.cta_projects') }}
                        </a>
                        <a href="{{ lroute('price') }}" class="btn btn-secondary">
                            {{ __('contact.thank_you.cta_price') }}
                        </a>
                    </div>
                </div>

                <h2 class="contact-form__title" x-show="!submitted">{{ __('contact.form_heading') }}</h2>
                <p class="contact-form__subtitle" x-show="!submitted">{{ __('contact.form_subheading') }}</p>

                <form @submit.prevent="submit" novalidate x-show="!submitted">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('contact.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="{{ __('contact.name') }}">
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group form-group--inline">
                            <label for="email">{{ __('contact.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="vas@email.cz">
                        </div>
                        <div class="form-group form-group--inline">
                            <label for="tel">{{ __('contact.tel') }} <span aria-hidden="true">*</span></label>
                            <input type="tel" id="tel" name="tel" required autocomplete="tel"
                                   placeholder="+420 000 000 000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">{{ __('contact.subject') }}</label>
                        <input type="text" id="subject" name="subject"
                               placeholder="{{ __('contact.subject') }}">
                    </div>

                    <div class="form-group">
                        <label for="message">{{ __('contact.message') }}</label>
                        <textarea id="message" name="message" rows="5"
                                  placeholder="{{ __('contact.message_placeholder') }}"></textarea>
                    </div>

                    <x-form.file-drop />

                    <div class="form-group form-group--checkbox">
                        <label>
                            <input type="checkbox" name="gdpr" required>
                            {{ __('contact.agree') }}
                            <a href="{{ lroute('privacy') }}">{{ __('contact.policy') }}</a>
                        </label>
                    </div>

                    <div class="form-group form-group--inline">
                        <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                            <span class="btn__inner" x-show="!loading">
                                {{ __('contact.send') }}
                                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                            </span>
                            <span class="btn__inner" x-show="loading" x-cloak>
                                <svg class="btn__spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                </svg>
                                {{ __('contact.sending') ?? '...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- 3-step „Co se stane potom" — OND-136 next_steps --}}
<section class="section-wrapper section-wrapper--alt next-steps">
    <div class="container-site">
        <p class="section-subheading">{{ __('contact.next_steps.eyebrow') }}</p>
        <h2 class="section-heading">{{ __('contact.next_steps.heading') }}</h2>

        <ol class="next-steps__list">
            @foreach (__('contact.next_steps.steps') as $i => $step)
                <li class="next-steps__item">
                    <span class="next-steps__index" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="next-steps__title">{{ $step['title'] }}</h3>
                    <p class="next-steps__text">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

@endsection
