@props([
    'project',
    'locale' => null,
    'eager'  => false,
])

@php
    $locale ??= app()->getLocale();
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    $t = $project->translation($locale);

    // Tagline: subtitle → fallback summary (zkrácený)
    $tagline = $t?->subtitle ?: ($t?->summary ? \Illuminate\Support\Str::limit($t->summary, 110) : null);

    // Thumbnail (OND-202): wide 3-device mockup je v malé kartě nečitelný —
    // preferuj explicitní thumbnail, pak čtvercový detailní záběr, pak hero.
    $screens = $project->screenshots ?? collect();
    $hero    = portfolio_card_thumbnail($screens);

    $categoryLabel = __('projects.detail.category_label.' . $project->category);
    if (str_starts_with($categoryLabel, 'projects.detail.category_label.')) {
        $categoryLabel = ucfirst($project->category);
    }

    // OND-209: slug může být lokalizovaný (DE/EN), detailUrl to řeší.
    $detailHref = $project->detailUrl($locale);
    $ariaLabel = ($t?->title ?? $project->client_name ?? $project->slug) . ' — ' . __('projects.view_project');
@endphp

<article
    class="portfolio-card"
    data-category="{{ $project->category }}"
    data-filter-hidden="false"
    data-reveal
>
    <a href="{{ $detailHref }}" class="portfolio-card__link" aria-label="{{ $ariaLabel }}">
        <div class="portfolio-card__thumbnail">
            @if ($hero)
                <x-portfolio.screenshot
                    :path="$hero->path"
                    alt=""
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    loading="{{ $eager ? 'eager' : 'lazy' }}"
                    fetchpriority="{{ $eager ? 'high' : null }}"
                />
            @else
                <div class="portfolio-card__thumbnail-placeholder" aria-hidden="true"></div>
            @endif
        </div>

        <div class="portfolio-card__body">
            <p class="portfolio-card__meta">
                <span class="portfolio-card__category">{{ $categoryLabel }}</span>
                @if ($project->year)
                    <span aria-hidden="true">·</span>
                    <span class="portfolio-card__year">{{ $project->year }}</span>
                @endif
                @if ($project->client_name)
                    <span aria-hidden="true">·</span>
                    <span class="portfolio-card__client">{{ $project->client_name }}</span>
                @endif
            </p>

            <h3 class="portfolio-card__title">{{ $t?->title ?? $project->slug }}</h3>

            @if ($tagline)
                <p class="portfolio-card__tagline">{{ $tagline }}</p>
            @endif

            <span class="portfolio-card__cta">
                {{ __('projects.view_project') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </span>
        </div>
    </a>
</article>
