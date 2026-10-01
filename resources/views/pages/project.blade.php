@extends('layouts.app')

{{-- Titulek podle vzoru podstránek `<Stránka> — Ondřej Kriška, ONDRAWEB`.
     „Stránka" je tu `meta_title` z DB (ručně psaný titulek pro vyhledávač),
     fallback `title`. DB se nemění, jen se k ní přidá podpis. --}}
@section('title', ($translation?->meta_title ?: ($translation?->title ?? $project->slug)) . ' — Ondřej Kriška, ONDRAWEB')
@section('description', $translation?->meta_description ?? $translation?->summary ?? '')

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro detail projektu.
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
            ['@type' => 'ListItem', 'position' => 3, 'name' => $translation?->title ?? $project->slug, 'item' => url()->current()],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@endpush

@php
    // Recenze klienta u jeho případovky (OND-402, V3 z OND-396): první položka
    // z `testimonials.php`, jejíž `project` je slug tohohle projektu. Pořadí
    // v souboru rozhoduje: Cvrček je před Kňourkem (Cyklocentrum, stejný text),
    // Holcmann před YCF CUP (PitArena). Bez recenze se blok nevykreslí.
    $review = collect(__('testimonials.items'))
        ->first(fn ($t) => ($t['project'] ?? null) === $project->slug);
@endphp

@section('content')
{{-- ============================================================
     OND-402 — detail projektu ve slovníku nové homepage (5. z 9).
     Texty jsou z DB (`portfolio_project_translations`) beze změny,
     mění se slovník a pořadí. Obal `pd` nese tokeny ACID,
     `pd--depth-sub` vypíná vrstvu B. Kužely vrstvy A v hloubka.css §E
     jsou psané na `section[data-pdd="project-*"]` — sekce musí zůstat
     PŘÍMÝMI dětmi obalu.

     POŘADÍ JE ARGUMENT: co to je → jak to vypadá → s čím klient
     přišel, co jsem udělal, co to přineslo → co na to říká klient →
     zblízka → co dalšího → chcete totéž? Jedno acidové tlačítko na konci.
     ============================================================ --}}
<div class="pd pd--depth pd--depth-sub">

<x-portfolio.detail-hero :project="$project" :translation="$translation" />

{{-- Hlavní vizuál navazuje na hlavu bez horního odsazení: titulek a obrázek
     jsou jedna věta („tady je to"). --}}
<x-portfolio.detail-gallery :screenshots="$project->screenshots" part="lead"
    :transition-name="project_transition_name($project->slug, 'img')" />

<section class="pd-section" data-pdd="project-body">
    <div class="container-site">
        <div class="pd-story">
            <div class="pd-story__main">
                <x-portfolio.detail-body :translation="$translation" :project="$project" />

                @if ($review)
                    <x-portfolio.client-review :person="$review" />
                @endif
            </div>
            <x-portfolio.detail-meta :project="$project" />
        </div>
    </div>
</section>

<x-portfolio.detail-gallery :screenshots="$project->screenshots" part="rest" :project="$project" />

@if ($relatedProjects && $relatedProjects->count())
{{-- Další projekty — mřížka `.pd-works` z /projekty beze změny (OND-399 §5). --}}
<section class="pd-section" data-pdd="project-related">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('projects.detail.related_heading') }}</h2>
        </header>
        <div class="pd-works">
            @foreach ($relatedProjects as $portfolioProject)
                <x-portfolio.work :project="$portfolioProject" :locale="$locale" :transition="false" />
            @endforeach
        </div>
        <p class="pd-more"><a href="{{ lroute('projects') }}" class="pd-more__link">{{ __('home.portfolio.cta') }}</a></p>
    </div>
</section>
@endif

{{-- Závěr — `.pd-about-cta` z /o-mne a /recenze: věta a JEDINÉ acidové
     tlačítko stránky. Dřív tu vedle stálo „← Zpět na projekty" jako druhé
     tlačítko; cestu do katalogu nese „Všechny projekty →" o sekci výš. --}}
<section class="pd-section pd-about-cta" data-pdd="project-cta" data-sticky-cta="hide">
    <div class="container-site">
        <h2 class="pd-lead">{{ __('projects.cta.heading') }}</h2>
        <a href="{{ lroute('contact') }}" class="pd-cta">
            {{ __('projects.cta.primary') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
        </a>
    </div>
</section>

</div>
@endsection
