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

{{-- OND-488 — písma nad ohybem přednačíst, jinak stránka při načtení poskočí.
     Bez preloadu se písma začnou stahovat až po rozparsování CSS a první
     layout proběhne s náhradním písmem: podtitulek hlavy (Inter) má pak
     3 řádky místo 4 a s příchodem Interu odsune větu i mřížku o 29 px
     (CLS 0,018). Když IBM Plex latin-ext dorazí dřív než latin, věta
     (IBM Plex) se na chvíli zalomí na 3 řádky místo 2 (CLS 0,11).
     Všechny čtyři soubory stránka stahuje v cs, en i de i bez preloadu,
     nic navíc se tedy nestahuje, jen dřív. --}}
@push('preloads')
    @foreach ([
        'ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2',
        'ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2',
        'inter/files/inter-latin-wght-normal.woff2',
        'inter/files/inter-latin-ext-wght-normal.woff2',
    ] as $font)
    <link rel="preload" as="font" type="font/woff2" crossorigin href="{{ Vite::asset('node_modules/@fontsource-variable/' . $font) }}">
    @endforeach
@endpush

@section('content')
{{-- ============================================================
     OND-399 — /projekty ve slovníku nové homepage (4. z 9).
     Obsah je z lang/*/projects.php beze změny, mění se slovník
     a pořadí. Obal `pd` nese tokeny ACID, `pd--depth-sub` vypíná
     vrstvu B. Kužely vrstvy A v hloubka.css §E jsou psané na
     `section[data-pdd="projects-*"]` — sekce musí zůstat PŘÍMÝMI
     dětmi obalu.

     POŘADÍ JE ARGUMENT: co jsem postavil → všechno, co jsem
     postavil → jak to dělám → má to smysl pro vás? Katalog je
     protagonista, všechno ostatní je pod ním.
     ============================================================ --}}
<div class="pd pd--depth pd--depth-sub">

{{-- Hlava — `.pd-page-head` (základ OND-379 §2a). Dva řádky nad titulkem
     jsou jeden `.pd-eyebrow` s vlasovou čárkou, stejně jako /kontakt a /cenik. --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('projects.hero.page_mark_label') }} <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ __('projects.hero.upline') }}</p>
        <h1 class="pd-heading pd-heading--sub pd-heading--plain">{!! __('projects.hero.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('projects.hero.subline') }}</p>
    </div>
</section>

@if ($portfolioProjects->isNotEmpty())
{{-- Katalog — věta nad mřížkou (OND-470) a mřížka `.pd-works` (sdílená
     komponenta, použije ji i „Další projekty" na detailu). Sekce navazuje
     na hlavu bez horního odsazení: titulek hlavy a katalog jsou jedna
     věta. Všechny publikované projekty jsou v HTML jako odkazy;
     věta je jen přeskládá. --}}
<section class="pd-section" data-pdd="projects-grid">
    <div class="container-site">
        <x-portfolio.filter
            :sectors="$sectors"
            :counts="$counts"
            target="portfolio-grid"
        />

        <div id="portfolio-grid" class="pd-works" data-intent-grid>
            @foreach ($portfolioProjects as $portfolioProject)
                <x-portfolio.work
                    :project="$portfolioProject"
                    :locale="$locale"
                    :eager="$loop->index < 3"
                    data-sectors="{{ implode(' ', array_keys(array_filter($sectors, fn ($slugs) => in_array($portfolioProject->slug, $slugs, true)))) }}"
                />
            @endforeach
            <p class="pd-works__rest" data-intent-rest hidden>{{ __('projects.catalog.sentence.rest') }}</p>
        </div>
    </div>
</section>
@else
{{-- Prázdná DB (OND-351): případovky z lang jsou jediný důkaz na stránce.
     S plnou DB se nevykreslí — tři ze čtyř jsou v mřížce jako celý detail
     a čtvrtou (Toyota) zastupují čtyři aplikace z Toyoty Kolín. --}}
<section class="pd-section" data-pdd="projects-cases">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('projects.snapshots.heading') }}</h2>
        </header>
        <p class="pd-intro">{{ __('projects.snapshots.desc') }}</p>

        <div class="pd-points pd-points--cases">
            @foreach (__('projects.snapshots.items') as $snapshot)
            <article class="pd-point">
                <p class="pd-work__meta">{{ $snapshot['type'] }}</p>
                <h3 class="pd-point__title">{{ $snapshot['title'] }}</h3>
                <p class="pd-point__text">{{ $snapshot['summary'] }}</p>
                <ul class="pd-service__bullets">
                    @foreach ($snapshot['outcomes'] as $outcome)
                    <li>{{ $outcome }}</li>
                    @endforeach
                </ul>
                @if (!empty($snapshot['url']))
                <p class="pd-point__link">
                    <a href="{{ $snapshot['url'] }}" class="pd-case__live" target="_blank" rel="noopener"
                       data-analytics="case_study_live_click"
                       data-analytics-props='{"domain":"{{ $snapshot['domain'] }}"}'>{{ $snapshot['domain'] }} &nearr;</a>
                </p>
                @endif
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Proč já — `.pd-points` z /cenik (OND-391 §6 ji sem předurčil). Nadpis
     sekce je `why_me.subheading` („Takhle to dělám já"): `why_me.heading`
     („Do projektů vkládám následující") je uvozovací věta s dvojtečkou,
     která nic netvrdí. Obě jsou v lang, nic se nepřepisuje. --}}
<section class="pd-section" data-pdd="projects-why">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('projects.why_me.subheading') }}</h2>
            </header>

            <div class="pd-points">
                @foreach (__('projects.why_me.items') as $item)
                <div class="pd-point">
                    <h3 class="pd-point__title">{{ $item['title'] }}</h3>
                    <p class="pd-point__text">{!! $item['description'] !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Závěr — `.pd-close` z /cenik. Dřív dvě sekce a tři tlačítka („Má to
     smysl…" s dvěma a „Chcete podobný výsledek…" s jedním); kvalifikace
     sama končí výzvou, takže je to jedna sekce a JEDINÉ acidové tlačítko.
     „Nejdřív ceník" zůstává jako tichý odkaz. `projects.cta.heading`
     tu odpadá — dál ho používá detail projektu. --}}
<section class="pd-section pd-close" data-pdd="projects-cta">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('projects.fit.heading') }}</h2>
            </header>

            <div>
                <ul class="pd-service__bullets">
                    @foreach (__('projects.fit.items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <p class="pd-lead">{{ __('projects.fit.cta_heading') }}</p>
                <p class="pd-intro">{{ __('projects.fit.cta_text') }}</p>
                <div class="pd-actions">
                    <a href="{{ lroute('contact') }}" class="pd-cta">
                        {{ __('projects.fit.cta_primary') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                    </a>
                    <a href="{{ lroute('price') }}" class="pd-case__live">{{ __('projects.fit.cta_secondary') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
@endsection
