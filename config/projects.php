<?php

/*
|--------------------------------------------------------------------------
| Realizované projekty (statická data)
|--------------------------------------------------------------------------
| Ověřené případovky. Databáze se zatím nezavádí — projekty se přidávají
| jednoduše přidáním další položky do pole 'items' (klíč = slug v URL
| /projekty/{slug}).
|
| Struktura jedné položky:
|   name      … název projektu
|   field     … obor / odvětví (zobrazí se jako štítek na kartě)
|   category  … kategorie pro filtr: 'web' | 'app' | 'other'
|   summary   … jedna shrnující věta na kartu
|   live_url  … odkaz na živý web (null = interní / nedostupný)
|   brief     … zadání (co klient potřeboval)
|   built     … co jsem postavil
|   features  … pole bodů „co web umí" (může být prázdné)
|   result    … výsledek / dopad (volitelné, jinak null)
*/

return [

    'items' => [

        'pitarena' => [
            'name'     => 'PitAréna',
            'field'    => 'Motoristika',
            'category' => 'app',
            'summary'  => 'E-shop s katalogem pitbike motorek YCF a náhradních dílů, roztříděných podle modelů a skupin.',
            'live_url' => 'https://shop.pitarena.cz',
            'brief'    => 'Klient prodává pitbike motorky YCF a náhradní díly. Potřeboval prodávat online, včetně dílů, kde je klíčové poskládat správný kus pro daný model a ročník.',
            'built'    => 'E-shop s katalogem motorek i dílů, roztříděných podle modelů a skupin.',
            'features' => [
                'Košík a uživatelský účet.',
                'Kategorie podle modelů (LITE 125, PILOT 125, Factory 190) a skupin dílů (brzdy, motory, tlumiče, elektrika).',
                'Filtrování podle modelu a ročníku.',
                'Oblíbené a porovnání.',
            ],
            'result'   => null,
        ],

        'barana' => [
            'name'     => 'BARANA',
            'field'    => 'Stavebnictví',
            'category' => 'web',
            'summary'  => 'Prezentační web pro výrobce hliníkových pergol, bran a plotů na míru s galerií realizací a poptávkou.',
            'live_url' => 'https://barana.cz',
            'brief'    => 'Klient vyrábí hliníkové pergoly, brány a ploty na míru. Potřeboval web, který srozumitelně ukáže, co dělá, a ze kterého se lidé snadno ozvou.',
            'built'    => 'Prezentační web se službami, galerií realizací a poptávkovým formulářem.',
            'features' => [
                'Rozdělené služby (bioklimatické pergoly, brány a ploty, návrh na míru).',
                'Galerie realizací.',
                'Poptávkový formulář a kontakt.',
                'Sekce „Jak to probíhá".',
            ],
            'result'   => null,
        ],

        'zubni-provazek' => [
            'name'     => 'Zubní ordinace Provázek',
            'field'    => 'Stomatologie',
            'category' => 'web',
            'summary'  => 'Prezentační web zubní ordinace pro dospělé i děti s online objednáním.',
            'live_url' => 'https://zubniprovazek.cz',
            'brief'    => 'Klient provozuje zubní ordinaci pro dospělé i děti. Potřeboval web s informacemi o ordinaci a snadné online objednání.',
            'built'    => 'Prezentační web s online objednáním.',
            'features' => [
                'Online objednání.',
                'Přehled služeb.',
                'Ceník.',
                'Sekce O nás.',
                'Kontakt a otevírací doba.',
            ],
            'result'   => null,
        ],

        'toyota-tsm' => [
            'name'     => 'Toyota TSM',
            'field'    => 'Logistika / interní',
            'category' => 'app',
            'summary'  => 'Webová aplikace na míru procesům logistiky, která nahradila ruční a zdlouhavé postupy.',
            'live_url' => null,
            'brief'    => 'Ruční a zdlouhavé procesy v logistice.',
            'built'    => 'Webovou aplikaci na míru procesům logistiky.',
            'features' => [],
            'result'   => 'Aplikace firmě přinesla úsporu v řádu milionů korun.',
        ],

    ],

];
