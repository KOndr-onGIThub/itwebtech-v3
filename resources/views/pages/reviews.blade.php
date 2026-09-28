@extends('layouts.app')

@section('title', __('reviews.meta.title'))
@section('description', __('reviews.meta.description'))

@php
    // Uvozovky jsou per-locale a sází je šablona, ne texty v `lang` —
    // stejně jako na homepage (`home.quote_marks`).
    $quoteOpen  = __('home.quote_marks.open');
    $quoteClose = __('home.quote_marks.close');
    $locale     = app()->getLocale();
@endphp

@section('content')

{{-- ============================================================
     OND-397 — /recenze (10. stránka). Předloha: OND-396.
     Stránka je cíl odkazu ve chvíli pochybnosti („je to pravda,
     komu to dělal?"), ne stránka v navigaci. Pořadí: číslo →
     odkazy na oba profily → recenze seskupené podle otázky →
     jedna výzva.

     Číslo v H1 je totéž jako v pruhu na homepage (26 hodnocení
     na Googlu a Firmy.cz). Kartiček je 22 (23 lidí) a to je
     správně: čtyři lidé hodnotili na obou platformách, Veselá
     a Podaná jsou z Facebooku. Počet kartiček se proto na stránce
     nepíše. Kdo hodnotil na obou platformách, má na kartičce oba
     odkazy na originál (OND-444, B-10).

     Žádné `AggregateRating` ani `Review` v JSON-LD: recenze, které
     firma publikuje o sobě, Google ve výsledcích nezobrazí.

     Vrstva hloubky jako na /o-mne: světlo ano, pohyb ne. Kužely
     visí na `data-pdd` (hloubka.css §E), CSS je v podpis.css §F.
     ============================================================ --}}
<div class="pd pd--depth pd--depth-sub">

<section class="pd-section pd-page-head" data-pdd="reviews-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('reviews.eyebrow') }}</p>
        <h1 class="pd-heading pd-heading--sub">{!! __('reviews.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('reviews.intro') }}</p>

        {{-- EN/DE: recenze jsou překlad českých originálů (jen en/de). --}}
        @if (filled(__('home.testimonials.note')))
        <p class="pd-testi__note">{{ __('home.testimonials.note') }}</p>
        @endif

        {{-- Nástroj na ověření čísla: tady si ho návštěvník sečte. --}}
        <p class="pd-page-head__actions">
            @foreach (config('reviews.profiles') as $key => $profile)
            <a class="pd-case__live" href="{{ $profile['url'] }}" target="_blank" rel="noopener">{{ __('reviews.profiles.' . $key, ['count' => $profile['count']]) }} ↗</a>
            @endforeach
        </p>
    </div>
</section>

{{-- Čtyři skupiny podle otázky, kterou si člověk klade (předloha §3).
     Každá recenze je na stránce jednou; `id` kartičky = `id` recenze,
     ať na ni jde později odkázat (`/recenze#rostislav-toman`). --}}
<section class="pd-section pd-reviews" data-pdd="reviews-list">
    <div class="container-site">
        @foreach ($groups as $groupKey => $cards)
        <div class="pd-reviews__group">
            <h2 class="pd-subsection__title">{{ __('reviews.groups.' . $groupKey) }}</h2>
            <div class="pd-testi">
                @foreach ($cards as $people)
                @php
                    $lead = $people[0];
                    // OND-444 (B-10): `also` = tentýž člověk i na druhé platformě.
                    // Bez textu jen přidá odkaz pod hlavní citát, s textem je to
                    // vlastní citát se svým odkazem — nad hlavním, je novější.
                    $also = collect($people)->flatMap(fn ($person) => $person['also'] ?? []);
                    $ownQuotes = $also->filter(fn ($entry) => filled($entry['text'] ?? null));
                    $leadLinks = collect($people)->concat($also->reject(fn ($entry) => filled($entry['text'] ?? null)));
                @endphp
                <article class="pd-testi__item" id="{{ $lead['id'] }}">
                    @foreach ($people as $person)
                    <x-testimonial-by :person="$person" :size="56" />
                    @endforeach
                    @foreach ($ownQuotes as $quote)
                    <p class="pd-testi__text">{{ $quoteOpen }}{{ $quote['text'] }}{{ $quoteClose }}</p>
                    <p class="pd-testi__links">
                        <a class="pd-case__live" href="{{ $quote['url'] }}" target="_blank" rel="noopener">{{ __('reviews.original.' . $quote['source']) }} ↗</a>
                    </p>
                    @endforeach
                    <p class="pd-testi__text">{{ $quoteOpen }}{{ $lead['text'] }}{{ $quoteClose }}</p>
                    <p class="pd-testi__links">
                        @foreach ($leadLinks as $link)
                        <a class="pd-case__live" href="{{ $link['url'] }}" target="_blank" rel="noopener">{{ __('reviews.original.' . $link['source']) }} ↗</a>
                        @endforeach
                        @if (!empty($lead['project']) && $projects->has($lead['project']))
                        <a class="pd-case__live" href="{{ $projects[$lead['project']]->detailUrl($locale) }}">{{ __('home.portfolio.detail_cta') }} →</a>
                        @endif
                    </p>
                </article>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="pd-section pd-about-cta" data-pdd="reviews-cta">
    <div class="container-site">
        <p class="pd-lead">{{ __('about.cta_text') }}</p>
        <a href="{{ lroute('contact') }}" class="pd-cta">
            {{ __('about.cta_button') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
        </a>
    </div>
</section>

</div>
@endsection
