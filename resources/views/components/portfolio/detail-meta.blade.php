@props(['project'])

@php
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    $locale = app()->getLocale();

    $categoryLabel = __('projects.detail.category_label.' . $project->category);
    if (str_starts_with($categoryLabel, 'projects.detail.category_label.')) {
        $categoryLabel = ucfirst($project->category);
    }

    $liveHost = null;
    if ($project->live_url) {
        $liveHost = parse_url($project->live_url, PHP_URL_HOST) ?? $project->live_url;
        $liveHost = preg_replace('#^www\.#', '', $liveHost);
    }
@endphp

<aside class="portfolio-detail-meta">
    <dl class="portfolio-detail-meta__list">
        @if ($project->client_name)
            <div class="portfolio-detail-meta__row">
                <dt>{{ __('projects.detail.meta.client') }}</dt>
                <dd>{{ $project->client_name }}</dd>
            </div>
        @endif

        @if ($project->year)
            <div class="portfolio-detail-meta__row">
                <dt>{{ __('projects.detail.meta.year') }}</dt>
                <dd>{{ $project->year }}</dd>
            </div>
        @endif

        @if ($project->duration)
            <div class="portfolio-detail-meta__row">
                <dt>{{ __('projects.detail.meta.duration') }}</dt>
                <dd>{{ $project->duration }}</dd>
            </div>
        @endif

        <div class="portfolio-detail-meta__row">
            <dt>{{ __('projects.detail.meta.category') }}</dt>
            <dd>{{ $categoryLabel }}</dd>
        </div>

        @if ($project->live_url)
            <div class="portfolio-detail-meta__row">
                <dt>{{ __('projects.detail.meta.live_url') }}</dt>
                <dd>
                    <a href="{{ $project->live_url }}" target="_blank" rel="noopener">
                        {{ $liveHost }}
                        <x-icon.arrow-right class="w-3 h-3 shrink-0 -rotate-45 inline-block align-middle" />
                    </a>
                </dd>
            </div>
        @endif

        @if ($project->tags && $project->tags->count())
            <div class="portfolio-detail-meta__row portfolio-detail-meta__row--tags">
                <dt>{{ __('projects.detail.meta.tags') }}</dt>
                <dd>
                    <ul class="portfolio-detail-meta__tags">
                        @foreach ($project->tags as $tag)
                            @php $tagName = $tag->translation($locale)?->name ?? $tag->slug; @endphp
                            <li>{{ $tagName }}</li>
                        @endforeach
                    </ul>
                </dd>
            </div>
        @endif
    </dl>
</aside>
