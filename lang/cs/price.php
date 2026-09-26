<?php

return [

    'meta' => [
        'title'       => 'Ceník — Ondřej Kriška',
        'description' => 'Orientační ceník webových stránek, e-shopů a webových aplikací. Jasná představa o investici ještě před první konzultací.',
    ],

    'subheading' => 'Orientační ceny',
    'heading'    => 'Víte, do čeho jdete, ještě před první schůzkou.',
    // OND-198 (nález 5.4): očekávací věta musí padnout dřív než první číslo.
    // OND-354: prahové číslo a rozpětí místo menu tří balíčků (Ondřej 26. 9.
    // 2026 na OND-347). Odmítací věta „pokud hledáte web do dvaceti tisíc…"
    // je tím pryč — spodní hranice 20 000 Kč říká totéž bez odmítnutí.
    'intro'      => 'Většina projektů vychází mezi 55 a 150 tisíci korunami. Nejmenší web, který stavím, je prezentace do pěti stránek od 20 000 Kč. Co na webu bude a kolik to bude stát, dostanete písemně před začátkem práce — a to číslo je i na faktuře.',

    // OND-135 P2 iter 5 — plán §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-
    // portfolio artefakt per CEO PR #78 precedent (home / kontakt).
    'hero' => [
        'page_mark_label' => 'CENÍK',
        'upline'          => 'Žádné nabídky na vyžádání.',
        // OND-354: „Tři pásma, jedna jasná cena" přestalo být pravda — pásma
        // po téhle změně nejsou.
        'heading_html'    => 'Kolik u mě stojí<br><em>web na míru</em>.',
        'subline'         => 'Cena na faktuře = cena ve specifikaci. Žádné vícenáklady bez vašeho vědomí.',
    ],

    // Sticky CTA — viditelné napříč scrollem, „cena nikdy nezmizí".
    'sticky_cta' => [
        'label' => 'Vyberte si pásmo',
        'cta'   => 'Chci nezávaznou nabídku',
    ],

    'popular'   => 'Nejoblíbenější',
    'quotation' => 'Nezávazná poptávka',

    // OND-354: úrovně se jmenují podle ROZSAHU, ne podle cenové hladiny, a
    // cenu nenesou — klíč `price` je zrušený (a s ním i `price_note`
    // „orientační cena", které bez čísla nemá co popisovat). `key` je technický
    // identifikátor pro analytiku (dřív se posílalo číslo z ceny, dimenze
    // `pricing_tier_shown`) — nikde se nevykresluje, stejný princip jako
    // `home.faq.items[].key`. Názvy a `desc` jsou shodné s homepage kotvou
    // (`home.price_anchor.items`), featurelisty jsou beze změny.
    // Pořadí podle rostoucího rozsahu, doporučená je prostřední úroveň.
    'tiers' => [
        [
            'key'     => 'presentation',
            'name'    => 'Prezentační web',
            'scope'   => 'do 5 stránek',
            'desc'    => 'Důvěryhodná online prezentace pro živnostníky a malé firmy.',
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
        [
            'key'     => 'business',
            'name'    => 'Firemní web',
            'scope'   => 'do 12 stránek',
            'desc'    => 'Vícejazyčný web s blogem, měřením konverzí a rezervačním systémem.',
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
            'key'     => 'custom',
            'name'    => 'Na míru',
            'scope'   => 'bez omezení rozsahu',
            'desc'    => 'E-shop, webová aplikace nebo komplexní portál.',
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
    ],

    // OND-354: nahrazuje omluvné „Výjimka, ne standardní vstup." u nejnižší
    // úrovně. Neodmítá člověka, ale říká, co za ty peníze nepřijde.
    'entry_note' => 'Za dvacet tisíc postavím web do pěti stránek. Bude rychlý, na telefonu se bude ovládat dobře a nebude na něm rozbitý odkaz na poptávku. Nečekejte od něj, že vám sám začne vozit zakázky — na to je potřeba víc práce, než se za tu cenu dá odvést. Ale hotový bude poctivě.',

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
                'text'  => 'I týdny a měsíce po předání projektu se ozvu nejpozději následující pracovní den. Drobné úpravy a technické dotazy jsou samozřejmostí.',
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

    // OND-354: osa tabulky se mění. Dřív srovnávala tři pojmenovaná pásma —
    // ta zmizela, takže se tabulka neměla o co opřít. Nová osa je „co cenu
    // zvedá a co snižuje": nejběžnější způsob, jak cenu vysvětlit bez cenovky
    // (14 z 26 dodavatelů v německojazyčném vzorku rešerše na OND-347).
    'compare' => [
        'heading' => 'Co cenu zvedá a co snižuje',
        'up'   => [
            'label' => 'Zvedá cenu',
            'items' => [
                'Víc než pět stránek',
                'Druhý a další jazyk',
                'E-shop nebo rezervační systém',
                'Vlastní administrace obsahu',
                'Napojení na systémy, které už používáte',
                'Texty a fotky, které je potřeba vytvořit',
            ],
        ],
        'down' => [
            'label' => 'Snižuje cenu',
            'items' => [
                'Texty a fotky máte připravené',
                'Menší počet stránek',
                'Jeden jazyk',
                'Obsah si po zaškolení plníte sami',
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Nejste si jistí, co přesně potřebujete?',
        'desc'    => 'Konzultace je zdarma a nezávazná. Během 30 minut zjistím, co dává pro váš byznys smysl — a upřímně vám řeknu i to, jestli spolupráce smysl nemá.',
        'btn'     => 'Domluvit konzultaci zdarma',
    ],

];
