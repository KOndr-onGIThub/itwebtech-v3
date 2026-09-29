@props([
    'projects',
    'locale'  => null,
    'sectors' => [],
    'counts'  => [],
])

{{-- ============================================================
     OND-471 · varianta 1 — VĚTA. Filtr je věta podle záměru
     návštěvníka: „Potřebuju [web] pro [výrobu a logistiku].“
     Dvě nativní `<select>` uvnitř věty: klávesnice, čtečka i výběr
     na mobilu fungují samy od sebe. Viditelné slovo je zrcadlo
     vybrané volby; select leží průhledně přes něj.

     Nic se neschovává. Shody se přesunou nahoru (FLIP, jednorázově
     při změně, žádný výpočet na snímek), zbytek zůstane pod
     předělem „Další projekty“ ztlumený a klikací. Pořadí se mění
     v DOM, ne přes CSS `order` — pořadí tabulátoru sedí s očima.

     Bez JS je věta skrytá (`hidden`) a mřížka je celá v původním
     pořadí. Stav se píše do URL (`?co=&pro=`), takže návrat
     z detailu i sdílený odkaz ukáže stejný výběr.
     ============================================================ --}}
@php
    $locale ??= app()->getLocale();
    $whatKeys = array_values(array_filter(
        ['all', 'website', 'application', 'other'],
        fn ($k) => $k === 'all' || ($counts[$k] ?? 0) > 0
    ));
    $forKeys = array_merge(['all'], array_keys($sectors));
    $total = $projects->count();
    $i18n = [
        'count' => __('projects.catalog.count'),
        'match' => __('projects.catalog.sentence.match'),
        'none'  => __('projects.catalog.sentence.none'),
    ];
@endphp

<form class="pd-intent" data-intent hidden
      aria-label="{{ __('projects.catalog.sentence.aria') }}"
      data-total="{{ $total }}"
      data-i18n="{{ json_encode($i18n, JSON_UNESCAPED_UNICODE) }}">
    <p class="pd-intent__sentence">
        <span>{{ __('projects.catalog.sentence.lead') }}</span>
        <span class="pd-intent__slot">
            <label class="sr-only" for="intent-co">{{ __('projects.catalog.sentence.what_label') }}</label>
            <select id="intent-co" name="co" class="pd-intent__select" aria-controls="portfolio-grid">
                @foreach ($whatKeys as $key)
                    <option value="{{ $key }}">{{ __('projects.catalog.sentence.what.' . $key) }}</option>
                @endforeach
            </select>
            <span class="pd-intent__value" aria-hidden="true">{{ __('projects.catalog.sentence.what.all') }}</span>
        </span>
        <span>{{ __('projects.catalog.sentence.joiner') }}</span>
        <span class="pd-intent__slot">
            <label class="sr-only" for="intent-pro">{{ __('projects.catalog.sentence.for_label') }}</label>
            <select id="intent-pro" name="pro" class="pd-intent__select" aria-controls="portfolio-grid">
                @foreach ($forKeys as $key)
                    <option value="{{ $key }}">{{ __('projects.catalog.sentence.for.' . $key) }}</option>
                @endforeach
            </select>
            <span class="pd-intent__value" aria-hidden="true">{{ __('projects.catalog.sentence.for.all') }}</span>
        </span><span class="pd-intent__dot">.</span>
    </p>
    <p class="pd-intent__status">
        <span role="status" data-intent-status>{{ str_replace(':n', $total, $i18n['count']['other']) }}</span>
        <a href="{{ lroute('contact') }}" class="pd-case__live" data-intent-cta hidden>{{ __('projects.catalog.cta') }} &rarr;</a>
    </p>
</form>

<div id="portfolio-grid" class="pd-works pd-works--intent" data-intent-grid>
    @foreach ($projects as $project)
        <x-portfolio.work
            :project="$project"
            :locale="$locale"
            :eager="$loop->index < 3"
            data-sectors="{{ implode(' ', array_keys(array_filter($sectors, fn ($slugs) => in_array($project->slug, $slugs, true)))) }}"
        />
    @endforeach
    <p class="pd-works__rest" data-intent-rest hidden>{{ __('projects.catalog.sentence.rest') }}</p>
</div>
