{{--
    OND-438 — přednačtení (Speculation Rules, návrh 3 z OND-429)
    ─────────────────────────────────────────────────────────────
    Když člověk najede myší na odkaz (`moderate` = ~200 ms), prohlížeč cílovou
    stránku předem vykreslí, takže po kliknutí je skoro hotová. Na telefonu
    Chrome vybírá podle toho, co je na obrazovce. Umí to jen Chrome/Edge,
    ostatní prohlížeče značku ignorují.

    Kam smí: jen VLASTNÍ odkazy v aktuálním jazyce, na detail projektu a na
    hlavní podstránky z menu. Seznam je výčet, ne zákaz, takže nic dalšího
    se nepřednačte:
      - `search: ""` → nic s `?` (vzor jinak parametry propouští, ověřeno
        na URLPattern v Chromu 147),
      - cesta je relativní k webu → odkazy ven neprojdou,
      - `/cookies`, zásady ochrany údajů, homepage a články v seznamu nejsou,
      - `[hreflang]` → přepínač jazyka, `[target]` a `[download]` → nové
        okno a soubory,
      - odesílání formulářů přednačtení nikdy nespouští (jen odkazy).

    Analytika: GA4 a Clarity v přednačtené stránce nestartují, dokud ji
    člověk skutečně neotevře (`resources/js/prerender.js`). Plausible to
    řeší sám.
--}}
@php
    $speculationPath = fn (string $route) => parse_url(lroute($route), PHP_URL_PATH) ?: '/';
    $speculationPages = [$speculationPath('projects') . '/*'];
    foreach (['projects', 'price', 'about', 'reviews', 'blog', 'contact'] as $speculationRoute) {
        $speculationPages[] = $speculationPath($speculationRoute);
    }
    $speculationRules = [
        'prerender' => [[
            'where' => ['and' => [
                ['or' => array_map(fn ($p) => ['href_matches' => ['pathname' => $p, 'search' => '']], $speculationPages)],
                ['not' => ['selector_matches' => '[hreflang], [target], [download]']],
            ]],
            'eagerness' => 'moderate',
        ]],
    ];
@endphp
<script type="speculationrules">{!! json_encode($speculationRules, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
