@props([
    'categories' => ['all', 'website', 'application', 'other'],
    'counts'     => [],
    'target'     => 'portfolio-grid',
])

{{-- OND-399 — filtr ve slovníku ACID: textová tlačítka, ne pilulky.
     Aktivní kategorie = bílý text + acidové podtržení (stav, ne ozdoba).
     `role="group"` + `aria-pressed`: dřív `role="tab"` bez tabpanelu,
     což čtečka ohlásila jako záložky, které nikam nevedou.
     Kategorie s nulou se nevykreslí — tlačítko do prázdné mřížky je slepá
     ulička. Počet „21 projektů zobrazeno" zůstává jen pro čtečku: vidící
     ho má v aktivním tlačítku. --}}
@php
    $labelMap = [
        'all'         => 'projects.filter_all',
        'website'     => 'projects.filter_websites',
        'application' => 'projects.filter_webapps',
        'other'       => 'projects.filter_other',
    ];
@endphp

<div
    class="pd-filter"
    role="group"
    aria-label="{{ __('projects.filter_aria') }}"
    x-data="portfolioFilter({{ Js::from(['target' => $target, 'categories' => $categories, 'counts' => $counts]) }})"
>
    @foreach ($categories as $category)
        @php
            $count = $counts[$category] ?? 0;
            $label = isset($labelMap[$category]) ? __($labelMap[$category]) : ucfirst($category);
        @endphp
        @continue($category !== 'all' && $count === 0)
        <button
            type="button"
            class="pd-filter__btn"
            aria-pressed="{{ $category === 'all' ? 'true' : 'false' }}"
            :aria-pressed="active === '{{ $category }}' ? 'true' : 'false'"
            aria-controls="{{ $target }}"
            @click="setActive('{{ $category }}')"
            data-category="{{ $category }}"
        >
            <span class="pd-filter__label">{{ $label }}</span>
            <span class="pd-filter__count">{{ $count }}</span>
        </button>
    @endforeach

    <p class="sr-only" aria-live="polite"><span x-text="visibleCount"></span> {{ __('projects.count_label') }}</p>
</div>
