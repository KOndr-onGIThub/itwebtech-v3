@props([
    'projects',
    'locale'  => null,
    'sectors' => [],
    'counts'  => [],
])

{{-- ============================================================
     OND-471 · varianta 3 — VITRÍNA. Žádný filtr: 21 položek je málo
     na to, aby se musely schovávat (Occam). Seznam je rozdělený
     nadpisy podle druhu práce a vedle něj stojí vitrína — velký
     náhled, věta a až tři výsledky vybraného projektu. Návštěvník
     vidí důkaz dřív, než klikne; na detail jde, až když ví proč.

     Desktop (≥ 1024): seznam vlevo, vitrína vpravo `position: sticky`.
     Najetí nebo fokus na řádek vymění kartu ve vitríně (skrytá karta
     = `hidden`, obrázky se načtou až při zobrazení).
     Mobil a tablet: vitrína se nevykreslí vedle; tlačítko „+“ u řádku
     přesune kartu přímo pod řádek (akordeon, otevřená je jedna).
     Titulek řádku je vždy normální odkaz na detail — bez JS taky.
     ============================================================ --}}
@php
    $locale ??= app()->getLocale();
    $items = $projects->map(fn ($p) => portfolio_catalog_item($p, $locale, $sectors))->values();
    $groups = [
        'website'     => 'projects.filter_websites',
        'application' => 'projects.filter_webapps',
        'other'       => 'projects.filter_other',
    ];
@endphp

<div class="pd-show" data-show>
    <div class="pd-show__list">
        @foreach ($groups as $cat => $labelKey)
            @php $groupItems = $items->where('category', $cat); @endphp
            @continue($groupItems->isEmpty())
            <section class="pd-show__group" aria-labelledby="show-group-{{ $cat }}">
                <h2 class="pd-show__group-title" id="show-group-{{ $cat }}">
                    {{ __($labelKey) }} <span class="pd-show__group-count">{{ $groupItems->count() }}</span>
                </h2>
                <ol class="pd-show__items">
                    @foreach ($groupItems as $it)
                        <li class="pd-show__item" data-show-item="{{ $it['slug'] }}">
                            <a href="{{ $it['url'] }}" class="pd-show__link"
                               data-analytics="project_card_click"
                               data-analytics-props='{"slug":"{{ $it['slug'] }}"}'>
                                <span class="pd-show__title" style="view-transition-name: {{ project_transition_name($it['slug'], 'title') }}">{{ $it['title'] }}</span>
                                @if ($it['text'])
                                    <span class="pd-show__text">{{ $it['text'] }}</span>
                                @endif
                            </a>
                            <button type="button" class="pd-show__toggle" data-show-toggle
                                    aria-expanded="false" aria-controls="show-{{ $it['slug'] }}" hidden>
                                <span class="sr-only">{{ __('projects.catalog.show.preview', ['title' => $it['title']]) }}</span>
                                <svg viewBox="0 0 16 16" width="16" height="16" aria-hidden="true"><path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                            </button>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endforeach
    </div>

    <div class="pd-show__stage" data-show-stage>
        @foreach ($items as $i => $it)
            <article class="pd-show__card" id="show-{{ $it['slug'] }}" data-show-card="{{ $it['slug'] }}" @if ($i > 0) hidden @endif>
                <a href="{{ $it['url'] }}" class="pd-show__visual" tabindex="-1" aria-hidden="true"
                   style="view-transition-name: {{ $i === 0 ? project_transition_name($it['slug'], 'img') : 'none' }}"
                   data-vt="{{ project_transition_name($it['slug'], 'img') }}">
                    @if ($it['hero'])
                        <x-portfolio.screenshot
                            :path="$it['hero']->path"
                            :alt="$it['alt']"
                            sizes="(min-width: 1024px) 680px, 100vw"
                            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                        />
                    @endif
                </a>
                <div class="pd-show__body">
                    <p class="pd-work__meta">
                        {{ $it['categoryLabel'] }}@if ($it['year']) · {{ $it['year'] }}@endif @if ($it['brand'])· {{ $it['brand'] }}@endif
                    </p>
                    <p class="pd-show__card-title">{{ $it['title'] }}</p>
                    @if ($it['text'])
                        <p class="pd-show__card-text">{{ $it['text'] }}</p>
                    @endif
                    @if ($it['outcomes'])
                        <ul class="pd-show__outcomes">
                            @foreach ($it['outcomes'] as $outcome)
                                <li>{{ $outcome }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <p class="pd-show__actions">
                        <a href="{{ $it['url'] }}" class="pd-show__open">
                            {{ __('projects.catalog.show.open') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                        </a>
                        @if ($it['live'])
                            <a href="{{ $it['live'] }}" class="pd-case__live" target="_blank" rel="noopener">{{ __('projects.catalog.show.live') }} &nearr;</a>
                        @endif
                    </p>
                </div>
            </article>
        @endforeach
    </div>
</div>
