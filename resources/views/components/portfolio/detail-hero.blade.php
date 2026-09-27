@props([
    'project',
    'translation',
])

{{-- OND-402 — hlava detailu = `.pd-page-head` (základ OND-379 §2a).
     Nadřádek nese kategorii a rok (dřív žlutý řádek na střed i se jménem
     klienta, který se lámal na tři řádky — klient je v údajích u textu).
     Jediná akce v hlavě je živý web, tichým odkazem s acid podtržením
     (sazba `.pd-case__cta` z homepage). „Napsat poptávku" v hlavě odpadá:
     je v liště nahoře a jako jediné tlačítko na konci stránky.
     `data-analytics-view` (OND-137 P4 §6) visí přímo na sekci — obalový
     `<div>` by ji odtrhl od obalu `.pd` a vrstva A by ji nenašla. --}}
@php
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    /** @var \App\Models\Portfolio\PortfolioProjectTranslation $translation */
    $categoryLabel = __('projects.detail.category_label.' . $project->category);
    if (str_starts_with($categoryLabel, 'projects.detail.category_label.')) {
        $categoryLabel = ucfirst($project->category);
    }
    $sub = $translation?->subtitle ?: $translation?->summary;
@endphp

<section class="pd-section pd-page-head" data-pdd="project-head"
         data-analytics-view="case_study_view"
         data-analytics-props='{"slug":"{{ $project->slug }}"}'>
    <div class="container-site">
        <p class="pd-eyebrow">{{ $categoryLabel }}@if ($project->year) <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ $project->year }}@endif</p>
        <h1 class="pd-heading pd-heading--sub">{{ $translation?->title ?? $project->slug }}</h1>
        @if ($sub)
            <p class="pd-sub">{{ $sub }}</p>
        @endif
        @if ($project->live_url)
            <p class="pd-page-head__actions">
                <a href="{{ $project->live_url }}" class="pd-case__cta" target="_blank" rel="noopener"
                   data-analytics="case_study_live_click"
                   data-analytics-props='{"slug":"{{ $project->slug }}"}'>{{ __('projects.detail.visit_live') }} ↗</a>
            </p>
        @endif
    </div>
</section>
