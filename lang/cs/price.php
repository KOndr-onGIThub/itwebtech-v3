<?php

return [

    'meta' => [
        'title'       => 'Ceník — Ondřej Kriška',
        'description' => 'Orientační ceník webových stránek, e-shopů a webových aplikací. Jasná představa o investici ještě před první konzultací.',
    ],

    'subheading' => 'Orientační ceny',
    'heading'    => 'Jasné ceny pro každý projekt',
    'intro'      => 'Každý projekt je jiný — finální cenu znáte po bezplatné konzultaci. Tento přehled vám dá jasnou představu o investici ještě před naší první schůzkou.',

    'popular'   => 'Nejoblíbenější',
    'quotation' => 'Nezávazná poptávka',

    'price_note' => 'orientační cena',

    'tiers' => [
        [
            'name'    => 'Prezentace',
            'desc'    => 'Pro živnostníky a malé firmy, kteří potřebují důvěryhodnou online prezentaci.',
            'price'   => 'od 20 000 Kč',
            'popular' => false,
            'features' => [
                'Do 5 stránek na míru',
                'Moderní responzivní design',
                'Kontaktní formulář',
                'Technické SEO',
                'Optimalizace rychlosti načítání',
                '14 dní podpory po spuštění',
            ],
            'cta' => 'Poptejte projekt',
        ],
        [
            'name'    => 'Profesionál',
            'desc'    => 'Pro firmy, které chtějí web jako svůj nejlepší obchodní nástroj.',
            'price'   => 'od 45 000 Kč',
            'popular' => true,
            'features' => [
                'Do 12 stránek na míru',
                'Konverzní design zaměřený na výsledky',
                'Blog nebo galerie s editací obsahu',
                'Vícejazyčný web',
                'Analytika a měření konverzí',
                'Hosting a doména na 1 rok zdarma',
                '1 měsíc podpory po spuštění',
            ],
            'cta' => 'Vybrat tento plán',
        ],
        [
            'name'    => 'Komplex',
            'desc'    => 'Pro náročné projekty bez kompromisů — e-shop, rezervace nebo webová aplikace.',
            'price'   => 'od 85 000 Kč',
            'popular' => false,
            'features' => [
                'Neomezený rozsah projektu',
                'E-shop nebo rezervační systém',
                'Vlastní administrační rozhraní',
                'Pokročilá SEO strategie s reportingem',
                'Integrace externích systémů',
                '3 měsíce podpory po spuštění',
            ],
            'cta' => 'Konzultace zdarma',
        ],
    ],

    'note' => 'Orientační ceny bez DPH. Nejsem plátce DPH.',

    'guarantees' => [
        'heading' => 'Co je součástí každého projektu',
        'items'   => [
            [
                'title' => 'Bezúdržbové weby',
                'text'  => 'Žádný WordPress, žádné pluginy třetích stran. Ušetříte až 20 000 Kč ročně za pravidelné aktualizace a záplaty.',
            ],
            [
                'title' => 'Cena předem bez překvapení',
                'text'  => 'Přesná nabídka ještě před zahájením práce. Co je v nabídce, to je i na faktuře — žádné vícenáklady bez vašeho vědomí.',
            ],
            [
                'title' => 'Přímá komunikace',
                'text'  => 'Mluvíte přímo se mnou — bez obchodníků, projektových manažerů a koordinátorů. Jedno místo kontaktu, jedno místo zodpovědnosti.',
            ],
            [
                'title' => 'Podpora i po spuštění',
                'text'  => 'Odpovím do 24 hodin, i týdny a měsíce po předání projektu. Drobné úpravy a technické dotazy jsou samozřejmostí.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Doplňkové služby',
        'desc'    => 'Komplexní digitální podpora i po spuštění projektu.',
        'items'   => [
            [
                'name'  => 'SEO a obsahový marketing',
                'price' => 'od 4 500 Kč / měs.',
                'desc'  => 'Analýza klíčových slov, obsahová strategie, sledování výkonu. Organická viditelnost, která pracuje i bez reklamního rozpočtu.',
            ],
            [
                'name'  => 'Správa sociálních sítí',
                'price' => 'od 9 900 Kč / měs.',
                'desc'  => 'Tvorba obsahu, plánování a publikování. Konzistentní přítomnost, která buduje důvěru zákazníků.',
            ],
            [
                'name'  => 'Webová aplikace na míru',
                'price' => 'individuální nabídka',
                'desc'  => 'Evidence skladu, interní systémy, zákaznické portály. Cena odpovídá složitosti a rozsahu projektu.',
            ],
            [
                'name'  => 'Grafický design a branding',
                'price' => 'od 4 800 Kč',
                'desc'  => 'Logo, vizuální identita, bannery. Vše co potřebujete pro konzistentní a zapamatovatelnou prezentaci značky.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'Co přesně dostanete',
        'tiers'   => ['Prezentace', 'Profesionál', 'Komplex'],
        'groups'  => [
            [
                'label' => 'Rozsah projektu',
                'rows'  => [
                    ['label' => 'Počet stránek', 'values' => ['do 5', 'do 12', 'bez omezení']],
                    ['label' => 'Responzivní design', 'values' => [true, true, true]],
                    ['label' => 'Kontaktní formulář', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Funkce webu',
                'rows'  => [
                    ['label' => 'Blog nebo galerie s editací', 'values' => [false, true, true]],
                    ['label' => 'Vícejazyčný web', 'values' => [false, true, true]],
                    ['label' => 'Rezervační systém', 'values' => [false, 'volitelně', true]],
                    ['label' => 'E-shop', 'values' => [false, false, true]],
                    ['label' => 'Vlastní administrace', 'values' => [false, false, true]],
                    ['label' => 'Integrace externích systémů', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Marketing a výkon',
                'rows'  => [
                    ['label' => 'Technické SEO', 'values' => [true, true, true]],
                    ['label' => 'Optimalizace rychlosti', 'values' => [true, true, true]],
                    ['label' => 'Analytika a měření konverzí', 'values' => [false, true, true]],
                    ['label' => 'Pokročilá SEO strategie', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Servis a podpora',
                'rows'  => [
                    ['label' => 'Hosting a doména zdarma', 'values' => [false, '1 rok', '1 rok']],
                    ['label' => 'Podpora po spuštění', 'values' => ['14 dní', '1 měsíc', '3 měsíce']],
                    ['label' => 'Bezúdržbový provoz', 'values' => [true, true, true]],
                ],
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Nejste si jistí, co přesně potřebujete?',
        'desc'    => 'Konzultace je zdarma a nezávazná. Během 30 minut zjistím, co dává pro váš byznys smysl — a upřímně vám řeknu i to, jestli spolupráce smysl nemá.',
        'btn'     => 'Domluvit konzultaci zdarma',
    ],

];
