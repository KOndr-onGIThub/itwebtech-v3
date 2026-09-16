@props([
    'screenshots',
    'part' => 'all', // 'lead' = jen hlavní celošířkový vizuál | 'rest' = zbytek | 'all' = obojí
])

{{--
    OND-202 (kurátorský layout po 2× zamítnuté kartě boardem):

    Každý snímek má roli určenou z poměru stran (screenshot_gallery_role):
      - `wide`  (>= 1.5) — hlavní vizuály (3-device mockupy, bannery)
        → VÝHRADNĚ celošířkový band v přirozeném poměru, nikdy malá karta.
      - `card`  (< 1.5)  — podpůrné snímky (čtvercové detaily zařízení)
        → párová mřížka v jednotném čtvercovém výřezu; dominantní čtverce
        1800×1800 se nijak neořezávají, plný snímek je vždy v lightboxu.

    `part` umožňuje stránce střídat text s obrázky: `lead` (první wide,
    preferenčně type=hero) se renderuje NAD textem případovky jako hlavní
    vizuál, `rest` (ostatní bandy + karty v původním pořadí) až POD ní.
--}}
@php
    $screens = collect($screenshots ?? []);

    // Pořadí: hero první, pak gallery v DB pořadí.
    $ordered = $screens->sortBy(fn ($s) => $s->type === 'hero' ? 0 : 1)->values();

    // Lead = první snímek s rolí wide (hlavní vizuál). Projekty se čtvercovým
    // hero (vp-industry, zubni-provazek) tak dostanou jako lead svůj wide
    // gallery mockup a čtvercové hero se zařadí mezi karty.
    $lead = $ordered->first(fn ($s) => screenshot_gallery_role($s->path) === 'wide');
    $rest = $ordered->reject(fn ($s) => $lead && $s->is($lead))->values();

    // Rest → sekvence bloků: běžící skupina karet se přeruší každým wide bandem.
    $blocks = [];
    $cardRun = [];
    foreach ($rest as $shot) {
        if (screenshot_gallery_role($shot->path) === 'wide') {
            if ($cardRun) {
                $blocks[] = ['type' => 'cards', 'items' => $cardRun];
                $cardRun = [];
            }
            $blocks[] = ['type' => 'band', 'shot' => $shot];
        } else {
            $cardRun[] = $shot;
        }
    }
    if ($cardRun) {
        $blocks[] = ['type' => 'cards', 'items' => $cardRun];
    }
@endphp

@if (in_array($part, ['lead', 'all']) && $lead)
<section class="portfolio-detail-gallery portfolio-detail-gallery--lead section-wrapper" data-reveal>
    <div class="container-site">
        <figure class="portfolio-detail-gallery__band">
            <x-portfolio.screenshot
                :path="$lead->path"
                :alt="$lead->translation()?->alt ?? ''"
                sizes="(max-width: 1024px) 100vw, 1100px"
                loading="eager"
                fetchpriority="high"
                :lightbox-gallery="'portfolio-screenshots'"
            />
            @if ($lead->translation()?->caption)
                <figcaption>{{ $lead->translation()->caption }}</figcaption>
            @endif
        </figure>
    </div>
</section>
@endif

@if (in_array($part, ['rest', 'all']) && count($blocks))
<section class="portfolio-detail-gallery section-wrapper" data-reveal>
    <div class="container-site">
        @foreach ($blocks as $block)
            @if ($block['type'] === 'band')
                <figure class="portfolio-detail-gallery__band">
                    <x-portfolio.screenshot
                        :path="$block['shot']->path"
                        :alt="$block['shot']->translation()?->alt ?? ''"
                        sizes="(max-width: 1024px) 100vw, 1100px"
                        loading="lazy"
                        :lightbox-gallery="'portfolio-screenshots'"
                    />
                    @if ($block['shot']->translation()?->caption)
                        <figcaption>{{ $block['shot']->translation()->caption }}</figcaption>
                    @endif
                </figure>
            @else
                <div class="portfolio-detail-gallery__grid" data-reveal-group>
                    @foreach ($block['items'] as $shot)
                        <figure class="portfolio-detail-gallery__item">
                            <x-portfolio.screenshot
                                :path="$shot->path"
                                :alt="$shot->translation()?->alt ?? ''"
                                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 540px"
                                loading="lazy"
                                :lightbox-gallery="'portfolio-screenshots'"
                            />
                            @if ($shot->translation()?->caption)
                                <figcaption>{{ $shot->translation()->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</section>
@endif
