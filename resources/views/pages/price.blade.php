@extends('layouts.app')

@section('title', __('price.meta.title'))
@section('description', __('price.meta.description'))

{{-- OND-137 P4 §SEO: Service JSON-LD per tier + BreadcrumbList.
     JSON-LD pole se sestavují v PHP bloku a echují přes
     předvypočtenou stringovou proměnnou. Inline schema-context klíč
     uvnitř json_encode echo bloku naráží na Blade direktivu
     (Laravel 12 CompilesContexts) — token by se přepsal na PHP kód
     a JSON klíč by byl zničený. PHP blok Blade neparsuje na direktivy. --}}
@push('jsonld')
@php
    // OND-137 P4 §SEO bug-fix: locale → priceCurrency mapping, ať Service
    // JSON-LD pro EN/DE nehlásí EUR magnitudu s priceCurrency=CZK.
    $priceCurrency = ['cs' => 'CZK', 'en' => 'EUR', 'de' => 'EUR'][app()->getLocale()] ?? 'CZK';

    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),  'item' => lroute('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.price'), 'item' => lroute('price')],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $serviceJsons = [];
    foreach (__('price.tiers') as $tier) {
        $tierPriceNum = (int) preg_replace('/[^0-9]/', '', $tier['price']);
        $serviceLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'serviceType' => 'Web development',
            'name' => $tier['name'],
            'description' => $tier['desc'],
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url'  => url('/'),
            ],
            'areaServed' => ['CZ', 'SK', 'DE', 'AT'],
            'offers' => [
                '@type' => 'Offer',
                'price' => $tierPriceNum,
                'priceCurrency' => $priceCurrency,
                'url' => lroute('price'),
            ],
        ];
        $serviceJsons[] = json_encode($serviceLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@foreach ($serviceJsons as $serviceJson)
<script type="application/ld+json">
{!! $serviceJson !!}
</script>
@endforeach
@endpush

@section('content')

{{-- Page hero — OND-135 iter 5: plán §3.1 design DNA (page-mark + display + amber accent) --}}
<div class="page-hero page-hero--price">
    <div class="container-site">
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt (itwebtech nemá „pages" hierarchii) —
             aplikováno per CEO PR #78 precedent (home) + PR #80 (kontakt). --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('price.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('price.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('price.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('price.hero.subline') }}</p>
    </div>
</div>

{{-- Pricing tiers --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">

        {{-- OND-198 (nález 5.4): očekávací věta musí padnout dřív, než čtenář
             uvidí první číslo. Pásma jsou v lang souboru seřazená
             Standard → Custom → Startovní, nejlevnější je poslední. --}}
        <p class="pricing-expectation">{{ __('price.intro') }}</p>

        <div class="pricing-tiers" data-reveal-group>
            @foreach (__('price.tiers') as $tier)
            @php
                // OND-137 P4 §6: pricing_tier_shown custom dimension (25/55/95) —
                // extrahované z tier['price'] (např. "25 000 Kč" → "25").
                $tierShown = (int) preg_replace('/[^0-9]/', '', $tier['price']);
                $tierShown = (string) (int) ($tierShown / 1000); // 25000 → "25"
            @endphp
            <article class="pricing-tier {{ $tier['popular'] ? 'pricing-tier--featured' : '' }}"
                     data-analytics-view="pricing_tier_view"
                     data-analytics-props='{"pricing_tier_shown":"{{ $tierShown }}"}'>

                @if ($tier['popular'])
                <span class="pricing-tier__badge">{{ __('price.popular') }}</span>
                @endif

                <header class="pricing-tier__header">
                    <h2 class="pricing-tier__name">{{ $tier['name'] }}</h2>
                    <p class="pricing-tier__desc">{{ $tier['desc'] }}</p>
                    <div class="pricing-tier__price">{{ $tier['price'] }}</div>
                    <p class="pricing-tier__price-note">{{ __('price.price_note') }}</p>
                </header>

                <ul class="pricing-tier__features">
                    @foreach ($tier['features'] as $feature)
                    <li>
                        <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ lroute('contact') }}"
                   class="btn {{ $tier['popular'] ? 'btn-primary' : 'btn-secondary' }} pricing-tier__cta"
                   data-analytics="pricing_tier_cta_primary_click"
                   data-analytics-props='{"pricing_tier_shown":"{{ $tierShown }}"}'>
                    {{ $tier['cta'] }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>

            </article>
            @endforeach
        </div>

        <p class="pricing-note">{{ __('price.note') }}</p>

    </div>
</section>

{{-- Feature comparison table --}}
@php
    $compareTiers  = __('price.compare.tiers');
    $compareGroups = __('price.compare.groups');
    $tierPrices    = array_column(__('price.tiers'), 'price');
    // OND-198 (nález 5.4): zvýrazněný sloupec se odvozuje z příznaku `popular`,
    // ne z pevného indexu 1 — pořadí pásem se změnilo (Standard je první).
    $featuredIdx   = array_search(true, array_column(__('price.tiers'), 'popular'), true);
    $featuredIdx   = $featuredIdx === false ? -1 : $featuredIdx;
@endphp
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.compare.heading') }}</h2>
        </header>

        {{-- DESKTOP: full 3-column table --}}
        <div class="pricing-compare pricing-compare--desktop">
            <table class="pricing-compare__table">
                <thead>
                    <tr>
                        <th></th>
                        @foreach ($compareTiers as $i => $tier)
                        <th class="{{ $i === $featuredIdx ? 'is-featured' : '' }}">{{ $tier }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compareGroups as $group)
                    <tr class="pricing-compare__group-row">
                        <td colspan="4">{{ $group['label'] }}</td>
                    </tr>
                    @foreach ($group['rows'] as $row)
                    <tr>
                        <td class="pricing-compare__feature">{{ $row['label'] }}</td>
                        @foreach ($row['values'] as $vi => $val)
                        <td class="{{ $vi === $featuredIdx ? 'is-featured' : '' }}">
                            @if ($val === true)
                                <span class="pricing-compare__yes">
                                    <x-icon.circle-check-big class="w-4 h-4" />
                                    <span class="sr-only">{{ __('price.compare.included') }}</span>
                                </span>
                            @elseif ($val === false)
                                <span class="pricing-compare__no" aria-hidden="true">—</span>
                                <span class="sr-only">{{ __('price.compare.not_included') }}</span>
                            @else
                                <span class="pricing-compare__val">{{ $val }}</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE: tab switcher + single column --}}
        <div class="pricing-compare pricing-compare--mobile"
             x-data="{ active: 0, prices: {{ json_encode($tierPrices) }} }"
             x-cloak>

            {{-- Tab header --}}
            <div class="pcm-header">
                <div class="pcm-tabs" role="tablist" aria-label="{{ __('price.compare.tabs_aria') }}">
                    @foreach ($compareTiers as $i => $tier)
                    <button class="pcm-tab"
                            id="pcm-tab-{{ $i }}"
                            :class="{ 'is-active': active === {{ $i }} }"
                            @click="active = {{ $i }}"
                            type="button"
                            role="tab"
                            aria-controls="pcm-panel"
                            :aria-selected="(active === {{ $i }}).toString()"
                            :tabindex="active === {{ $i }} ? 0 : -1">
                        {{ $tier }}
                    </button>
                    @endforeach
                </div>
                <div class="pcm-price" x-text="prices[active]"></div>
            </div>

            {{-- Feature rows (single dynamic tabpanel labelled by the active tab). --}}
            <div id="pcm-panel"
                 role="tabpanel"
                 :aria-labelledby="'pcm-tab-' + active"
                 aria-live="polite">
                @foreach ($compareGroups as $group)
                <div class="pcm-group">{{ $group['label'] }}</div>
                @foreach ($group['rows'] as $row)
                <div class="pcm-row">
                    <span class="pcm-feature">{{ $row['label'] }}</span>
                    <span class="pcm-value-wrap">
                        @foreach ($row['values'] as $vi => $val)
                        <span x-show="active === {{ $vi }}">
                            @if ($val === true)
                                <span class="pricing-compare__yes">
                                    <x-icon.circle-check-big class="w-4 h-4" aria-hidden="true" focusable="false" />
                                    <span class="sr-only">{{ __('price.compare.included') }}</span>
                                </span>
                            @elseif ($val === false)
                                <span class="pricing-compare__no" aria-hidden="true">—</span>
                                <span class="sr-only">{{ __('price.compare.not_included') }}</span>
                            @else
                                <span class="pricing-compare__val">{{ $val }}</span>
                            @endif
                        </span>
                        @endforeach
                    </span>
                </div>
                @endforeach
                @endforeach
            </div>

        </div>

    </div>
</section>

{{-- What's included --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.guarantees.heading') }}</h2>
        </header>

        <div class="pricing-guarantees" data-reveal-group>
            @foreach (__('price.guarantees.items') as $g)
            <div class="pricing-guarantee">
                <h3>{{ $g['title'] }}</h3>
                <p>{{ $g['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Addons --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.addons.heading') }}</h2>
            <p class="section-header__desc">{{ __('price.addons.desc') }}</p>
        </header>

        <div class="pricing-addons" data-reveal-group>
            @foreach (__('price.addons.items') as $addon)
            <div class="pricing-addon">
                <div class="pricing-addon__info">
                    <h3>{{ $addon['name'] }}</h3>
                    <p>{{ $addon['desc'] }}</p>
                </div>
                <div class="pricing-addon__price">{{ $addon['price'] }}</div>
                <a href="{{ lroute('contact') }}" class="btn btn-secondary pricing-addon__cta">
                    {{ __('price.quotation') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Sticky CTA — plán „cena nikdy nezmizí" (OND-135 iter 5).
     Zobrazí se po prvním scroll-passu hero, skryje se v final CTA sekci. --}}
<aside class="price-sticky-cta"
       x-data="{ visible: false }"
       x-init="
         const trigger = () => { visible = window.scrollY > 480 && window.scrollY < (document.body.scrollHeight - window.innerHeight - 320); };
         trigger();
         window.addEventListener('scroll', trigger, { passive: true });
         window.addEventListener('resize', trigger, { passive: true });
       "
       x-show="visible"
       x-transition.opacity.duration.300ms
       x-cloak
       aria-label="{{ __('price.sticky_cta.label') }}">
    <a href="{{ lroute('contact') }}" class="price-sticky-cta__btn">
        <span class="price-sticky-cta__label">{{ __('price.sticky_cta.cta') }}</span>
        <x-icon.arrow-right class="w-4 h-4 shrink-0" />
    </a>
</aside>

{{-- CTA --}}
<section class="section-wrapper section-cta price-cta" data-reveal>
    <div class="container-site">
        <div class="price-cta__inner">
            <h2 class="final-cta-heading">{{ __('price.cta.heading') }}</h2>
            <p class="price-cta__desc">{{ __('price.cta.desc') }}</p>
            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('price.cta.btn') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        </div>
    </div>
</section>

@endsection
