@props([
    'number',
    'icon',
    'title',
    'desc',
    'timeframe' => null,
    'last'      => false,
])

<div class="step-item" data-reveal>
    <div class="flex flex-col items-center">
        <div class="step-item__badge">{{ $number }}</div>
        @unless ($last)
            <div class="w-0.5 flex-1 bg-gold/20 mt-2 min-h-[40px]"></div>
        @endunless
    </div>
    <div class="step-item__body">
        <div class="step-item__meta">
            <x-dynamic-component :component="'icon.' . $icon" class="w-4 h-4 text-sage" />
            @if ($timeframe)
                <span class="step-item__timeframe">{{ $timeframe }}</span>
            @endif
        </div>
        <h3 class="step-item__title">{{ $title }}</h3>
        <p class="step-item__desc">{{ $desc }}</p>
    </div>
</div>
