<?php

/*
|--------------------------------------------------------------------------
| Přesměrování starých domén a adres (OND-455)
|--------------------------------------------------------------------------
| Nový web nahrazuje `ondraweb.cz` (Framer) i `itwebtech.cz` (starý web).
*/

$csv = static fn (string $value): array => array_values(array_filter(array_map(
    static fn (string $host): string => strtolower(trim($host)),
    explode(',', $value)
)));

return [

    /*
    | Kanonický host (bez schématu), např. `ondraweb.cz`. Prázdný = middleware
    | App\Http\Middleware\RedirectLegacyHost nedělá nic, takže kód jde na
    | produkci ještě před přepnutím DNS.
    */
    'canonical_host' => strtolower(trim((string) env('CANONICAL_HOST', ''))),

    /*
    | Hosty, které se 301 přesměrují na kanonický host (cesta i query zůstávají).
    | Čárkami oddělený seznam.
    */
    'legacy_hosts' => $csv((string) env('LEGACY_HOSTS', 'itwebtech.cz,www.itwebtech.cz,www.ondraweb.cz')),

    /*
    | Staré slugy případovek → dnešní slug v `portfolio_projects.slug`.
    | `null` = případovka na novém webu není, cíl je výpis `/projekty`.
    | Zdroj: sitemapy `itwebtech.cz` a `ondraweb.cz` + navigace starého webu,
    | slugy ověřené proti produkční DB 28. 9. 2026.
    */
    'project_slugs' => [
        // itwebtech.cz/projects/{slug}
        'clanek-na-motorkari-cz'   => 'clanek-motorkari-cz',
        'FRLcreator'               => 'frl-creator',
        'kempveselka'              => 'kemp-veselka',
        'strechyzajic'             => 'strechy-zajic',
        'vpindustry'               => 'vp-industry',
        'pitarena-reklamni-cedule' => 'pitarena-cedule',
        // Video k akademii (`video-pitbike-akademie`) není publikované.
        'pitarena-akademie-202308' => 'pitarena',
        // Nepublikované případovky.
        'logo-realitacky'          => null,
        'delejme-animace'          => null,
        // ondraweb.cz/projekty/{slug}
        'zoomorava'                => null,
    ],

];
