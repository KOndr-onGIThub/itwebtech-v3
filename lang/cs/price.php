<?php

return [

    'meta' => [
        'title'       => 'Ceník — Ondřej Kriška',
        'description' => 'Orientační ceník webových stránek, e-shopů a webových aplikací. Jasná představa o investici ještě před první konzultací.',
    ],

    'subheading' => 'Orientační ceny',
    'heading'    => 'Víte, do čeho jdete, ještě před první schůzkou.',
    // OND-198 (nález 5.4): očekávací věta musí padnout dřív než první číslo.
    'intro'      => 'Většina projektů, které stavím, vychází mezi 55 a 150 tisíci korunami. Pokud hledáte web do dvaceti tisíc, nebudu pro vás ten správný dodavatel a řeknu vám to rovnou. Každý projekt je jiný — finální cenu znáte po bezplatné konzultaci. Tento přehled vám dá jasnou představu ještě před naší první schůzkou.',

    // OND-135 P2 iter 5 — plán §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-
    // portfolio artefakt per CEO PR #78 precedent (home / kontakt).
    'hero' => [
        'page_mark_label' => 'CENÍK',
        'upline'          => 'Žádné nabídky na vyžádání.',
        // OND-198 (nález 5.4): v podnadpisu vede Standard, ne nejlevnější pásmo.
        'heading_html'    => 'Tři pásma,<br>jedna <em>jasná cena</em>.',
        'subline'         => 'Cena na faktuře = cena ve specifikaci. Žádné vícenáklady bez vašeho vědomí.',
    ],

    // Sticky CTA — viditelné napříč scrollem, „cena nikdy nezmizí".
    'sticky_cta' => [
        'label' => 'Vyberte si pásmo',
        'cta'   => 'Chci nezávaznou nabídku',
    ],

    'popular'   => 'Nejoblíbenější',
    'quotation' => 'Nezávazná poptávka',

    'price_note' => 'orientační cena',

    // OND-130 (B2 §1, klíčová direktiva 2 + plán §3.6): sjednocená taxonomie
    // Startovní / Standard / Custom — 25 / 55 / od 95 tis. Kč. Stejné
    // ceny v homepage cenové kotvě (`home.price_anchor.items`) i v service
    // 3-card kotvě (`home.services.primary.*`). Featurelisty zachovány,
    // doladění obsahu řeší B3 (Content Writer) v rámci stejného PR.
    'tiers' => [
        [
            'name'    => 'Standard',
            'desc'    => 'Pro firmy, které chtějí web jako svůj nejlepší obchodní nástroj.',
            'price'   => '55 000 Kč',
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
            'cta' => 'Chci nezávaznou nabídku',
        ],
        [
            'name'    => 'Custom',
            'desc'    => 'Pro náročné projekty bez kompromisů — e-shop, rezervace nebo webová aplikace.',
            'price'   => 'od 95 000 Kč',
            'popular' => false,
            'features' => [
                'Neomezený rozsah projektu',
                'E-shop nebo rezervační systém',
                'Vlastní administrační rozhraní',
                'Pokročilá SEO strategie s reportingem',
                'Integrace externích systémů',
                '3 měsíce podpory po spuštění',
            ],
            'cta' => 'Chci nezávaznou nabídku',
        ],
        [
            'name'    => 'Startovní',
            // OND-198 (nález 5.4): nejlevnější pásmo je poslední a rámované jako výjimka.
            'desc'    => 'Výjimka, ne standardní vstup. Pro živnostníky, kterým větší rozsah nedává smysl — důvěryhodná online prezentace do 5 stránek.',
            'price'   => '25 000 Kč',
            'popular' => false,
            'features' => [
                'Do 5 stránek na míru',
                'Moderní responzivní design',
                'Kontaktní formulář',
                'Technické SEO',
                'Optimalizace rychlosti načítání',
                '14 dní podpory po spuštění',
            ],
            'cta' => 'Chci nezávaznou nabídku',
        ],
    ],

    'note' => 'Nejsem plátce DPH — uvedené ceny jsou konečné, nic se k nim nepřičítá.',

    'guarantees' => [
        'heading' => 'Co je součástí každého projektu',
        'items'   => [
            [
                'title' => 'Bezúdržbové weby',
                'text'  => 'Žádný WordPress, žádné pluginy třetích stran. Ušetříte tisíce ročně oproti WordPressu — bez měsíčních aktualizací a bezpečnostních záplat.',
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
        // OND-130 sjednocená taxonomie — viz `tiers` výše.
        'tiers'   => ['Standard', 'Custom', 'Startovní'],
        'tabs_aria'     => 'Výběr cenové úrovně',
        'included'      => 'Zahrnuto',
        'not_included'  => 'Nezahrnuto',
        'groups'  => [
            [
                'label' => 'Rozsah projektu',
                'rows'  => [
                    ['label' => 'Počet stránek', 'values' => ['do 12', 'bez omezení', 'do 5']],
                    ['label' => 'Responzivní design', 'values' => [true, true, true]],
                    ['label' => 'Kontaktní formulář', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Funkce webu',
                'rows'  => [
                    ['label' => 'Blog nebo galerie s editací', 'values' => [true, true, false]],
                    ['label' => 'Vícejazyčný web', 'values' => [true, true, false]],
                    ['label' => 'Rezervační systém', 'values' => ['volitelně', true, false]],
                    ['label' => 'E-shop', 'values' => [false, true, false]],
                    ['label' => 'Vlastní administrace', 'values' => [false, true, false]],
                    ['label' => 'Integrace externích systémů', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Marketing a výkon',
                'rows'  => [
                    ['label' => 'Technické SEO', 'values' => [true, true, true]],
                    ['label' => 'Optimalizace rychlosti', 'values' => [true, true, true]],
                    ['label' => 'Analytika a měření konverzí', 'values' => [true, true, false]],
                    ['label' => 'Pokročilá SEO strategie', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Servis a podpora',
                'rows'  => [
                    ['label' => 'Hosting a doména zdarma', 'values' => ['1 rok', '1 rok', false]],
                    ['label' => 'Podpora po spuštění', 'values' => ['1 měsíc', '3 měsíce', '14 dní']],
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
