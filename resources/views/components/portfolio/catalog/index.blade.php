@props([
    'projects',
    'locale'  => null,
    'sectors' => [],
])

{{-- ============================================================
     OND-471 · varianta 2 — REJSTŘÍK. Katalog jako rejstřík názvů:
     21 řádků se vejde na necelé dvě obrazovky, oko je přečte jako
     obsah knihy. Náhled není v mřížce, ale pod rukou.

     Desktop (hover + jemný ukazatel): řádek je jen typografie; náhled
     `.pd-index__thumb` se při najetí vznese vedle kurzoru a jde za ním
     (jedna CSS proměnná na pohyb myši, žádná smyčka na snímek). Ostatní
     řádky ustoupí, aktivní zůstane v plném kontrastu.
     Dotyk: tentýž obrázek stojí v řádku vlevo jako malý náhled.

     Filtr = hledání. Pole prohledává titulek, klienta, větu, kategorii,
     rok i štítky (bez diakritiky). Návrhy pod polem jsou jen zkratky
     do pole, ne záložky. Počet oznamuje `role="status"`.
     Bez JS je lišta skrytá a rejstřík celý.
     ============================================================ --}}
@php
    $locale ??= app()->getLocale();
    $items = $projects->map(fn ($p) => portfolio_catalog_item($p, $locale, $sectors))->values();
    $i18n = [
        'count' => __('projects.catalog.count'),
    ];
@endphp

<div class="pd-index" data-index data-total="{{ $items->count() }}" data-i18n="{{ json_encode($i18n, JSON_UNESCAPED_UNICODE) }}">
    <div class="pd-index__bar" data-index-bar hidden>
        <div class="pd-index__field">
            <label class="sr-only" for="index-q">{{ __('projects.catalog.index.search_label') }}</label>
            <svg class="pd-index__glass" viewBox="0 0 20 20" width="20" height="20" aria-hidden="true"><circle cx="8.5" cy="8.5" r="6" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M13 13l5 5" stroke="currentColor" stroke-width="1.5"/></svg>
            <input id="index-q" class="pd-index__search" type="search" autocomplete="off" spellcheck="false"
                   enterkeyhint="search" aria-controls="portfolio-index"
                   placeholder="{{ __('projects.catalog.index.placeholder') }}">
        </div>
        <p class="pd-index__try">
            <span class="pd-index__try-label">{{ __('projects.catalog.index.try') }}</span>
            @foreach (__('projects.catalog.index.suggestions') as $suggestion)
                <button type="button" class="pd-index__chip" data-q="{{ $suggestion }}">{{ $suggestion }}</button>
            @endforeach
        </p>
        <p class="pd-index__status" role="status" data-index-status>{{ str_replace(':n', $items->count(), $i18n['count']['other']) }}</p>
    </div>

    <ol class="pd-index__list" id="portfolio-index" data-index-list>
        @foreach ($items as $i => $it)
            <li class="pd-index__row" data-search="{{ $it['search'] }}">
                <a href="{{ $it['url'] }}" class="pd-index__link"
                   data-analytics="project_card_click"
                   data-analytics-props='{"slug":"{{ $it['slug'] }}"}'>
                    <span class="pd-index__no" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="pd-index__thumb" aria-hidden="true" style="view-transition-name: {{ project_transition_name($it['slug'], 'img') }}">
                        @if ($it['hero'])
                            <x-portfolio.screenshot
                                :path="$it['hero']->path"
                                alt=""
                                sizes="(hover: hover) and (min-width: 1024px) 440px, 128px"
                                loading="{{ $i < 6 ? 'eager' : 'lazy' }}"
                            />
                        @endif
                    </span>
                    <span class="pd-index__main">
                        <span class="pd-index__title" style="view-transition-name: {{ project_transition_name($it['slug'], 'title') }}">{{ $it['title'] }}</span>
                        @if ($it['text'])
                            <span class="pd-index__text">{{ $it['text'] }}</span>
                        @endif
                    </span>
                    <span class="pd-index__meta">
                        <span>{{ $it['categoryLabel'] }}</span>
                        @if ($it['brand'])<span>{{ $it['brand'] }}</span>@endif
                    </span>
                    <span class="pd-index__year">{{ $it['year'] }}</span>
                </a>
            </li>
        @endforeach
    </ol>

    <p class="pd-index__empty" data-index-empty hidden>
        {{ __('projects.catalog.index.none') }}
        <a href="{{ lroute('contact') }}" class="pd-case__live">{{ __('projects.catalog.cta') }} &rarr;</a>
    </p>
</div>
