@props(['translation'])

@php
    /** @var \App\Models\Portfolio\PortfolioProjectTranslation|null $translation */
    $hasAny = $translation && (
        filled($translation->description) ||
        filled($translation->challenge) ||
        filled($translation->solution) ||
        filled($translation->result)
    );
@endphp

<div class="portfolio-detail-body">
    @if ($translation?->description)
        <div class="portfolio-detail-body__intro">
            {!! nl2br(e($translation->description)) !!}
        </div>
    @endif

    @if ($translation?->challenge)
        <section class="portfolio-detail-body__section">
            <h2>{{ __('projects.detail.challenge') }}</h2>
            <div>{!! nl2br(e($translation->challenge)) !!}</div>
        </section>
    @endif

    @if ($translation?->solution)
        <section class="portfolio-detail-body__section">
            <h2>{{ __('projects.detail.solution') }}</h2>
            <div>{!! nl2br(e($translation->solution)) !!}</div>
        </section>
    @endif

    @if ($translation?->result)
        <section class="portfolio-detail-body__section">
            <h2>{{ __('projects.detail.result') }}</h2>
            <div>{!! nl2br(e($translation->result)) !!}</div>
        </section>
    @endif

    @if (! $hasAny)
        <p class="portfolio-detail-body__empty">{{ __('projects.detail.no_content') }}</p>
    @endif
</div>
