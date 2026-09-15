@props([
    'categories' => ['all', 'website', 'application', 'other'],
    'counts'     => [],
    'target'     => 'portfolio-grid',
])

@php
    // Mapování kategorií na existující i18n klíče (filter_*).
    $labelMap = [
        'all'         => 'projects.filter_all',
        'website'     => 'projects.filter_websites',
        'application' => 'projects.filter_webapps',
        'other'       => 'projects.filter_other',
    ];
@endphp

<section
    class="portfolio-filter"
    x-data="portfolioFilter({{ Js::from(['target' => $target, 'categories' => $categories, 'counts' => $counts]) }})"
    aria-label="{{ __('projects.filter_aria') }}"
    data-reveal
>
    <div class="container-site portfolio-filter__inner">
        <div role="tablist" aria-label="{{ __('projects.filter_aria') }}" class="portfolio-filter__tabs">
            @foreach ($categories as $category)
                @php
                    $label = $labelMap[$category] ?? null;
                    $count = $counts[$category] ?? 0;
                @endphp
                <button
                    type="button"
                    role="tab"
                    class="portfolio-filter__tab"
                    :class="active === '{{ $category }}' ? 'is-active' : ''"
                    :aria-pressed="active === '{{ $category }}' ? 'true' : 'false'"
                    aria-controls="{{ $target }}"
                    @click="setActive('{{ $category }}')"
                    data-category="{{ $category }}"
                >
                    <span>{{ $label ? __($label) : ucfirst($category) }}</span>
                    <span class="portfolio-filter__tab-count" aria-hidden="true">{{ $count }}</span>
                </button>
            @endforeach
        </div>

        <p class="portfolio-filter__count" aria-live="polite">
            <span x-text="visibleCount"></span>
            <span class="portfolio-filter__count-label">{{ __('projects.count_label') }}</span>
        </p>
    </div>
</section>
