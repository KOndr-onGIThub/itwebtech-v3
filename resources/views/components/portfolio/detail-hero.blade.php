@props([
    'project',
    'translation',
])

@php
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    /** @var \App\Models\Portfolio\PortfolioProjectTranslation $translation */

    $categoryLabel = __('projects.detail.category_label.' . $project->category);
    if (str_starts_with($categoryLabel, 'projects.detail.category_label.')) {
        $categoryLabel = ucfirst($project->category);
    }
@endphp

<section class="page-hero page-hero--portfolio-detail" data-reveal>
    <div class="container-site">
        <a href="{{ lroute('projects') }}" class="back-link">
            <x-icon.arrow-right class="w-4 h-4 shrink-0 rotate-180" />
            {{ __('projects.back_to_projects') }}
        </a>

        <p class="portfolio-detail-hero__meta">
            <span>{{ $categoryLabel }}</span>
            @if ($project->year)
                <span aria-hidden="true">·</span>
                <span>{{ $project->year }}</span>
            @endif
            @if ($project->client_name)
                <span aria-hidden="true">·</span>
                <span>{{ $project->client_name }}</span>
            @endif
        </p>

        <h1>{{ $translation?->title ?? $project->slug }}</h1>

        @if ($translation?->subtitle)
            <p class="page-hero__desc">{{ $translation->subtitle }}</p>
        @elseif ($translation?->summary)
            <p class="page-hero__desc">{{ $translation->summary }}</p>
        @endif

        <div class="portfolio-detail-hero__actions">
            @if ($project->live_url)
                <a
                    href="{{ $project->live_url }}"
                    target="_blank"
                    rel="noopener"
                    class="btn btn-primary"
                >
                    {{ __('projects.detail.visit_live') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                    {{ __('projects.cta.primary') }}
                </a>
            @else
                <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                    {{ __('projects.cta.primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
            @endif
        </div>
    </div>
</section>
