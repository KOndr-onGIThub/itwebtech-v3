{{-- ===================================================
     OND-227 — VARIANTA D „STUDIO" (?podpis=d&barva=acid|klein|sarlat)
     Iterace 4 (Ondra 18. 9.: „Dark web ano, ale jinak a moderněji.
     Jiné barvy"). Kompozice vítězné C zůstává (fotka nese hero,
     autogram, přímočarost), retro kulisy jdou pryč: žádný papír,
     štítky, grain, rotace, patina. Povrch = současný studiový
     jazyk: vlasové linky, hodně negativního prostoru, ostrá
     typografie, přesný motion. Amber končí — tři barevné směry
     na stejném layoutu, každý s pojmenovanou rolí barvy:
       acid   #D8FF3A — barva jako signální inkoust akcí
       klein  #3B5BFF — barva jako plocha/materiál
       sarlat #FF3B30 — barva jako vzácný tah, CTA bílé
     Texty beze změny z lang/.
     =================================================== --}}
@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))
@section('hide_prefooter', 'true')

@push('preloads')
    {{-- Prototypova routa ?podpis=* nesmi do indexu (prevazi nad výchozím index,follow) --}}
    <meta name="robots" content="noindex, nofollow">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2') }}">
@endpush

@php
    $barva = request()->query('barva');
    if (!in_array($barva, ['acid', 'klein', 'sarlat'], true)) {
        $barva = 'acid';
    }

    $allTestimonials = collect(__('testimonials.items'));
    $homeTestimonialOrder = config('site.features.show_toyota_testimonial')
        ? ['Pavel Baudyš', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice']
        : ['Peter Vidlička', 'Rostislav Toman', 'Stanislav Holcmann', 'Hana Jaskmanická', 'Ing. Ivo Štěpánek', 'Václav Pešice'];
    $homeTestimonials = collect($homeTestimonialOrder)
        ->map(fn ($name) => $allTestimonials->firstWhere('name', $name))
        ->filter()
        ->values();
@endphp

@section('content')
<div class="pd pd--{{ $barva }}">

{{-- Hero — kompozice z C: fotka „tak jak je" přes pravou část,
     text v negativním prostoru vlevo, autogram, tilt. Bez grainu
     a light-leaku (patina pryč). --}}
<section class="pd-hero" id="pd-hero-tilt">
    <div class="pd-hero__photo" data-tilt>
        <x-responsive-image
            path="hero/hero-uvod.webp"
            alt="Ondřej Kriška"
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

{{-- Živé weby — flat desky, vlasové linky, přesný hover --}}
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

{{-- Social proof — jeden přesný řádek; u směru KLEIN celá plocha barvou --}}
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

{{-- Metoda — velké tenké číslice, žádná dekorace --}}
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

{{-- OND-229 F2 — Služby: tři sloupce s vlasovými linkami, bez ikon
     a boxů. Ikony jsou slovník šablon; sloupec unese titulek sám. --}}
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

{{-- OND-229 F2 — Případovky (R3: ukaž, neříkej): reálné vizuály
     nasazených webů z DB přes AVIF pipeline; dílo mluví první,
     věta o výsledku druhá. Střídavý layout, žádné karty. --}}
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

{{-- Cenová kotva — tři sloupce, vlasové linky, žádné boxy --}}
<section class="pd-section">
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
    </div>
</section>

{{-- OND-229 F2 — Proč já: video nese sekci (mluví Ondra sám),
     výhody jako tichá mřížka s vlasovými linkami vedle. --}}
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

{{-- OND-229 F2 — Pod kapotou (R3: decentní moment řemesla):
     fakta ověřitelná v repu + čas načtení změřený Performance API
     v prohlížeči návštěvníka. Bez JS zůstane řádek s časem skrytý —
     nikdy neukazujeme číslo, které jsme nenaměřili. --}}
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

        {{-- Živé demo řemesla (R3: jeden interaktivní prvek): tři
             proměnné design systému přepisují ukázku naživo. Nativní
             ovládací prvky = klávesnice i dotyk zdarma, žádná knihovna.
             Obsah karty je smyšlená firma — žádná klientská data. --}}
        <div class="pd-demo" id="pd-demo">
            <div class="pd-demo__copy">
                <p class="pd-eyebrow">{{ __('home.demo.eyebrow') }}</p>
                <h3 class="pd-demo__heading">{{ __('home.demo.heading') }}</h3>
                <p class="pd-demo__text">{{ __('home.demo.text') }}</p>

                <form class="pd-demo__controls">
                    <fieldset class="pd-demo__group">
                        <legend>{{ __('home.demo.controls.accent') }}</legend>
                        <div class="pd-demo__swatches">
                            @foreach (['acid' => '#D8FF3A', 'klein' => '#3B5BFF', 'sarlat' => '#FF3B30', 'jantar' => '#E8A64A'] as $key => $hex)
                            <label class="pd-demo__swatch">
                                <input
                                    type="radio"
                                    name="demo-accent"
                                    value="{{ $hex }}"
                                    data-on="{{ in_array($key, ['acid', 'jantar'], true) ? '#0A0A0B' : '#F2F0EA' }}"
                                    @checked($key === 'acid')
                                >
                                <span class="pd-demo__chip" style="background: {{ $hex }}" aria-hidden="true"></span>
                                {{ __('home.demo.accents.' . $key) }}
                            </label>
                            @endforeach
                        </div>
                    </fieldset>
                    <div class="pd-demo__group">
                        <label for="pd-demo-scale">{{ __('home.demo.controls.scale') }}</label>
                        <input type="range" id="pd-demo-scale" min="0.85" max="1.25" step="0.01" value="1">
                    </div>
                    <div class="pd-demo__group">
                        <label for="pd-demo-space">{{ __('home.demo.controls.space') }}</label>
                        <input type="range" id="pd-demo-space" min="0.7" max="1.6" step="0.01" value="1">
                    </div>
                </form>
            </div>

            <div class="pd-demo__stage" id="pd-demo-stage">
                <p class="pd-demo-card__eyebrow">{{ __('home.demo.card.eyebrow') }}</p>
                <p class="pd-demo-card__heading">{{ __('home.demo.card.heading') }}</p>
                <p class="pd-demo-card__text">{{ __('home.demo.card.text') }}</p>
                <div class="pd-demo-card__row">
                    <span class="pd-demo-card__cta">{{ __('home.demo.card.cta') }}</span>
                    <span class="pd-demo-card__stat">
                        <strong>{{ __('home.demo.card.stat_value') }}</strong>
                        {{ __('home.demo.card.stat_label') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- OND-229 F2 — Postup: čtyři kroky pod sebou, čas jako acid
     datový štítek; citace klientů zůstávají u kroků, kde vznikly. --}}
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

{{-- Reference — přesná mřížka citací --}}
<section class="pd-section">
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
                    <p class="pd-testi__meta">{{ $review['name'] }} — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- OND-229 F2 — FAQ: nativní details/summary, vlasové linky,
     acid křížek jako indikátor. Stejné analytics klíče a JSON-LD
     jako produkční homepage (parita pro případný flip). --}}
@php
    $faqItems = __('home.faq.items');
@endphp
<section class="pd-section" id="faq">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.faq.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">11</span>
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

{{-- OND-229 F2 — Poptávka: jediná závěrečná výzva (drží nález
     OND-201/5.8 — FAQ mikro-formulář se nepřidává, dva formuláře
     hned po sobě by výzvu rozmělnily). Stejný endpoint, pole i
     session handling jako produkční partial home-inline-form. --}}
<section class="pd-section" id="{{ __('home.anchors.poptavka') }}">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.inline_form.heading') }}</h2>
            <span class="pd-head__index" aria-hidden="true">12</span>
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
                @if (session('home_lead_success'))
                    <div class="pd-alert pd-alert--success" role="status">
                        {{ __('home.inline_form.success') }}
                    </div>
                @endif

                @if ($errors->any() && session('home_lead_target') !== 'faq')
                    <div class="pd-alert pd-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                @php
                    $isInlineTarget = session('home_lead_target') !== 'faq';
                @endphp

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
                                value="{{ $isInlineTarget ? old('name') : '' }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.name') }}"
                                autocomplete="name"
                            >
                            @if ($isInlineTarget) @error('name') <p class="pd-field__error">{{ $message }}</p> @enderror @endif
                        </div>

                        <div class="pd-field">
                            <label for="pd-lead-email">{{ __('home.inline_form.email') }} <span aria-hidden="true">*</span></label>
                            <input
                                type="email"
                                id="pd-lead-email"
                                name="email"
                                value="{{ $isInlineTarget ? old('email') : '' }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.email') }}"
                                autocomplete="email"
                            >
                            @if ($isInlineTarget) @error('email') <p class="pd-field__error">{{ $message }}</p> @enderror @endif
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-phone">{{ __('home.inline_form.phone') }}</label>
                            <input
                                type="tel"
                                id="pd-lead-phone"
                                name="phone"
                                value="{{ $isInlineTarget ? old('phone') : '' }}"
                                placeholder="{{ __('home.inline_form.placeholders.phone') }}"
                                autocomplete="tel"
                            >
                            @if ($isInlineTarget) @error('phone') <p class="pd-field__error">{{ $message }}</p> @enderror @endif
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-message">{{ __('home.inline_form.message') }} <span aria-hidden="true">*</span></label>
                            <textarea
                                id="pd-lead-message"
                                name="message"
                                rows="5"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.message') }}"
                            >{{ $isInlineTarget ? old('message') : '' }}</textarea>
                            @if ($isInlineTarget) @error('message') <p class="pd-field__error">{{ $message }}</p> @enderror @endif
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
    // Tilt portrétu převzatý z C — vanilla, no-op na touch a reduced-motion.
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

    // OND-229 — Živé demo: tři proměnné přepisují CSS tokeny ukázky.
    (function () {
        var demo = document.getElementById('pd-demo');
        var stage = document.getElementById('pd-demo-stage');
        if (!demo || !stage) return;
        demo.addEventListener('input', function (e) {
            var t = e.target;
            if (t.name === 'demo-accent') {
                stage.style.setProperty('--demo-accent', t.value);
                stage.style.setProperty('--demo-accent-on', t.dataset.on);
            } else if (t.id === 'pd-demo-scale') {
                stage.style.setProperty('--demo-scale', t.value);
            } else if (t.id === 'pd-demo-space') {
                stage.style.setProperty('--demo-space', t.value);
            }
        });
    })();

    // OND-229 — po submitu formuláře (redirect back()) doskrolovat
    // k výsledku, stejně jako na produkční homepage.
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any() || session('home_lead_success'))
        document.getElementById('{{ __('home.anchors.poptavka') }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        @endif
    });
</script>
@endpush
