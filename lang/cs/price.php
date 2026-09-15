<?php

return [

    'meta' => [
        'title'       => 'Ceník — Ondřej Kriška',
        'description' => 'Orientační cenová pásma webových stránek, e-shopů a webových aplikací na míru.',
    ],

    'subheading' => 'Orientační ceny',
    'heading'    => 'Jasné ceny pro každý projekt',
    'intro'      => 'Každý projekt je jiný, finální cenu domluvíme dopředu. Tento přehled vám dá představu o cenových pásmech. Nejlevnější nejsem a nechci být. Když hledáte web do dvaceti tisíc, rovnou řeknu, že pro vás nejsem ten pravý.',

    'popular'   => 'Nejoblíbenější',
    'quotation' => 'Nezávazná poptávka',

    'price_note' => 'orientační cena',

    'tiers' => [
        [
            'name'    => 'Firemní web',
            'desc'    => 'Pro živnostníky a menší firmy, které potřebují důvěryhodnou prezentaci na internetu.',
            'price'   => '50 000–90 000 Kč',
            'popular' => false,
            'features' => [
                'Web na míru, obvykle do 5 stránek',
                'Design přizpůsobený vaší firmě',
                'Dobře vypadá na mobilu i na počítači',
                'Kontaktní formulář',
                'Technické základy pro vyhledávače',
                'Rychlé načítání',
                '14 dní podpory po spuštění',
            ],
            'cta' => 'Poptejte projekt',
        ],
        [
            'name'    => 'Web s administrací',
            'desc'    => 'Pro firmy, které chtějí obsah spravovat samy nebo potřebují web ve více jazycích.',
            'price'   => '90 000–150 000 Kč',
            'popular' => true,
            'features' => [
                'Rozsáhlejší web na míru',
                'Jednoduchá správa obsahu (texty, fotky, produkty)',
                'Blog nebo galerie',
                'Vícejazyčný web',
                'Napojení na měření návštěvnosti',
                'Hosting a doména na 1 rok zdarma',
                '1 měsíc podpory po spuštění',
            ],
            'cta' => 'Vybrat tento plán',
        ],
        [
            'name'    => 'E-shop / aplikace',
            'desc'    => 'Pro náročnější projekty — e-shop, rezervace nebo interní aplikace na míru.',
            'price'   => 'od 150 000 Kč',
            'popular' => false,
            'features' => [
                'Rozsah podle potřeb projektu',
                'E-shop nebo rezervační systém',
                'Vlastní administrační rozhraní',
                'Interní aplikace na míru vašemu provozu',
                'Napojení na další systémy, které používáte',
                '3 měsíce podpory po spuštění',
            ],
            'cta' => 'Poptejte projekt',
        ],
    ],

    'note' => 'Nejsem plátce DPH, ceny jsou konečné.',

    'guarantees' => [
        'heading' => 'Co je součástí každého projektu',
        'items'   => [
            [
                'title' => 'Bezúdržbové weby',
                'text'  => 'Žádný WordPress, žádné pluginy třetích stran. Odpadají náklady na pravidelné aktualizace a bezpečnostní záplaty.',
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
                'text'  => 'Odpovídám v pracovní dny, obvykle do dvou pracovních dnů, i týdny a měsíce po předání projektu. Drobné úpravy a technické dotazy jsou samozřejmostí.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Doplňkové služby',
        'desc'    => 'Digitální podpora i po spuštění projektu.',
        'items'   => [
            [
                'name'  => 'SEO a obsah',
                'price' => 'od 4 500 Kč / měs.',
                'desc'  => 'Péče o to, aby web dobře fungoval ve vyhledávačích, a pravidelný obsah.',
            ],
            [
                'name'  => 'Správa sociálních sítí',
                'price' => 'od 9 900 Kč / měs.',
                'desc'  => 'Tvorba obsahu, plánování a publikování, aby na sebe firma navazovala i mimo web.',
            ],
            [
                'name'  => 'Webová aplikace na míru',
                'price' => 'individuální nabídka',
                'desc'  => 'Evidence skladu, interní systémy, zákaznické portály. Cena odpovídá rozsahu projektu.',
            ],
            [
                'name'  => 'Grafika a branding',
                'price' => 'od 4 800 Kč',
                'desc'  => 'Logo, vizuální identita, bannery. Pro ucelenou a zapamatovatelnou prezentaci firmy.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'Co přesně dostanete',
        'tiers'   => ['Firemní web', 'Web s administrací', 'E-shop / aplikace'],
        'groups'  => [
            [
                'label' => 'Rozsah projektu',
                'rows'  => [
                    ['label' => 'Počet stránek', 'values' => ['do 5', 'více', 'bez omezení']],
                    ['label' => 'Design na míru', 'values' => [true, true, true]],
                    ['label' => 'Kontaktní formulář', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Funkce webu',
                'rows'  => [
                    ['label' => 'Správa obsahu (blog, galerie)', 'values' => [false, true, true]],
                    ['label' => 'Vícejazyčný web', 'values' => [false, true, true]],
                    ['label' => 'Rezervační systém', 'values' => [false, 'volitelně', true]],
                    ['label' => 'E-shop', 'values' => [false, false, true]],
                    ['label' => 'Vlastní administrace', 'values' => [false, false, true]],
                    ['label' => 'Napojení na další systémy', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Vyhledávače a výkon',
                'rows'  => [
                    ['label' => 'Technické základy pro vyhledávače', 'values' => [true, true, true]],
                    ['label' => 'Rychlé načítání', 'values' => [true, true, true]],
                    ['label' => 'Měření návštěvnosti', 'values' => [false, true, true]],
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
        'desc'    => 'Napište mi, co řešíte. Ozvu se zpět a upřímně vám řeknu, co dává pro vaši firmu smysl — i to, jestli spolupráce smysl nemá.',
        'btn'     => 'Napsat zprávu',
    ],

];
