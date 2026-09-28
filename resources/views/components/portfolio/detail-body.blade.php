@props(['translation', 'project' => null])

{{-- OND-402 — text případovky. Perex větším písmem (`.pd-story__intro`),
     pak Výzva / Řešení / Výsledek jako tři kapitoly: nadpis h2 v sazbě
     `.pd-step__title`, text v sazbě `.pd-step__text` (62 ch). Žádná
     zlatá svislá čárka před nadpisem — kapitolu otevírá nadpis a mezera.

     OND-449: pod „Výsledkem“ řádek „Stav k … Zdroj: …“ (B-09, jen když je
     vyplněné datum i zdroj) a za ním blok „Vyzkoušejte si to naživo“ (B-05,
     jen u projektu s `live_url`; věta z `live_hint`, jinak obecná). --}}
@php
    /** @var \App\Models\Portfolio\PortfolioProjectTranslation|null $translation */
    $chapters = array_filter([
        'challenge' => $translation?->challenge,
        'solution'  => $translation?->solution,
        'result'    => $translation?->result,
    ], 'filled');

    /** @var \App\Models\Portfolio\PortfolioProject|null $project */
    $resultSource = null;
    if ($project?->result_as_of && filled($translation?->result_source)) {
        $resultSource = __('projects.detail.result_source', [
            'date'   => $project->result_as_of->locale(app()->getLocale())
                ->translatedFormat(__('projects.detail.result_date_format')),
            'source' => $translation->result_source,
        ]);
    }
@endphp

@if (filled($translation?->description))
    <p class="pd-story__intro">{!! nl2br(e($translation->description)) !!}</p>
@endif

@foreach ($chapters as $key => $text)
    <div class="pd-story__chapter">
        <h2 class="pd-step__title">{{ __('projects.detail.' . $key) }}</h2>
        <p class="pd-step__text">{!! nl2br(e($text)) !!}</p>
        @if ($key === 'result' && $resultSource)
            <p class="pd-story__source">{{ $resultSource }}</p>
        @endif
    </div>
@endforeach

@if ($project?->live_url)
    <div class="pd-story__live">
        <h2 class="pd-step__title">{{ __('projects.detail.live_heading') }}</h2>
        <p class="pd-step__text">{{ filled($translation?->live_hint) ? $translation->live_hint : __('projects.detail.live_hint_default') }}</p>
        <p class="pd-story__live-link"><a href="{{ $project->live_url }}" class="pd-case__cta" target="_blank" rel="noopener"
           data-analytics="case_study_live_click"
           data-analytics-props='{"slug":"{{ $project->slug }}"}'>{{ __('projects.detail.visit_live') }} ↗</a></p>
    </div>
@endif

@if (blank($translation?->description) && ! $chapters)
    <p class="pd-step__text">{{ __('projects.detail.no_content') }}</p>
@endif
