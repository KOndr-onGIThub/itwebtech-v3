<?php

return [

    'meta' => [
        'title'       => 'Co jsem postavil | ONDRAWEB',
        'description' => 'Weby, e-shopy a webové aplikace, které jsem postavil a které dnes běží. U každého je odkaz na živou verzi, takže si to můžete ověřit sami.',
    ],

    'subheading'       => 'Realizované',
    'heading'          => 'PROJEKTY',
    // OND-201 (bod 5): úvod podle sekce 4 dokumentu texty-podstranky (OND-186).
    'intro'            => 'Tady najdete weby, které jsem postavil a které dnes běží. Nejsou to obrázky v galerii — na každý se dá kliknout a podívat se, jak funguje naživo. Radši ukážu hotovou práci než sliby.',

    // OND-135 P2 iter 6 — plán §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-
    // portfolio artefakt per CEO PR #78/#80/#82 precedent (home/kontakt/cenik).
    // OND-201 (nález 5.2, KRITICKÉ): hero sliboval „Hotové projekty, hotová
    // čísla." a „Žádný screenshot bez čísla." — slib, který stránka o dva
    // odstavce níž sama porušila, protože tvrdá čísla nemáme u všech projektů.
    // Slib je přeformulovaný na důkaz, který doložit umíme: co web umí,
    // živý odkaz, rozsah. Žádná vymyšlená čísla.
    'hero' => [
        'page_mark_label' => 'REALIZACE',
        'upline'          => 'Živé weby, ne obrázky v galerii.',
        'heading_html'    => 'Co jsem<br><em>postavil</em>.',
        'subline'         => 'U každého projektu je napsané, co web umí a v jakém rozsahu jsem ho postavil. Odkaz vede na živou verzi — ověřte si to sami.',
    ],

    'filter_all'       => 'Vše',
    'filter_websites'  => 'Stránky',
    'filter_webapps'   => 'Aplikace',
    'filter_other'     => 'Ostatní',
    'filter_aria'      => 'Filtr projektů podle kategorie',
    'count_label'      => 'projektů zobrazeno',

    'info_client'      => 'Klient',
    'info_date'        => 'Kdy',
    'info_categories'  => 'Kategorie',
    'info_price'       => 'Orientační cena',

    'why_me' => [
        'subheading' => 'Takhle to dělám já',
        'heading'    => 'Do projektů vkládám následující',
        'items'      => [
            ['title' => 'Expertiza a praxe',      'description' => 'Díky 18leté zkušenosti v Toyotě mám unikátní praxi v optimalizaci procesů a vývoji webových aplikací.'],
            ['title' => 'Stabilita a robustnost', 'description' => 'Nestavím web z cizích doplňků, které se rozbijí při první aktualizaci. Píšu vlastní kód, který drží.'],
            ['title' => 'Důkladné testování',     'description' => 'Nenechávám nic náhodě. Aplikace i webové stránky testuji v průběhu vývoje i po jeho dokončení.'],
            ['title' => 'Rychlost a design',      'description' => 'Prioritou je rychlé načítání a moderní design, což zajišťuje pozitivní první dojem a příjemnou uživatelskou zkušenost.'],
            ['title' => 'Řešení na míru',         'description' => 'Každý projekt je pro mě unikátní a vždy hledám nejlepší řešení přizpůsobené potřebám a cílům každého klienta.'],
            ['title' => 'Důraz na detail',        'description' => 'Vždy věnuji velkou pozornost detailům, které mohou být rozhodující pro úspěch vašeho projektu.'],
        ],
    ],

    'cta_all' => 'Podívejte se na mé další projekty',

    // OND-201 (nález 5.2, KRITICKÉ): sekce dřív ukazovala tři anonymní
    // „ukázky výsledků z podobných zakázek" s vymyšlenými termíny (4/6/7
    // týdnů) a nedoložitelnými dopady („vyšší důvěryhodnost", „zkrácení
    // reakční doby"). Nahrazeno skutečnými případovkami z dokumentu
    // `pripadovky` (OND-186) — ověřené na živých webech, žádná vymyšlená
    // čísla. Souhlas se zveřejněním úspory u Toyota TSM potvrdil Ondra.
    'snapshots' => [
        'subheading' => 'Případovky',
        'heading'    => 'Čtyři projekty zblízka',
        'desc'       => 'U každého je napsané, s čím klient přišel, co jsem postavil a co web umí. Kde je web veřejný, vede odkaz na živou verzi.',
        'live_label' => 'Živý web',
        'items'      => [
            [
                'type'     => 'E-shop — motorky a náhradní díly',
                'domain'   => 'shop.pitarena.cz',
                'url'      => 'https://shop.pitarena.cz',
                'title'    => 'PitAréna',
                'summary'  => 'Klient prodává pitbike motorky YCF a náhradní díly. Potřeboval prodávat online — a u dílů je klíčové poskládat správný kus pro daný model a ročník. Postavil jsem e-shop s katalogem motorek i dílů, roztříděných podle modelů a skupin.',
                'outcomes' => [
                    'Košík a zákaznický účet.',
                    'Kategorie podle modelů (LITE 125, PILOT 125, Factory 190) a skupin dílů — brzdy, motory, tlumiče, elektrika.',
                    'Filtrování podle modelu a ročníku.',
                    'Oblíbené položky a porovnání produktů.',
                    'Přehledná navigace v rozsáhlém sortimentu.',
                ],
            ],
            [
                'type'     => 'Prezentační web — hliníkové konstrukce',
                'domain'   => 'barana.cz',
                'url'      => 'https://barana.cz',
                'title'    => 'BARANA',
                'summary'  => 'Klient vyrábí hliníkové pergoly, brány a ploty na míru. Potřeboval web, který srozumitelně ukáže, co dělá, a ze kterého se lidé snadno ozvou. Postavil jsem prezentační web se službami, galerií realizací a poptávkovým formulářem.',
                'outcomes' => [
                    'Rozdělené služby — bioklimatické pergoly, brány a ploty, návrh na míru.',
                    'Galerie hotových realizací.',
                    'Poptávkový formulář a kontakt.',
                    'Sekce „Jak to probíhá".',
                    'Čistý přehledný design.',
                ],
            ],
            [
                'type'     => 'Prezentační web s online objednáním — stomatologie',
                'domain'   => 'zubniprovazek.cz',
                'url'      => 'https://zubniprovazek.cz',
                'title'    => 'Zubní ordinace Provázek',
                'summary'  => 'Klient provozuje zubní ordinaci pro dospělé i děti. Potřeboval web s informacemi o ordinaci a hlavně snadné online objednání. Postavil jsem prezentační web s online objednáním.',
                'outcomes' => [
                    'Online objednání.',
                    'Přehled služeb — prevence, dentální hygiena, bělení, záchovná péče, náhrady a implantáty, dětská stomatologie.',
                    'Ceník.',
                    'Sekce O nás, kontakt, otevírací doba a umístění.',
                    'Přehledný přátelský design.',
                ],
            ],
            [
                'type'     => 'Interní aplikace — logistika',
                'domain'   => null,
                'url'      => null,
                'title'    => 'Toyota — aplikace TSM',
                'summary'  => 'Webová aplikace na míru procesům logistiky, kterou jsem naprogramoval během osmnácti let v Toyotě. Nahradila zdlouhavou ruční práci a firmě přinesla úsporu v řádu milionů korun.',
                'outcomes' => [
                    'Aplikace postavená na reálných procesech logistiky, ne obecný nástroj.',
                    'Nahrazení zdlouhavé ruční práce.',
                    'Úspora v řádu milionů korun.',
                    'Interní systém — není veřejně dostupný, proto bez odkazu.',
                ],
            ],
        ],
    ],

    'fit' => [
        'subheading'    => 'Rychlá kvalifikace',
        'heading'       => 'Má to smysl řešit teď?',
        'items'         => [
            'Váš web má návštěvnost, ale poptávky nepřicházejí konzistentně.',
            'Nabídka služeb je nejasná nebo se ztrácí v textu.',
            'Chybí jasný postup, co se stane po odeslání poptávky.',
            // OND-201 (nález 5.1): „nástroj, který vydělává" byl slib výsledku
            // za klienta — výdělek neovlivním sám.
            'Nechcete další „hezký web", ale nástroj postavený na tom, jak vaše firma funguje.',
        ],
        'cta_heading'   => 'Pokud sedí 2 a více bodů, má smysl to řešit.',
        'cta_text'      => 'Během úvodní konzultace najdeme nejkratší cestu k funkčnímu řešení bez zbytečných funkcí navíc.',
        'cta_primary'   => 'Domluvit konzultaci',
        'cta_secondary' => 'Nejdřív ceník',
    ],

    'empty'            => 'Momentálně nejsou k dispozici žádné projekty.',
    'view_project'     => 'Zobrazit projekt',
    'back_to_projects' => '← Zpět na projekty',

    'before_after' => 'Porovnání před a po',
    'before'       => 'Před',
    'after'        => 'Po',
    'screenshots'  => 'Ukázky z projektu',

    'detail' => [
        'challenge'       => 'Výzva',
        'solution'        => 'Řešení',
        'result'          => 'Výsledek',
        'no_content'      => 'K tomuto projektu zatím není dostupný podrobný popis.',
        'related_heading' => 'Další projekty',
        'visit_live'      => 'Navštívit web',
        'meta'            => [
            'client'   => 'Klient',
            'year'     => 'Rok',
            'duration' => 'Doba realizace',
            'category' => 'Kategorie',
            'live_url' => 'Web',
            'tags'     => 'Technologie',
        ],
        'category_label'  => [
            'website'     => 'Webová stránka',
            'application' => 'Webová aplikace',
            'other'       => 'Ostatní',
        ],
    ],

    'cta' => [
        'heading' => 'Chcete podobný výsledek pro váš byznys?',
        'primary' => 'Domluvit konzultaci',
    ],

];
