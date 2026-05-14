@extends('layouts.app')

@section('title', __('projects.meta.title'))
@section('description', __('projects.meta.description'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro /projekty. --}}
@push('jsonld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),     'item' => lroute('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.projects'), 'item' => lroute('projects')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- Page hero — OND-135 iter 6: plán §3.1 design DNA (page-mark + display + amber accent) --}}
<div class="page-hero page-hero--projects">
    <div class="container-site">
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt (itwebtech nemá „pages" hierarchii) —
             aplikováno per CEO PR #78/#80/#82 precedent (home/kontakt/cenik). --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('projects.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('projects.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('projects.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('projects.hero.subline') }}</p>
    </div>
</div>

{{-- 1. Portfolio filter --}}
<x-portfolio.filter
    :categories="['all', 'website', 'application', 'other']"
    :counts="$counts"
    target="portfolio-grid"
/>

{{-- 2. Portfolio grid --}}
<section class="section-wrapper section-wrapper--tight" data-reveal>
    <div class="container-site">
        <x-portfolio.grid :projects="$portfolioProjects" :locale="$locale" />
    </div>
</section>

{{-- 3. Conversion snapshots (existující) --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.snapshots.subheading') }}</p>
            <h2>{{ __('projects.snapshots.heading') }}</h2>
            <p class="section-header__desc">{{ __('projects.snapshots.desc') }}</p>
        </header>

        <div class="project-snapshots" data-reveal-group>
            @foreach (__('projects.snapshots.items') as $snapshot)
            <article class="project-snapshot">
                <div class="project-snapshot__meta">
                    <span>{{ $snapshot['type'] }}</span>
                    <span>{{ $snapshot['timeline'] }}</span>
                </div>
                <h3>{{ $snapshot['title'] }}</h3>
                <p>{{ $snapshot['summary'] }}</p>
                <ul>
                    @foreach ($snapshot['outcomes'] as $outcome)
                    <li>
                        <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                        <span>{{ $outcome }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- 4. Project fit (existující) --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.fit.subheading') }}</p>
            <h2>{{ __('projects.fit.heading') }}</h2>
        </header>

        <div class="project-fit" data-reveal-group>
            <ul class="project-fit__list">
                @foreach (__('projects.fit.items') as $item)
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>{{ $item }}</span>
                </li>
                @endforeach
            </ul>

            <aside class="project-fit__cta">
                <h3>{{ __('projects.fit.cta_heading') }}</h3>
                <p>{{ __('projects.fit.cta_text') }}</p>
                <div class="project-fit__actions">
                    <a href="{{ lroute('contact') }}" class="btn btn-primary">
                        {{ __('projects.fit.cta_primary') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </a>
                    <a href="{{ lroute('price') }}" class="btn btn-secondary">
                        {{ __('projects.fit.cta_secondary') }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- 5. Why me (existující) --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.why_me.subheading') }}</p>
            <h2>{{ __('projects.why_me.heading') }}</h2>
        </header>

        <div class="why-grid" data-reveal-group>
            @foreach (__('projects.why_me.items') as $item)
            <div class="why-card">
                <h3>{{ $item['title'] }}</h3>
                <p>{!! $item['description'] !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 6. Final CTA --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <div class="cta-block">
            <h2>{{ __('projects.cta.heading') }}</h2>
            <div class="cta-block__actions">
                <a href="{{ lroute('contact') }}" class="btn btn-primary">
                    {{ __('projects.cta.primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
