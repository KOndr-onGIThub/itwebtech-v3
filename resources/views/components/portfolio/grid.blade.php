@props([
    'projects',
    'locale' => null,
    'id'     => 'portfolio-grid',
])

@php
    $locale ??= app()->getLocale();
@endphp

@if ($projects->isEmpty())
    <div class="portfolio-grid__empty">
        @if (isset($empty))
            {{ $empty }}
        @else
            <p>{{ __('projects.empty') }}</p>
            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('projects.cta.primary') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        @endif
    </div>
@else
    <div id="{{ $id }}" class="portfolio-grid" data-reveal-group>
        @foreach ($projects as $portfolioProject)
            <x-portfolio.card
                :project="$portfolioProject"
                :locale="$locale"
                :eager="$loop->index < 3"
            />
        @endforeach
    </div>
@endif
