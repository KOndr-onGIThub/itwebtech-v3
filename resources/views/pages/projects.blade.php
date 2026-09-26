@extends('layouts.app')

@section('title', __('projects.meta.title'))
@section('description', __('projects.meta.description'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro /projekty.
     Pozn.: viz price.blade.php — schema-context klíč řešíme přes PHP blok,
     aby ho nesežrala Blade direktiva (Laravel 12 CompilesContexts). --}}
@push('jsonld')
@php
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),     'item' => lroute('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.projects'), 'item' => lroute('projects')],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@endpush

@section('content')

{{-- OND-251 — vrstva hloubky ZAPNUTÁ: jen světlo a hmota, žádný pohyb.
     Důkazová stránka. Náboj na každé dlaždici mřížky by byl přesně ta
     „přeplácanost", kterou vrstva odstraňovala — karty místo toho dostávají
     kontaktní stín, aby ležely na ploše. Rozbor v §E hloubka.css. --}}
<div class="pd--depth pd--depth-sub">

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

{{-- 1. + 2. Portfolio filter a mřížka
     OND-351: DB `portfolio_projects` je dnes prázdná, takže první obrazovka
     stránky hlásila „Vše 0 · Stránky 0 …", „0 projektů zobrazeno" a prázdný
     stav „Momentálně nejsou k dispozici žádné projekty." — a to na stránce,
     která má odvedenou práci dokazovat. Když projekty nejsou, přeskočíme
     filtr i mřížku a stránka začne rovnou případovkami. Až se DB naplní,
     obojí se vrátí samo — žádný flag, jen podmínka. --}}
@if ($portfolioProjects->isNotEmpty())
<x-portfolio.filter
    :categories="['all', 'website', 'application', 'other']"
    :counts="$counts"
    target="portfolio-grid"
/>

<section class="section-wrapper section-wrapper--tight" data-reveal data-pdd="projects-grid">
    <div class="container-site">
        <x-portfolio.grid :projects="$portfolioProjects" :locale="$locale" />
    </div>
</section>
@endif

{{-- 3. Případovky
     OND-201 (nález 5.2): dřív anonymní „snapshots" s vymyšlenými termíny.
     Nově skutečné případovky z dokumentu `pripadovky` (OND-186). Druhý
     meta slot nese odkaz na živý web místo vymyšlené doby realizace —
     u interních aplikací (Toyota TSM) je `url` null a odkaz se nevykreslí. --}}
<section class="section-wrapper" data-reveal data-pdd="projects-cases">
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
                    @if (!empty($snapshot['url']))
                    <a href="{{ $snapshot['url'] }}" target="_blank" rel="noopener"
                       data-analytics="case_study_live_click"
                       data-analytics-props='{"domain":"{{ $snapshot['domain'] }}"}'>
                        {{ $snapshot['domain'] }}
                    </a>
                    @endif
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
<section class="section-wrapper section-alt" data-reveal data-pdd="projects-fit">
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
<section class="section-wrapper" data-reveal data-pdd="projects-why">
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
<section class="section-wrapper" data-reveal data-pdd="projects-cta">
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

</div>
@endsection
