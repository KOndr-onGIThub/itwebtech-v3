{{-- ===================================================
     HOMEPAGE — vizuální podpis ACID (OND-231 F3)

     Historie: podpis vznikl v OND-227 jako prototyp `?podpis=d&barva=acid`,
     dostavěn v OND-229 (F2). F3 ho povyšuje na produkční `/`: prototypové
     větvení v PageController@home je pryč, varianty a/b/c smazané,
     `noindex` odstraněn. Prefix tříd `pd-` (podpis D) zůstal — je to
     jediný žijící vizuální jazyk webu, přejmenování by bylo jen šum
     v diffu (styly viz resources/css/podpis.css).

     Gramatika ACID:
       - barva je signální inkoust: existuje jen tam, kde je akce nebo důraz
       - vlasové linky místo karet a boxů, hodně negativního prostoru
       - žádný obsah nestartuje v opacity: 0 (tj. žádné `data-reveal`)
       - pohyb je přesný a účelový, ne dekorativní

     F3 doplnil oproti prototypu D (parita s původní homepage):
       loga klientů, sekce „Generátor versus váš byznys", původ principů
       (Toyota), záruky, CTA na ceník, analytics-view kotvy a dispatch
       konverzních eventů po úspěšném odeslání formuláře.
     =================================================== --}}
@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))
{{-- Závěrečná výzva je poslední sekce stránky — generický prefooter
     by za ní byl čtvrtá CTA v řadě (nález OND-201/5.8). --}}
@section('hide_prefooter', 'true')

{{-- OND-145 P2 — preload display fontu (IBM Plex Sans Variable wght axis)
     pro hero LCP. Variable woff2 nese weights 100–700 v jednom souboru.
     latin + latin-ext kvůli CS diakritice; DE umlauty jsou v latin subsetu. --}}
@push('preloads')
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2') }}">
@endpush

@php
    $allTestimonials = collect(__('testimonials.items'));
    // Toyota (Pavel Baudyš) je za feature flagem (publikační souhlas).
    // Když je off → fallback Peter Vidlička (Yolk studio, dlouhodobý B2B).
    $homeTestimonialOrder = config('site.features.show_toyota_testimonial')
        ? ['Pavel Baudyš', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice']
        : ['Peter Vidlička', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice'];
    $homeTestimonials = collect($homeTestimonialOrder)
        ->map(fn ($name) => $allTestimonials->firstWhere('name', $name))
        ->filter()
        ->values();
@endphp

@section('content')
<div class="pd">

{{-- ===================================================
     01 — HERO
     Fotka „tak jak je" přes pravou část, text v negativním
     prostoru vlevo, autogram. Tilt jen na hover zařízeních.
     =================================================== --}}
<section class="pd-hero" id="pd-hero-tilt">
    <div class="pd-hero__photo" data-tilt>
        <x-responsive-image
            path="hero/hero-uvod.webp"
            alt="Ondřej Kriška — weby a aplikace na míru"
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

{{-- ===================================================
     02 — ŽIVÉ WEBY (důkaz hned po hero)
     =================================================== --}}
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

{{-- ===================================================
     SOCIAL PROOF — jeden přesný řádek + tichá řada klientů.
     OND-231: loga klientů se v prototypu D ztratila; jsou to
     ověřitelná jména, ne dekorace, takže se vracejí — ale bez
     rámečků, jen jako ztlumená řada, která ožije na hover.
     =================================================== --}}
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

<section class="pd-clients" aria-label="{{ __('home.social_proof.clients_aria') }}">
    <div class="container-site">
        <ul class="pd-clients__list">
            {{-- OND-231: záměrně jen jména, ne loga. Dvě z nich existují jen
                 jako rastry se světlým pozadím (yolk, pitarena) a na dark
                 ploše se z nich staly šedé placky mezi textovými jmény.
                 Důkazem je jméno klienta, ne jeho logo — řada tak drží
                 jednu sazbu a ACID gramatiku. --}}
            @foreach (__('home.social_proof.brands') as $brand)
            <li class="pd-clients__item">
                <span class="pd-clients__name">{{ $brand['name'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ===================================================
     03 — METODA (co dělám + krátké vymezení)
     =================================================== --}}
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

{{-- ===================================================
     04 — SLUŽBY
     Tři sloupce s vlasovými linkami, bez ikon a boxů —
     ikony jsou slovník šablon, sloupec unese titulek sám.
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.services.heading_primary') }}</h2>
            <span class="pd-head__index" aria-hidden="true">04</span>
        </header>

        <div class="pd-services">
            @foreach (['weby', 'aplikace', 'eshop'] as $i => $key)
            <article class="pd-service">
                <span class="pd-service__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="pd-service__title">{{ __("home.services.primary.{$key}.title") }}</h3>
                <p class="pd-service__desc">{{ __("home.services.primary.{$key}.description") }}</p>
                <ul class="pd-service__bullets">
                    @foreach (__("home.services.primary.{$key}.bullets") as $bullet)
                    <li>{{ $bullet }}</li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>

        <p class="pd-services__secondary">
            {!! __('home.services.secondary_inline', [
                'pricing_link' => '<a href="' . lroute('price') . '">' . e(__('home.services.secondary_inline_pricing')) . '</a>',
                'contact_link' => '<a href="#' . __('home.anchors.poptavka') . '">' . e(__('home.services.secondary_inline_contact')) . '</a>',
            ]) !!}
        </p>
    </div>
</section>

{{-- ===================================================
     05 — PŘÍPADOVKY (ukaž, neříkej)
     Reálné vizuály nasazených webů z DB přes AVIF pipeline;
     dílo mluví první, věta o výsledku druhá.
     Zapnuto přes SHOW_PORTFOLIO_SECTION (config/site.php).
     =================================================== --}}
@if (config('site.features.show_portfolio_section') && ($featuredHomeProjects ?? collect())->isNotEmpty())
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.portfolio.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">05</span>
        </header>

        <div class="pd-cases">
            @foreach ($featuredHomeProjects as $i => $project)
                @php
                    $t = $project->translation();
                    $cardCopy = __('home.portfolio.cards.' . $project->slug);
                    $clientLabel = is_array($cardCopy) && !empty($cardCopy['client'])
                        ? $cardCopy['client']
                        : ($project->client_name ?: ($t?->title ?? $project->slug));
                    $outcome = is_array($cardCopy) && !empty($cardCopy['outcome'])
                        ? $cardCopy['outcome']
                        : ($t?->subtitle ?? '');
                    // OND-202: jednotný výběr náhledovky — viz portfolio_card_thumbnail().
                    $hero = portfolio_card_thumbnail($project->screenshots ?? collect());
                    $detailHref = $project->detailUrl();
                @endphp
                <article class="pd-case">
                    <a
                        href="{{ $detailHref }}"
                        class="pd-case__visual"
                        aria-label="{{ $clientLabel }} — {{ __('home.portfolio.detail_cta') }}"
                        data-analytics="project_card_click"
                        data-analytics-props='{"slug":"{{ $project->slug }}"}'
                    >
                        @if ($hero)
                            <x-portfolio.screenshot
                                :path="$hero->path"
                                :alt="$clientLabel"
                                sizes="(min-width: 1024px) 58vw, 100vw"
                                loading="lazy"
                            />
                        @endif
                    </a>
                    <div class="pd-case__body">
                        <span class="pd-case__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="pd-case__client">{{ $clientLabel }}</h3>
                        @if ($outcome)
                        <p class="pd-case__outcome">{{ $outcome }}</p>
                        @endif
                        <a
                            href="{{ $detailHref }}"
                            class="pd-case__cta"
                            data-analytics="project_card_click"
                            data-analytics-props='{"slug":"{{ $project->slug }}"}'
                        >{{ __('home.portfolio.detail_cta') }} &rarr;</a>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="pd-more"><a href="{{ lroute('projects') }}" class="pd-more__link">{{ __('home.portfolio.cta') }}</a></p>
    </div>
</section>
@endif

{{-- ===================================================
     06 — CENOVÁ KOTVA
     Pořadí pásem je dané lang souborem, zvýrazněné pásmo
     se řídí klíčem `featured`, ne pozicí v poli (OND-198/5.4).
     =================================================== --}}
<section class="pd-section" id="section-price" data-analytics-view="price_anchor_view">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.price_anchor.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">06</span>
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

        {{-- OND-231: odkaz na detailní ceník se v prototypu ztratil —
             je to jediná cesta z kotvy na rozpad cen. --}}
        <p class="pd-more">
            <a href="{{ lroute('price') }}" class="pd-more__link" data-analytics="price_anchor_cta_click">
                {{ __('home.price_anchor.cta') }}
            </a>
        </p>
    </div>
</section>

{{-- ===================================================
     07 — PROČ JÁ
     Video nese sekci (mluví Ondra sám), výhody jako tichá
     mřížka s vlasovými linkami vedle.
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.why_me.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">07</span>
        </header>

        <div class="pd-why">
            <div class="pd-why__media">
                <div class="pd-why__video">
                    <x-video-intro :ariaLabel="__('home.why_me.video_aria')" />
                </div>
                <p class="pd-why__bio">{{ __('home.why_me.bio') }}</p>
            </div>

            <div class="pd-why__grid">
                @foreach (__('home.why_me.advantages') as $i => $adv)
                <article class="pd-why__item">
                    <span class="pd-why__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h3>{{ $adv['heading'] }}</h3>
                        <p>{{ $adv['text'] }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     08 — POD KAPOTOU (decentní moment řemesla)
     Fakta ověřitelná v repu + čas načtení změřený Performance
     API v prohlížeči návštěvníka. Bez JS zůstane řádek s časem
     skrytý — nikdy neukazujeme číslo, které jsme nenaměřili.
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.craft.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">08</span>
        </header>
        <p class="pd-intro">{{ __('home.craft.intro') }}</p>

        <div class="pd-hood">
            @foreach (__('home.craft.facts') as $fact)
            <article class="pd-hood__fact">
                <h3>{{ $fact['heading'] }}</h3>
                <p>{{ $fact['text'] }}</p>
            </article>
            @endforeach
        </div>

        <p class="pd-hood__perf" id="pd-perf" hidden>
            {{ __('home.craft.perf_prefix') }}
            <strong id="pd-perf-value"></strong>
            {{ __('home.craft.perf_suffix') }}
        </p>

    </div>
</section>

{{-- ===================================================
     09 — POSTUP
     Čtyři kroky pod sebou, čas jako acid datový štítek;
     citace klientů zůstávají u kroků, kde vznikly.
     =================================================== --}}
<section class="pd-section" id="{{ __('home.anchors.how_i_work') }}">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.how_i_work.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">09</span>
        </header>

        <ol class="pd-steps">
            @foreach (__('home.how_i_work.steps') as $i => $step)
            <li class="pd-step">
                <span class="pd-step__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="pd-step__body">
                    <h3 class="pd-step__title">{{ $step['heading'] }}@if (!empty($step['time'])) <em>{{ $step['time'] }}</em>@endif</h3>
                    <p class="pd-step__text">{{ $step['text'] }}</p>
                    @if (!empty($step['quote_text']))
                    <blockquote>
                        {{ $step['quote_text'] }}
                        <footer>— {{ $step['quote_author'] }}</footer>
                    </blockquote>
                    @endif
                    @if (!empty($step['note']))
                    <p class="pd-step__note">{{ $step['note'] }}</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>

        {{-- Rezervace: přímý odkaz místo vendor widgetu — Reservanto
             button si nese vlastní žluté barvy a rozbíjel by podpis;
             direct_url je stejný cíl (viz config/site.php, OND-123). --}}
        <div class="pd-steps__cta">
            <p class="pd-steps__cta-intro">{{ __('home.how_i_work.cta_intro') }}</p>
            @php
                $booking = config('site.booking');
            @endphp
            @if (($booking['enabled'] ?? false) && !empty($booking['direct_url']))
                <a href="{{ $booking['direct_url'] }}" target="_blank" rel="noopener" class="pd-cta" data-analytics="final_cta_secondary_click">
                    {{ __('home.how_i_work.cta_label') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                </a>
            @else
                <a href="#{{ __('home.anchors.poptavka') }}" class="pd-cta">
                    {{ __('home.how_i_work.cta_label') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                </a>
            @endif
        </div>
    </div>
</section>

{{-- ===================================================
     10 — REFERENCE
     =================================================== --}}
<section class="pd-section" id="section-testimonials" data-analytics-view="testimonial_view">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.testimonials.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">10</span>
        </header>

        <div class="pd-testi">
            @foreach ($homeTestimonials as $i => $review)
            <article class="pd-testi__item">
                <span class="pd-testi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <p class="pd-testi__text">{{ $review['text'] }}</p>
                    <p class="pd-testi__meta">
                        {{ $review['name'] }} — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif
                        @php
                            $sourceLabels = [
                                'google'   => 'Google',
                                'facebook' => 'Facebook',
                                'firmy_cz' => 'Firmy.cz',
                            ];
                        @endphp
                        @if (!empty($sourceLabels[$review['source'] ?? '']))
                        <span class="pd-testi__source">{{ $sourceLabels[$review['source']] }}</span>
                        @endif
                    </p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     11 — GENERÁTOR VERSUS VÁŠ BYZNYS
     OND-231: sekce z původní homepage, přepsaná do ACID —
     dva sloupce oddělené vlasovou linkou, žádné ikonky
     v kolečkách, rozdíl nese sazba a barva jen u převažující
     strany. Argument, proč zákazník neřeší jen „udělat web".
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.ai.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">11</span>
        </header>
        <p class="pd-lead">{{ __('home.ai.subheading') }}</p>
        <p class="pd-intro">{{ __('home.ai.intro') }}</p>

        <div class="pd-versus">
            <div class="pd-versus__col">
                <p class="pd-versus__label">{{ __('home.ai.laik.label') }}</p>
                <p class="pd-versus__outcome">{{ __('home.ai.laik.outcome') }}</p>
                <ul class="pd-versus__list">
                    @foreach (__('home.ai.laik.items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <p class="pd-versus__note">{{ __('home.ai.laik.note') }}</p>
            </div>

            <div class="pd-versus__col pd-versus__col--mine">
                <p class="pd-versus__label">{{ __('home.ai.expert.label') }}</p>
                <p class="pd-versus__outcome">{{ __('home.ai.expert.outcome') }}</p>
                <ul class="pd-versus__list">
                    @foreach (__('home.ai.expert.items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <p class="pd-versus__note">{{ __('home.ai.expert.note') }}</p>
            </div>
        </div>

        <p class="pd-versus__closing">{{ __('home.ai.closing') }}</p>
    </div>
</section>

{{-- ===================================================
     12 — ODKUD POCHÁZEJÍ MÉ PRINCIPY
     OND-231: sekce z původní homepage. Je to jediné místo,
     kde web vysvětluje, proč Ondra pracuje tak, jak pracuje.
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.toyota.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">12</span>
        </header>

        <div class="pd-origin">
            <div class="pd-origin__text">
                <p>{{ __('home.toyota.text') }}</p>
                <p>{{ __('home.toyota.text_2') }}</p>
            </div>
            <blockquote class="pd-origin__quote">
                {{ __('home.toyota.quote_text') }}
                <footer>— {{ __('home.toyota.quote_author') }}</footer>
            </blockquote>
        </div>
    </div>
</section>

{{-- ===================================================
     13 — ZÁRUKY
     OND-231: sekce z původní homepage — snížení rizika těsně
     před FAQ a výzvou. Vlasové linky, žádné karty.
     =================================================== --}}
<section class="pd-section">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.guarantee.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">13</span>
        </header>

        <div class="pd-promise">
            @foreach (__('home.guarantee.items') as $i => $item)
            <article class="pd-promise__item">
                <span class="pd-promise__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <h3>{{ $item['heading'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================================================
     14 — FAQ
     Nativní details/summary, vlasové linky, acid křížek jako
     indikátor. Stejné analytics klíče i JSON-LD jako dřív —
     FAQPage rich snippet se generuje ze stejných lang klíčů
     jako accordion (single source of truth).
     =================================================== --}}
@php
    $faqItems = __('home.faq.items');
@endphp
<section class="pd-section" id="faq">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.faq.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">14</span>
        </header>

        <div class="pd-faq">
            @foreach ($faqItems as $i => $item)
            <details class="pd-faq__item" data-q-id="{{ $i }}">
                <summary
                    class="pd-faq__q"
                    data-analytics="faq_item_open"
                    data-faq-key="{{ $item['key'] ?? 'item-' . $i }}"
                >
                    <span>{{ $item['question'] }}</span>
                    <span class="pd-faq__mark" aria-hidden="true"></span>
                </summary>
                <p class="pd-faq__a">{{ $item['answer'] }}</p>
            </details>
            @endforeach
        </div>

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
    </div>
</section>

{{-- ===================================================
     15 — POPTÁVKA
     Jediná závěrečná výzva (nález OND-201/5.8 — FAQ
     mikro-formulář se nevrací, dva formuláře hned po sobě
     výzvu rozmělní). Stejný endpoint, pole i session
     handling jako partial home-inline-form, který stránka
     nahradila; `home.faq` větve v session zůstávají
     obslouženy pro případ redirectu z jiného zdroje.
     =================================================== --}}
<section class="pd-section" id="{{ __('home.anchors.poptavka') }}">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.inline_form.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">15</span>
        </header>

        <div class="pd-form">
            <div class="pd-form__intro">
                <p class="pd-intro">{{ __('home.inline_form.description') }}</p>
                <blockquote class="pd-form__quote">
                    {{ __('home.inline_form.quote_text') }}
                    <footer>— {{ __('home.inline_form.quote_author') }}</footer>
                </blockquote>
            </div>

            <div class="pd-form__panel">
                @if (session('home_lead_success') || session('faq_lead_success'))
                    <div class="pd-alert pd-alert--success" role="status">
                        {{ __('home.inline_form.success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="pd-alert pd-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('home.lead.store') }}"
                    novalidate
                    x-data="{ submitting: false }"
                    @submit="submitting = true; window.dispatchEvent(new CustomEvent('inline-form-submit-attempt'))"
                >
                    @csrf

                    <div class="pd-form__grid">
                        <div class="pd-field">
                            <label for="pd-lead-name">{{ __('home.inline_form.name') }} <span aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                id="pd-lead-name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.name') }}"
                                autocomplete="name"
                            >
                            @error('name') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field">
                            <label for="pd-lead-email">{{ __('home.inline_form.email') }} <span aria-hidden="true">*</span></label>
                            <input
                                type="email"
                                id="pd-lead-email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.email') }}"
                                autocomplete="email"
                            >
                            @error('email') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-phone">{{ __('home.inline_form.phone') }}</label>
                            <input
                                type="tel"
                                id="pd-lead-phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="{{ __('home.inline_form.placeholders.phone') }}"
                                autocomplete="tel"
                            >
                            @error('phone') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-message">{{ __('home.inline_form.message') }} <span aria-hidden="true">*</span></label>
                            <textarea
                                id="pd-lead-message"
                                name="message"
                                rows="5"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.message') }}"
                            >{{ old('message') }}</textarea>
                            @error('message') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="pd-cta pd-form__submit"
                        :disabled="submitting"
                        data-analytics="inline_form_submit_attempt"
                    >
                        <span x-show="!submitting">{{ __('home.inline_form.submit') }}</span>
                        <span x-show="submitting" x-cloak>{{ __('home.inline_form.submitting') }}</span>
                    </button>

                    <p class="pd-form__note">{{ __('home.inline_form.note') }}</p>

                    <p class="pd-form__privacy">
                        {{ __('home.inline_form.privacy_prefix') }}<a href="{{ lroute('privacy') }}">{{ __('home.inline_form.privacy_link') }}</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
    // Tilt portrétu — vanilla, no-op na touch a reduced-motion.
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

    // OND-229 — Pod kapotou: skutečný čas načtení z Performance API.
    // Bez podpory API zůstane odstavec `hidden` — žádné vymyšlené číslo.
    (function () {
        function show() {
            var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
            if (!nav || !nav.loadEventEnd) return;
            var s = nav.loadEventEnd / 1000;
            if (!(s > 0) || s > 60) return;
            var value = document.getElementById('pd-perf-value');
            var wrap = document.getElementById('pd-perf');
            if (!value || !wrap) return;
            value.textContent = s.toLocaleString(document.documentElement.lang || 'cs', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            }) + ' s';
            wrap.hidden = false;
        }
        if (document.readyState === 'complete') { setTimeout(show, 0); }
        else { window.addEventListener('load', function () { setTimeout(show, 0); }); }
    })();

    // Po submitu formuláře (redirect back()) doskrolovat k výsledku
    // a ohlásit konverzi analytics vrstvě (viz resources/js/analytics.js).
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any() || session('home_lead_success') || session('faq_lead_success'))
        document.getElementById('{{ __('home.anchors.poptavka') }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        @endif

        @if (session('home_lead_success') || session('faq_lead_success'))
        window.dispatchEvent(new CustomEvent('inline-form-submit-success'));
        @endif
    });
</script>
@endpush
