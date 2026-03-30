@props([
    'name',
    'rating' => 5,
    'text',
    'date'   => null,
    'source' => 'Google',
])

@php
    $initials = collect(explode(' ', $name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
@endphp

<div class="review-card">
    <div class="review-card__stars" aria-label="{{ $rating }} z 5 hvězdiček">
        @for ($i = 1; $i <= 5; $i++)
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $i <= $rating ? 'text-gold' : 'text-gray-200' }}" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        @endfor
    </div>

    <p class="review-card__text">&ldquo;{{ $text }}&rdquo;</p>

    <div class="review-card__author">
        <div class="review-card__avatar" aria-hidden="true">{{ $initials }}</div>
        <div>
            <div class="review-card__name">{{ $name }}</div>
            @if ($date)
                <div class="review-card__date">{{ $date }} &middot; {{ $source }}</div>
            @else
                <div class="review-card__date">{{ $source }}</div>
            @endif
        </div>
    </div>
</div>
