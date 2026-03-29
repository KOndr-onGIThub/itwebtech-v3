@extends('layouts.app')

@section('title', __('price.meta.title'))
@section('description', __('price.meta.description'))

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('price.subheading') ?? __('price.heading') }}</p>
        <h1>{{ __('price.heading') }}</h1>
        <p>{!! __('price.intro') !!}</p>
    </div>
</div>

{{-- One-off services --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.one_off.heading') }}</h2>
        </header>

        <div class="price-grid" data-reveal-group>
            @foreach (__('price.one_off.items') as $item)
            <article class="price-card">
                <header class="price-card__header">
                    <span class="price-card__label">{{ $item['short'] }}</span>
                    <h3>{{ $item['name'] }}</h3>
                    <p class="price-card__cost">{!! $item['cost'] !!}</p>
                </header>
                <ul class="price-card__features">
                    @foreach ($item['items'] as $feature)
                        @if ($feature)
                        <li>{{ $feature }}</li>
                        @endif
                    @endforeach
                </ul>
                <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                    {{ __('price.quotation') }}
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Long-term packages --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('price.longer.group_name') }}</p>
            <h2>{{ __('price.longer.heading') }}</h2>
        </header>

        <div class="price-grid" data-reveal-group>
            @foreach (__('price.longer.items') as $item)
            <article class="price-card">
                <header class="price-card__header">
                    <span class="price-card__label">{{ $item['short'] }}</span>
                    <h3>{{ $item['name'] }}</h3>
                    <div class="price-card__cost-duo">
                        <div>
                            <small>{{ __('price.monthly') }}</small>
                            <span>{!! $item['cost_month'] !!}</span>
                        </div>
                        <div>
                            <small>{{ __('price.annual') }} — {{ __('price.month_gratis') }}</small>
                            <span>{!! $item['cost_year'] !!}</span>
                        </div>
                    </div>
                </header>
                <ul class="price-card__features">
                    @foreach ($item['items'] as $feature)
                        @if ($feature)
                        <li>{{ $feature }}</li>
                        @endif
                    @endforeach
                </ul>
                <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                    {{ __('price.quotation') }}
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Website packages --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('price.package.group_name') }}</p>
            <h2>{{ __('price.package.heading') }}</h2>
            <p class="section-header__desc">{!! __('price.package.maintenance_free') !!}</p>
        </header>

        <div class="price-grid price-grid--packages" data-reveal-group>
            @foreach (__('price.package.items') as $i => $item)
            <article class="price-card {{ $i === 1 ? 'price-card--popular' : '' }}">
                @if ($i === 1)
                <span class="price-card__badge">{{ __('price.bestseller') }}</span>
                @elseif ($i === 2)
                <span class="price-card__badge">{{ __('price.popular') }}</span>
                @elseif ($i === 3)
                <span class="price-card__badge">{{ __('price.vip') }}</span>
                @endif
                <header class="price-card__header">
                    <h3>{{ $item['name'] }}</h3>
                    <p class="price-card__cost">{!! $item['cost'] !!}</p>
                </header>
                <ul class="price-card__features">
                    @foreach ($item['items'] as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                <a href="{{ lroute('contact') }}" class="btn btn-primary" style="justify-content:center;margin-top:auto;">
                    {{ __('price.quotation') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Sales info CTA --}}
<section class="section-wrapper section-cta" data-reveal>
    <div class="container-site" style="flex-direction:column;gap:1.25rem;">
        <p style="max-width:560px;text-align:center;color:rgba(241,245,249,.8);font-size:1.0625rem;line-height:1.7;">
            {{ __('price.sales_info') }}
        </p>
        <a href="{{ lroute('contact') }}" class="btn btn-primary">
            {{ __('price.cta_calculation') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
        </a>
    </div>
</section>

@endsection
