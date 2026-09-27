@props(['project'])

{{-- OND-402 — údaje o projektu jako `.pd-facts` (z /kontakt; základ OND-379
     ji sem předurčil). Bez rámečku, bez pilulek: popisek nad hodnotou.
     Kategorie a rok jsou v nadřádku hlavy a živý web v její akci, tady by
     byly podruhé. Technologie jsou řádek textu, ne pilulky: sedm rámečků
     o ničem neinformuje víc než čárka. --}}
@php
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    $locale = app()->getLocale();
    // OND-267: `duration` je jedna hodnota pro všechny jazyky, překlad jde
    // přes lang slovník (fallback na syrovou hodnotu je v modelu).
    $durationLabel = $project->durationLabel($locale);
    $tags = ($project->tags ?? collect())
        ->map(fn ($tag) => $tag->translation($locale)?->name ?? $tag->slug)
        ->filter();
@endphp

<aside class="pd-story__facts">
    <dl class="pd-facts">
        @if ($project->client_name)
            <div>
                <dt>{{ __('projects.detail.meta.client') }}</dt>
                <dd>{{ $project->client_name }}</dd>
            </div>
        @endif
        @if ($durationLabel)
            <div>
                <dt>{{ __('projects.detail.meta.duration') }}</dt>
                <dd>{{ $durationLabel }}</dd>
            </div>
        @endif
        @if ($tags->isNotEmpty())
            <div>
                <dt>{{ __('projects.detail.meta.tags') }}</dt>
                <dd>{{ $tags->implode(', ') }}</dd>
            </div>
        @endif
    </dl>
</aside>
