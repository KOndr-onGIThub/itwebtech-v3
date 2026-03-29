@props([
    'icon',
    'title',
    'desc',
])

<div class="benefit-card">
    <div class="benefit-card__icon">
        <x-dynamic-component :component="'icon.' . $icon" class="w-6 h-6" />
    </div>
    <div>
        <h3 class="benefit-card__title">{{ $title }}</h3>
        <p class="benefit-card__desc">{{ $desc }}</p>
    </div>
</div>
