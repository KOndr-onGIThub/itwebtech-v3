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

     Formulář je od OND-448 (B-01) TÝŽ jako v sekci „Poptávka" na homepage:
     sdílená komponenta `<x-lead-form>` (`.pd-form__panel` → `.pd-field`).

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
                {{-- OND-437 (návrh 1): konkrétní den odpovědi, viz App\Support\ReplyDate. --}}
                <p class="pd-sub">{!! \App\Support\ReplyDate::sentence('contact.hero.subline', \App\Support\ReplyDate::date()) !!}</p>
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
                     acidové tlačítko je na stránce jedno, „Poslat poptávku". --}}
                <p class="pd-more">
                    <a href="#kontaktni-formular" class="pd-more__link">{{ __('contact.cta_consultation') }}</a>
                </p>
            </aside>

            {{-- OND-448 (B-01): sdílený `<x-lead-form>` — tentýž formulář jako
                 na homepage (pole, chování, potvrzení). Předmět a zaškrtávací
                 souhlas jsou pryč, přílohy sbalené. `id="kontaktni-formular"`
                 drží kotvu „Napište mi“ a spodní lištu na mobilu. Titulek
                 a perex jsou uvnitř: po odeslání je vymění potvrzení. --}}
            <x-lead-form source="contact" id="kontaktni-formular" :note="false">
                <h2 class="pd-head__title" x-show="!submitted">{{ __('contact.form_heading') }}</h2>
                <p class="pd-intro" x-show="!submitted">{{ __('contact.form_subheading') }}</p>
            </x-lead-form>
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
