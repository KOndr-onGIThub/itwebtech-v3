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

{{-- ============================================================
     OND-392 — /kontakt v jazyce nové homepage (2. z 9), podle
     předlohy z OND-390. Obsah je NEDOTČENÝ, mění se jen slovník. Každý lang klíč, který
     stránka tiskla dřív, tiskne i teď — ve stejném znění a pořadí.

     Formulář je doslovný protějšek sekce „Poptávka" z homepage:
     `.pd-form` → `.pd-form__intro` + `.pd-form__panel` → `.pd-field`.
     Funkce se NEMĚNÍ — honeypot, přílohy, odeslání přes Alpine
     a stav po odeslání jsou na stejných místech, s týmiž atributy,
     jmény polí i id (viz poznámky u každého z nich níž).

     `pd` přibylo k `pd--depth pd--depth-sub` (základ OND-379 §4 krok 1).
     Vrstva hloubky: sekce si drží `data-pdd="contact-form"`
     a `data-pdd="contact-next"` jako PŘÍMÉ děti obalu — kužely v §E
     hloubka.css jsou psané `.pd--depth-sub > section[data-pdd="…"]`.

     CSS stránky je v podpis.css §F (sdílené komponenty + háky
     /kontakt), stín portrétu v hloubka.css §E2.
     ============================================================ --}}

<div class="pd pd--depth pd--depth-sub">

{{-- ===================================================
     01 — KDO TO JE
     Titulek slibuje „jen Ondřej", tvář stojí hned vedle.
     Na 1440 px nahrazuje portrét díru vpravo od titulku
     (dnešní hero je text na střed a 600 px prázdna).
     Hlava se jmenuje `.pd-page-head`, NIKDY `.pd-hero`
     (podmínka základu OND-379 §1c).
     =================================================== --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <div class="pd-page-head__grid">
            <div class="pd-page-head__text">
                <p class="pd-eyebrow">{{ __('contact.hero.page_mark_label') }}<span class="pd-eyebrow__sep" aria-hidden="true"></span>{{ __('contact.hero.upline') }}</p>
                <h1 class="pd-heading pd-heading--sub">{!! __('contact.hero.heading_html') !!}</h1>
                <p class="pd-sub">{{ __('contact.hero.subline') }}</p>
            </div>

            {{-- Kontaktní stín vrstvy C: `.pd-page-head__photo` v hloubka.css §E2
                 místo dnešního `.contact-info__photo-wrap`. --}}
            <figure class="pd-page-head__person">
                <div class="pd-page-head__photo">
                    <picture>
                        <source srcset="{{ asset_v('img/about/ondrej_kriska_2026_preview.webp') }}" type="image/webp">
                        <img
                            src="{{ asset_v('img/about/ondrej_kriska_2026.jpg') }}"
                            alt="{{ __('contact.hero.photo_alt') }}"
                            width="260"
                            height="325"
                        >
                    </picture>
                </div>
                <figcaption class="pd-page-head__role">{{ __('contact.hero.role_label') }}</figcaption>
            </figure>
        </div>
    </div>
</section>

{{-- ===================================================
     02 — POPTÁVKA
     Doslovný protějšek sekce „Poptávka" z homepage. Vlevo
     údaje (telefon je rovnocenná cesta — rozhodnutí boardu
     „formulář + telefon"), vpravo deska s formulářem.
     =================================================== --}}
<section class="pd-section" data-pdd="contact-form">
    <div class="container-site">
        <div class="pd-form">

            <aside class="pd-form__intro">
                {{-- OND-201 (nález 5.9): plná fakturační adresa a IČO z lang
                     souboru (OSVČ, veřejné údaje). OND-256/7: telefon z configu,
                     jedno místo pravdy. --}}
                <dl class="pd-facts">
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
                        <dt>{{ __('contact.email_label') }}</dt>
                        <dd><a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a></dd>
                    </div>

                    <div>
                        <dt>{{ __('contact.phone_label') }}</dt>
                        <dd><a href="tel:{{ preg_replace('/\s+/', '', config('contact.phone')) }}">{{ config('contact.phone') }}</a></dd>
                    </div>

                    <div>
                        <dt>{{ __('contact.hours_label') }}</dt>
                        <dd>{!! __('contact.open_hours') !!}</dd>
                    </div>
                </dl>

                {{-- Kotva na formulář. Dřív acidová pilulka — teď terciální odkaz:
                     acidové tlačítko je na stránce jedno, „Odeslat zprávu". --}}
                <p class="pd-more">
                    <a href="#kontaktni-formular" class="pd-more__link">{{ __('contact.cta_consultation') }}</a>
                </p>
            </aside>

            {{-- FUNKCE — ODESLÁNÍ: `x-data="contactForm(…)"` a `id="kontaktni-formular"`
                 zůstávají na kořeni formuláře, jen se třída `.contact-form` mění na
                 `.pd-form__panel`. Tím dostane desku, nabitou hranu i proud do políčka
                 z homepage (hloubka.css §B11, §C) — výjimka 1 ze základu je na
                 `.pd-form__panel` napsaná už teď a hloubka.js ji na podstránce
                 pozoruje. `$root.scrollIntoView` po odeslání míří sem. --}}
            <div id="kontaktni-formular" class="pd-form__panel"
                 x-data="contactForm({ genericError: @js(__('contact.message_error')) })">

                {{-- FUNKCE — STAV PO ODESLÁNÍ: `x-show="submitted"` tady,
                     `x-show="!submitted"` na titulku, perexu a formuláři níž. --}}
                <div class="pd-form__thanks" x-show="submitted" x-cloak>
                    <h2 class="pd-head__title">{{ __('contact.thank_you.heading') }}</h2>
                    <p class="pd-intro">{{ __('contact.thank_you.subline') }}</p>
                    <p class="pd-form__note">{{ __('contact.thank_you.next') }}</p>
                    <div class="pd-actions">
                        <a href="{{ lroute('projects') }}" class="pd-more__link">{{ __('contact.thank_you.cta_projects') }}</a>
                        <a href="{{ lroute('price') }}" class="pd-more__link">{{ __('contact.thank_you.cta_price') }}</a>
                    </div>
                </div>

                <h2 class="pd-head__title" x-show="!submitted">{{ __('contact.form_heading') }}</h2>
                <p class="pd-intro" x-show="!submitted">{{ __('contact.form_subheading') }}</p>

                {{-- FUNKCE — ODESLÁNÍ: `@submit.prevent="submit"`, `novalidate`,
                     `x-ref="form"` beze změny. --}}
                <form @submit.prevent="submit" novalidate x-show="!submitted" x-ref="form">
                    @csrf

                    {{-- FUNKCE — HONEYPOT: táž komponenta, totéž id. --}}
                    <x-form.honeypot id="contact-website-url" />

                    {{-- Jména polí (`name`, `email`, `tel`, `subject`, `message`,
                         `gdpr`, `attachment[]`) a id jsou beze změny — na nich stojí
                         serverová validace i `errors.*` v contactForm. Homepage
                         posílá telefon jako `phone`, tady zůstává `tel`. --}}
                    <div class="pd-form__grid">
                        <div class="pd-field pd-field--full">
                            <label for="name">{{ __('contact.name') }} <span aria-hidden="true">*</span></label>
                            <input type="text" id="name" name="name" required autocomplete="name"
                                   placeholder="{{ __('contact.name') }}"
                                   @input="clearError('name')"
                                   :aria-invalid="errors.name ? 'true' : null"
                                   :aria-describedby="errors.name ? 'name-error' : null">
                            <p class="pd-field__error" id="name-error" x-show="errors.name" x-text="errors.name" x-cloak></p>
                        </div>

                        <div class="pd-field">
                            <label for="email">{{ __('contact.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="vas@email.cz"
                                   @input="clearError('email')"
                                   :aria-invalid="errors.email ? 'true' : null"
                                   :aria-describedby="errors.email ? 'email-error' : null">
                            <p class="pd-field__error" id="email-error" x-show="errors.email" x-text="errors.email" x-cloak></p>
                        </div>

                        {{-- OND-256/4: telefon je nepovinný, pošťouchnutí pod polem
                             říká, co člověk získá, když ho vyplní (rozhodnutí boardu). --}}
                        <div class="pd-field">
                            <label for="tel">{{ __('contact.tel') }}</label>
                            <input type="tel" id="tel" name="tel" autocomplete="tel"
                                   placeholder="+420 000 000 000"
                                   @input="clearError('tel')"
                                   :aria-invalid="errors.tel ? 'true' : null"
                                   :aria-describedby="errors.tel ? 'tel-error' : 'tel-hint'">
                            <p class="pd-field__hint" id="tel-hint">{{ __('contact.tel_hint') }}</p>
                            <p class="pd-field__error" id="tel-error" x-show="errors.tel" x-text="errors.tel" x-cloak></p>
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="subject">{{ __('contact.subject') }}</label>
                            <input type="text" id="subject" name="subject"
                                   placeholder="{{ __('contact.subject') }}"
                                   @input="clearError('subject')"
                                   :aria-invalid="errors.subject ? 'true' : null"
                                   :aria-describedby="errors.subject ? 'subject-error' : null">
                            <p class="pd-field__error" id="subject-error" x-show="errors.subject" x-text="errors.subject" x-cloak></p>
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="message">{{ __('contact.message') }}</label>
                            <textarea id="message" name="message" rows="5"
                                      placeholder="{{ __('contact.message_placeholder') }}"
                                      @input="clearError('message')"
                                      :aria-invalid="errors.message ? 'true' : null"
                                      :aria-describedby="errors.message ? 'message-error' : null"></textarea>
                            <p class="pd-field__error" id="message-error" x-show="errors.message" x-text="errors.message" x-cloak></p>
                        </div>
                    </div>

                    {{-- FUNKCE — PŘÍLOHY: táž komponenta, žádný prop se nemění.
                         Mimo `.pd-field` schválně: proud do políčka patří jen
                         textovým polím (dnes to hlídal `:has()` v hloubka.css §E3). --}}
                    <x-form.file-drop />

                    {{-- Souhlas zůstává na sdílené třídě `.form-group--checkbox`
                         (landing page ji používá taky). Mimo `.pd-field` ze stejného
                         důvodu jako přílohy: zaškrtávátko se nevyplňuje. --}}
                    <div class="form-group form-group--checkbox">
                        <label>
                            <input type="checkbox" name="gdpr" required
                                   @change="clearError('gdpr')"
                                   :aria-invalid="errors.gdpr ? 'true' : null"
                                   :aria-describedby="errors.gdpr ? 'gdpr-error' : null">
                            {{-- Text i odkaz musí být JEDEN flex item (OND-374). --}}
                            <span>{{ __('contact.agree') }}<a href="{{ lroute('privacy') }}">{{ __('contact.policy') }}</a></span>
                        </label>
                        <p class="pd-field__error" id="gdpr-error" x-show="errors.gdpr" x-text="errors.gdpr" x-cloak></p>
                    </div>

                    {{-- Pád bez 422 (500, výpadek sítě) — jediná souhrnná hláška.
                         `x-ref="formError"` beze změny, jen `.form-alert` → `.pd-alert`. --}}
                    <p class="pd-alert pd-alert--error" role="alert" x-ref="formError"
                       x-show="formError" x-text="formError" x-cloak></p>

                    <button type="submit" class="pd-cta pd-form__submit" :disabled="loading">
                        <span class="btn__inner" x-show="!loading">
                            {{ __('contact.send') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                        </span>
                        <span class="btn__inner" x-show="loading" x-cloak>
                            <svg class="btn__spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            {{ __('contact.sending') ?? '...' }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     03 — CO SE STANE POTOM
     Základní `.pd-steps`, NE `--chapters`: tři krátké kroky,
     přesně to, na co je mřížka 56px 1fr psaná (základ §2c).
     Náboj `.pd-step::before` je na podstránce vypnutý plošně
     (hloubka.css §0, OND-386) — tady se nic nehýbe.
     =================================================== --}}
<section class="pd-section" data-pdd="contact-next">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <p class="pd-eyebrow">{{ __('contact.next_steps.eyebrow') }}</p>
                <h2 class="pd-head__title">{{ __('contact.next_steps.heading') }}</h2>
            </header>

            <ol class="pd-steps">
                @foreach (__('contact.next_steps.steps') as $i => $step)
                    <li class="pd-step">
                        <span class="pd-step__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="pd-step__body">
                            <h3 class="pd-step__title">{{ $step['title'] }}</h3>
                            <p class="pd-step__text">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

</div>

@endsection
