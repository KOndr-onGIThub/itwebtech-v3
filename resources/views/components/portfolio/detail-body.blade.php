@props(['translation'])

{{-- OND-402 — text případovky. Perex větším písmem (`.pd-story__intro`),
     pak Výzva / Řešení / Výsledek jako tři kapitoly: nadpis h2 v sazbě
     `.pd-step__title`, text v sazbě `.pd-step__text` (62 ch). Žádná
     zlatá svislá čárka před nadpisem — kapitolu otevírá nadpis a mezera. --}}
@php
    /** @var \App\Models\Portfolio\PortfolioProjectTranslation|null $translation */
    $chapters = array_filter([
        'challenge' => $translation?->challenge,
        'solution'  => $translation?->solution,
        'result'    => $translation?->result,
    ], 'filled');
@endphp

@if (filled($translation?->description))
    <p class="pd-story__intro">{!! nl2br(e($translation->description)) !!}</p>
@endif

@foreach ($chapters as $key => $text)
    <div class="pd-story__chapter">
        <h2 class="pd-step__title">{{ __('projects.detail.' . $key) }}</h2>
        <p class="pd-step__text">{!! nl2br(e($text)) !!}</p>
    </div>
@endforeach

@if (blank($translation?->description) && ! $chapters)
    <p class="pd-step__text">{{ __('projects.detail.no_content') }}</p>
@endif
