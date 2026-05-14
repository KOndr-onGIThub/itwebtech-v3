@extends('layouts.app')

@section('title', __('price.meta.title'))
@section('description', __('price.meta.description'))

@section('content')

{{-- Page hero — OND-135 iter 5: plán §3.1 page-mark + Fraunces italic display --}}
<div class="page-hero page-hero--price">
    <div class="container-site">
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('price.hero.page_mark_label') }}</span>
            <span class="page-hero__page-mark-index" aria-hidden="true">{{ __('price.hero.page_mark_index') }}</span>
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

        <div class="pricing-tiers" data-reveal-group>
            @foreach (__('price.tiers') as $tier)
            <article class="pricing-tier {{ $tier['popular'] ? 'pricing-tier--featured' : '' }}">

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

                <a href="{{ lroute('contact') }}" class="btn {{ $tier['popular'] ? 'btn-primary' : 'btn-secondary' }} pricing-tier__cta">
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
                        <th class="{{ $i === 1 ? 'is-featured' : '' }}">{{ $tier }}</th>
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
                        <td class="{{ $vi === 1 ? 'is-featured' : '' }}">
                            @if ($val === true)
                                <span class="pricing-compare__yes"><x-icon.circle-check-big class="w-4 h-4" /></span>
                            @elseif ($val === false)
                                <span class="pricing-compare__no">—</span>
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
                <div class="pcm-tabs" role="tablist">
                    @foreach ($compareTiers as $i => $tier)
                    <button class="pcm-tab"
                            :class="{ 'is-active': active === {{ $i }} }"
                            @click="active = {{ $i }}"
                            role="tab"
                            :aria-selected="active === {{ $i }}">
                        {{ $tier }}
                    </button>
                    @endforeach
                </div>
                <div class="pcm-price" x-text="prices[active]"></div>
            </div>

            {{-- Feature rows --}}
            @foreach ($compareGroups as $group)
            <div class="pcm-group">{{ $group['label'] }}</div>
            @foreach ($group['rows'] as $row)
            <div class="pcm-row">
                <span class="pcm-feature">{{ $row['label'] }}</span>
                <span class="pcm-value-wrap">
                    @foreach ($row['values'] as $vi => $val)
                    <span x-show="active === {{ $vi }}">
                        @if ($val === true)
                            <span class="pricing-compare__yes"><x-icon.circle-check-big class="w-4 h-4" /></span>
                        @elseif ($val === false)
                            <span class="pricing-compare__no">—</span>
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
