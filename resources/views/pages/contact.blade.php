@extends('layouts.app')

@section('title', __('contact.meta.title'))
@section('description', __('contact.meta.description'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro /kontakt.
     Pozn.: viz price.blade.php — schema-context klíč řešíme přes PHP blok,
     aby ho nesežrala Blade direktiva (Laravel 12 CompilesContexts). --}}
@push('jsonld')
@php
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),    'item' => lroute('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.contact'), 'item' => lroute('contact')],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@endpush

@section('content')

{{-- OND-251 — vrstva hloubky ZAPNUTÁ, v plném rozsahu (světlo + proud + hmota).
     Kontakt je rozhodovací stránka a formulář je tentýž objekt jako sekce 15
     na domovské stránce, takže dostává doslova totéž: desku, nabitou horní
     hranu a proud do políčka. Rozhodnutí a jeho důvod jsou v §E hloubka.css.
     Sekce se adresují přes `data-pdd`, nikdy přes pořadí. --}}
<div class="pd--depth pd--depth-sub">

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

<section class="section-wrapper" data-pdd="contact-form">
    <div class="container-site">

        <div class="contact-layout">

            {{-- Contact info --}}
            <aside class="contact-info">

                <div class="contact-info__photo-wrap">
                    <picture>
                        <source srcset="{{ asset_v('img/about/ondrej_kriska_preview.webp') }}" type="image/webp">
                        <img
                            src="{{ asset_v('img/about/ondrej_kriska.jpg') }}"
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
                    {{-- OND-201 (nález 5.9): dřív „Ondřej Kriška / Česká
                         republika" — signál anonymního dodavatele. Nově plná
                         fakturační adresa a IČO z lang souboru (OSVČ, jde
                         o veřejné údaje). --}}
                    <div>
                        <dt>{{ __('contact.address_label') }}</dt>
                        <dd>
                            {{ __('contact.address_name') }}<br>
                            {{ __('contact.address_street') }}<br>
                            {{ __('contact.address_city') }}<br>
                            {{ __('contact.address_registration') }}
                        </dd>
                    </div>

                    <div>
                        <dt>E-mail</dt>
                        <dd>
                            <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>
                        </dd>
                    </div>

                    {{-- OND-256/7: telefon byl na /kontakt jen v config/contact.php,
                         na stránce chyběl úplně. Číslo bere z configu, ať je
                         jedno místo pravdy. --}}
                    <div>
                        <dt>{{ __('contact.phone_label') }}</dt>
                        <dd>
                            <a href="tel:{{ preg_replace('/\s+/', '', config('contact.phone')) }}">{{ config('contact.phone') }}</a>
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
            {{-- OND-256/1: chyby se ukazují inline pod polem (stejný vzor jako
                 formulář na homepage), ne v anglickém modálu. `genericError`
                 je hláška pro pád bez 422 payloadu. --}}
            <div id="kontaktni-formular" class="contact-form"
                 x-data="contactForm({ genericError: @js(__('contact.message_error')) })">

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

                <form @submit.prevent="submit" novalidate x-show="!submitted" x-ref="form">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('contact.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="{{ __('contact.name') }}"
                               @input="clearError('name')"
                               :aria-invalid="errors.name ? 'true' : null"
                               :aria-describedby="errors.name ? 'name-error' : null">
                        <p class="form-group__error" id="name-error" x-show="errors.name" x-text="errors.name" x-cloak></p>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group form-group--inline">
                            <label for="email">{{ __('contact.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="vas@email.cz"
                                   @input="clearError('email')"
                                   :aria-invalid="errors.email ? 'true' : null"
                                   :aria-describedby="errors.email ? 'email-error' : null">
                            <p class="form-group__error" id="email-error" x-show="errors.email" x-text="errors.email" x-cloak></p>
                        </div>
                        {{-- OND-256/4: telefon je nepovinný (backend ho tak validoval
                             odjakživa, hvězdička v labelu lhala). Pošťouchnutí pod
                             polem říká, co uživatel získá, když ho vyplní. --}}
                        <div class="form-group form-group--inline">
                            <label for="tel">{{ __('contact.tel') }}</label>
                            <input type="tel" id="tel" name="tel" autocomplete="tel"
                                   placeholder="+420 000 000 000"
                                   @input="clearError('tel')"
                                   :aria-invalid="errors.tel ? 'true' : null"
                                   :aria-describedby="errors.tel ? 'tel-error' : 'tel-hint'">
                            <p class="form-group__hint" id="tel-hint">{{ __('contact.tel_hint') }}</p>
                            <p class="form-group__error" id="tel-error" x-show="errors.tel" x-text="errors.tel" x-cloak></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">{{ __('contact.subject') }}</label>
                        <input type="text" id="subject" name="subject"
                               placeholder="{{ __('contact.subject') }}"
                               @input="clearError('subject')"
                               :aria-invalid="errors.subject ? 'true' : null"
                               :aria-describedby="errors.subject ? 'subject-error' : null">
                        <p class="form-group__error" id="subject-error" x-show="errors.subject" x-text="errors.subject" x-cloak></p>
                    </div>

                    <div class="form-group">
                        <label for="message">{{ __('contact.message') }}</label>
                        <textarea id="message" name="message" rows="5"
                                  placeholder="{{ __('contact.message_placeholder') }}"
                                  @input="clearError('message')"
                                  :aria-invalid="errors.message ? 'true' : null"
                                  :aria-describedby="errors.message ? 'message-error' : null"></textarea>
                        <p class="form-group__error" id="message-error" x-show="errors.message" x-text="errors.message" x-cloak></p>
                    </div>

                    <x-form.file-drop />

                    <div class="form-group form-group--checkbox">
                        <label>
                            <input type="checkbox" name="gdpr" required
                                   @change="clearError('gdpr')"
                                   :aria-invalid="errors.gdpr ? 'true' : null"
                                   :aria-describedby="errors.gdpr ? 'gdpr-error' : null">
                            {{ __('contact.agree') }}
                            <a href="{{ lroute('privacy') }}">{{ __('contact.policy') }}</a>
                        </label>
                        <p class="form-group__error" id="gdpr-error" x-show="errors.gdpr" x-text="errors.gdpr" x-cloak></p>
                    </div>

                    {{-- Pád bez 422 (500, výpadek sítě) — jediná souhrnná hláška. --}}
                    <p class="form-alert form-alert--error" role="alert" x-ref="formError"
                       x-show="formError" x-text="formError" x-cloak></p>

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
<section class="section-wrapper section-wrapper--alt next-steps" data-pdd="contact-next">
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

</div>

@endsection
