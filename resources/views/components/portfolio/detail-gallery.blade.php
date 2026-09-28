@props([
    'screenshots',
    'part' => 'all', // 'lead' = jen hlavní celošířkový vizuál | 'rest' = zbytek | 'all' = obojí
    'transitionName' => null, // OND-438: jméno přechodu na rámu hlavního vizuálu (karta projektu → detail)
    'project' => null, // OND-449 (B-07b): projekt s `demo_video` → video pod prvním blokem galerie
])

{{--
    OND-402 — galerie ve slovníku ACID: `.pd-gallery`. Logika rolí (OND-202,
    OND-265, OND-268) je BEZE ZMĚNY, mění se jen třídy a sazba: rám 1 px
    `--color-border-soft` jako `.pd-case__visual` na homepage, žádné
    zaoblení, žádná šedá deska. Rám nese `__frame`, popisek stojí pod ním.

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
    // OND-268: `type = 'thumbnail'` je snímek pořízený VÝHRADNĚ pro miniaturu
    // karty projektu (`portfolio_card_thumbnail()`). Do galerie na detailu
    // nepatří — jinak by se tentýž obrázek ukázal dvakrát. Obecný nástroj
    // na kterýkoli další projekt, jehož první karta je špatná miniatura.
    $screens = collect($screenshots ?? [])->reject(fn ($s) => $s->type === 'thumbnail');

    // Pořadí: hero první, pak gallery v DB pořadí.
    $ordered = $screens->sortBy(fn ($s) => $s->type === 'hero' ? 0 : 1)->values();

    // Lead = první snímek s rolí wide (hlavní vizuál). Projekty se čtvercovým
    // hero (vp-industry, zubni-provazek) tak dostanou jako lead svůj wide
    // gallery mockup a čtvercové hero se zařadí mezi karty.
    // OND-449 (B-06): tentýž helper vybírá obrázek karty na /projekty —
    // karta a lead jsou vždy tentýž soubor, přechod jen zvětší obrázek.
    $lead = portfolio_lead_image($screens);
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
@php
    // OND-438: obrázek sem dosazuje až Alpine (sdílený basename `hero-1`,
    // viz responsive_image_srcsets()), takže do té doby měl rám výšku 2 px.
    // Obrázek z karty projektu pak při přechodu přejel do zploštělého rámu
    // a stránka pod ním poskočila. Poměr stran ze souboru drží rám od
    // prvního vykreslení ve správné výšce.
    $leadDims = screenshot_dimensions_any($lead->path);
    $leadStyle = collect([
        $transitionName ? "view-transition-name: {$transitionName}" : null,
        $leadDims ? "--lead-ratio: {$leadDims['width']} / {$leadDims['height']}" : null,
    ])->filter()->implode('; ');
@endphp
<section class="pd-section pd-gallery pd-gallery--lead" data-pdd="project-lead">
    <div class="container-site">
        <figure class="pd-gallery__band">
            <div class="pd-gallery__frame"@if ($leadStyle !== '') style="{{ $leadStyle }}"@endif>
                <x-portfolio.screenshot
                    :path="$lead->path"
                    :alt="$lead->translation()?->alt ?? ''"
                    sizes="(max-width: 1024px) 100vw, 1216px"
                    loading="eager"
                    fetchpriority="high"
                    :lightbox-gallery="'portfolio-screenshots'"
                />
            </div>
            @if ($lead->translation()?->caption)
                <figcaption>{{ $lead->translation()->caption }}</figcaption>
            @endif
        </figure>
    </div>
</section>
@endif

@php
    // OND-449 (B-07b): video smyčka pod prvním blokem galerie (jen `rest`/`all`).
    $showVideo = in_array($part, ['rest', 'all']) && filled($project?->demo_video);
@endphp
@if (in_array($part, ['rest', 'all']) && (count($blocks) || $showVideo))
<section class="pd-section pd-gallery" data-pdd="project-gallery">
    <div class="container-site">
        @if ($showVideo && ! count($blocks))
            <x-portfolio.demo-video :project="$project" />
        @endif
        @foreach ($blocks as $block)
            @if ($block['type'] === 'band')
                <figure class="pd-gallery__band">
                    <div class="pd-gallery__frame">
                        <x-portfolio.screenshot
                            :path="$block['shot']->path"
                            :alt="$block['shot']->translation()?->alt ?? ''"
                            sizes="(max-width: 1024px) 100vw, 1216px"
                            loading="lazy"
                            :lightbox-gallery="'portfolio-screenshots'"
                        />
                    </div>
                    @if ($block['shot']->translation()?->caption)
                        <figcaption>{{ $block['shot']->translation()->caption }}</figcaption>
                    @endif
                </figure>
            @else
                <div class="pd-gallery__grid">
                    @foreach ($block['items'] as $i => $shot)
                        @php
                            // OND-265: lichý počet dlaždic nechával v mřížce
                            // prázdnou pravou buňku. Poslední osamocená dlaždice
                            // proto jde přes obě buňky: široký snímek jako band,
                            // čtvercový vycentrovaný v šířce jedné buňky.
                            $isAlone = $i === count($block['items']) - 1 && $i % 2 === 0;
                            $ratio   = screenshot_tile_ratio($shot->path);
                            $classes = 'pd-gallery__item';
                            if ($isAlone) {
                                $classes .= $ratio >= 1.2
                                    ? ' pd-gallery__item--alone-band'
                                    : ' pd-gallery__item--alone';
                            }
                        @endphp
                        <figure class="{{ $classes }}" style="--shot-ratio: {{ $ratio }}">
                            <div class="pd-gallery__frame">
                                <x-portfolio.screenshot
                                    :path="$shot->path"
                                    :alt="$shot->translation()?->alt ?? ''"
                                    sizes="(max-width: 639px) 100vw, (max-width: 1024px) 50vw, 592px"
                                    loading="lazy"
                                    :lightbox-gallery="'portfolio-screenshots'"
                                />
                            </div>
                            @if ($shot->translation()?->caption)
                                <figcaption>{{ $shot->translation()->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
            @if ($loop->first && $showVideo)
                <x-portfolio.demo-video :project="$project" />
            @endif
        @endforeach
    </div>
</section>
@endif
