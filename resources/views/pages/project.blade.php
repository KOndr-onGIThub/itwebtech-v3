@extends('layouts.app')

@section('title', $translation?->meta_title ?? $translation?->title ?? config('app.name'))
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

@section('content')

{{-- 1. Detail hero — OND-137 P4 §6: case_study_view event (Jack §6) na
     hero sekci přes IntersectionObserver (data-analytics-view). --}}
<div data-analytics-view="case_study_view"
     data-analytics-props='{"slug":"{{ $project->slug }}"}'>
    <x-portfolio.detail-hero :project="$project" :translation="$translation" />
</div>

{{-- 2. Detail gallery --}}
<x-portfolio.detail-gallery :screenshots="$project->screenshots" />

{{-- 3+4. Body + meta --}}
<section class="section-wrapper portfolio-detail-body-wrapper" data-reveal>
    <div class="container-site">
        <div class="portfolio-detail-body-wrapper__grid">
            <div class="portfolio-detail-body-wrapper__main">
                <x-portfolio.detail-body :translation="$translation" />
            </div>
            <div class="portfolio-detail-body-wrapper__side">
                <x-portfolio.detail-meta :project="$project" />
            </div>
        </div>
    </div>
</section>

{{-- 5. Related projects --}}
@if ($relatedProjects && $relatedProjects->count())
<section class="section-wrapper section-alt portfolio-related" data-reveal>
    <div class="container-site">
        <header class="section-header section-header--left">
            <h2>{{ __('projects.detail.related_heading') }}</h2>
        </header>
        <div class="portfolio-grid" data-reveal-group>
            @foreach ($relatedProjects as $portfolioProject)
                <x-portfolio.card :project="$portfolioProject" :locale="$locale" />
            @endforeach
        </div>
    </div>
</section>
@endif

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
                <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                    {{ __('projects.back_to_projects') }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
