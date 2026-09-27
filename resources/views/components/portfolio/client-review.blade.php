@props(['person'])

{{-- OND-402 — recenze klienta u jeho případovky (V3 z OND-396). Stejná
     anatomie jako kartička na /recenze: tvář a jméno (`<x-testimonial-by>`),
     citace, pod ní originál a cesta na všechna hodnocení. Text je beze změny
     z `testimonials.php`, uvozovky sází šablona (`home.quote_marks`).
     V en/de je citace překlad — pod odkazy proto stojí stávající poznámka
     `home.testimonials.note`, stejně jako na /recenze a na homepage.
     „Všechna hodnocení →" vede na začátek /recenze, ne na kotvu: člověk má
     nejdřív vidět číslo (26 hodnocení), pak hledat jméno. --}}
<figure class="pd-testi__item pd-story__review">
    <x-testimonial-by :person="$person" :size="56" />
    <blockquote class="pd-testi__text">{{ __('home.quote_marks.open') }}{{ $person['text'] }}{{ __('home.quote_marks.close') }}</blockquote>
    <figcaption class="pd-testi__links">
        @if (!empty($person['url']))
            <a class="pd-case__live" href="{{ $person['url'] }}" target="_blank" rel="noopener">{{ __('reviews.original.' . $person['source']) }} ↗</a>
        @endif
        <a class="pd-case__live" href="{{ lroute('reviews') }}">{{ __('reviews.all') }}</a>
    </figcaption>
    @if (filled(__('home.testimonials.note')))
        <p class="pd-story__note">{{ __('home.testimonials.note') }}</p>
    @endif
</figure>
