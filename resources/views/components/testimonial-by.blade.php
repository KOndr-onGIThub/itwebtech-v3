{{--
    OND-353 (návrh OND-346) — podpis pod cizí citací: tvář (nebo monogram),
    jméno a zdroj. Jedna komponenta pro všech sedm citací na homepage.

    `person` je položka z `lang/*/testimonials.php` — musí mít `name`,
    `company`, `role`, `source` a buď `image`, nebo `initials`.

    `size` 56 = sekce 09 „Co říkají klienti" (portrét stojí ve sloupci, kde
    dřív byl ordinál), 40 = krátké pull-quotes u kroků, u Toyoty a u formuláře.
--}}
@props([
    'person',
    'size' => 56,
])

@php
    // Značky zdrojů leží v `public/img/testimonials/` jako hotová malá PNG —
    // responzivní varianty se pro ně negenerují, proto `asset()`, ne
    // `<x-responsive-image>`. Logo nese informaci o zdroji, takže textovou
    // alternativu mít musí (fotka naopak `alt=""`, jméno stojí hned vedle).
    $sourceLogos = [
        'google'   => ['file' => 'google.png', 'alt' => 'Recenze na Google'],
        'facebook' => ['file' => 'fb.png',     'alt' => 'Recenze na Facebooku'],
    ];

    $source = $person['source'] ?? null;
    $logo   = $sourceLogos[$source] ?? null;

    // OND-368: role patří jen k 56px variantě v sekci 09, kde na ni je místo.
    // U 40px pull-quotes se „firma — role" láme na dva verzálkové řádky
    // a přebije jméno nad sebou („TOYOTA — ŘEDITEL ŘÍZENÍ VÝROBY, MONTÁŽE
    // A LOGISTIKY"). Tam nese informaci firma, role je balast.
    $showRole = $size > 40 && !empty($person['role']);
@endphp

<div class="pd-by pd-by--{{ $size }}">
    <figure class="pd-by__face">
        @if (!empty($person['image']))
            <x-responsive-image
                :path="'testimonials/' . $person['image']"
                alt=""
                :sizes="$size . 'px'"
                loading="lazy"
            />
        @else
            <span class="pd-by__mono" aria-hidden="true">{{ $person['initials'] ?? '' }}</span>
        @endif
    </figure>

    <div class="pd-by__text">
        <p class="pd-by__line">
            <span class="pd-by__name">{{ $person['name'] }}</span>
            @if ($logo)
                <span class="pd-by__src">
                    <img
                        class="pd-by__logo pd-by__logo--{{ $source }}"
                        src="{{ asset('img/testimonials/' . $logo['file']) }}"
                        alt="{{ $logo['alt'] }}"
                        loading="lazy"
                    >
                </span>
            @elseif ($source === 'firmy_cz')
                {{-- Firmy.cz nemá značku, jen logotyp 110 × 24. Pod ~24 px z něj
                     je šmouha, ve 24 px by vedle jména soupeřil velikostí
                     (vyzkoušeno na živé stránce, OND-346). Slot zůstává stejný,
                     obsahem je slovo vysázené písmem stránky. --}}
                <span class="pd-by__src pd-by__src--word" title="Recenze na Firmy.cz">Firmy.cz</span>
            @endif
        </p>
        <span class="pd-by__org">{{ $person['company'] }}@if ($showRole) — {{ $person['role'] }}@endif</span>
    </div>
</div>
