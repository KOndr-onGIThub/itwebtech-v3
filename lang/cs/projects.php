<?php

return [

    'meta' => [
        'title'       => 'Projekty — Ondřej Kriška',
        'description' => 'Ukázky realizovaných projektů — webové stránky, webové aplikace. Nechte se inspirovat a představte si svůj úspěšný projekt.',
    ],

    'subheading'       => 'Realizované',
    'heading'          => 'PROJEKTY',
    'intro'            => 'Nechte se inspirovat ukázkami mé práce a představte si, jak bude vypadat váš úspěšný projekt. Pracuji tak, aby se ke mně klienti rádi vraceli.',

    // OND-135 P2 iter 6 — plán §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-
    // portfolio artefakt per CEO PR #78/#80/#82 precedent (home/kontakt/cenik).
    'hero' => [
        'page_mark_label' => 'REALIZACE',
        'upline'          => 'Hotové projekty, hotová čísla.',
        'heading_html'    => 'Případy, ne<br><em>portfolio galerie</em>.',
        'subline'         => 'Každý projekt s konkrétním výsledkem — termín, rozsah, dopad. Žádný screenshot bez čísla.',
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
            ['title' => 'Stabilita a robustnost', 'description' => 'Nestavím web ze cizích doplňků, které se rozbijí při první aktualizaci. Píšu vlastní kód, který drží.'],
            ['title' => 'Důkladné testování',     'description' => 'Nenechávám nic náhodě. Aplikace i webové stránky testuji v průběhu vývoje i po jeho dokončení.'],
            ['title' => 'Rychlost a design',      'description' => 'Prioritou je rychlé načítání a moderní design, což zajišťuje pozitivní první dojem a příjemnou uživatelskou zkušenost.'],
            ['title' => 'Řešení na míru',         'description' => 'Každý projekt je pro mě unikátní a vždy hledám nejlepší řešení přizpůsobené potřebám a cílům každého klienta.'],
            ['title' => 'Důraz na detail',        'description' => 'Vždy věnuji velkou pozornost detailům, které mohou být rozhodující pro úspěch vašeho projektu.'],
        ],
    ],

    'cta_all' => 'Podívejte se na mé další projekty',

    'snapshots' => [
        'subheading' => 'Co už funguje',
        'heading'    => 'Ukázky výsledků z podobných zakázek',
        'desc'       => 'Reálné scénáře, kde jsme odstranili překážky v poptávce a zjednodušili cestu návštěvníka ke kontaktu.',
        'items'      => [
            [
                'type'     => 'Firemní web',
                'timeline' => '4 týdny',
                'title'    => 'Nový web místo nečitelné prezentace',
                'summary'  => 'Původní web působil zastarale, byl pomalý a bez jasného CTA. Nová struktura vedla uživatele přímo na poptávku.',
                'outcomes' => [
                    'Jasná nabídka služeb hned v prvním scrollu.',
                    'Přechod na kontaktní krok bez zbytečných odboček.',
                    'Vyšší důvěryhodnost díky konzistentnímu obsahu.',
                ],
            ],
            [
                'type'     => 'Webová aplikace',
                'timeline' => '7 týdnů',
                'title'    => 'Klientský proces bez ruční administrativy',
                'summary'  => 'Byznys brzdila komunikace přes e-maily a tabulky. Přesun workflow do aplikace snížil chybovost a zrychlil reakce.',
                'outcomes' => [
                    'Zkrácení reakční doby díky centralizovaným datům.',
                    'Méně manuálních kroků a nižší provozní zátěž.',
                    'Lepší kontrola nad stavem zakázek v reálném čase.',
                ],
            ],
            [
                'type'     => 'E-shop na míru',
                'timeline' => '6 týdnů',
                'title'    => 'E-shop, který opravdu patří vám',
                'summary'  => 'Předchozí e-shop byl závislý na cizí platformě, která si pravidelně účtovala opravy. Nový má majitel plně pod kontrolou.',
                'outcomes' => [
                    'Odstranění opakovaných výpadků po aktualizacích.',
                    'Jasně řízený checkout bez rušivých prvků.',
                    'Technické SEO připravené od spuštění projektu.',
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
            'Nechcete další “hezký web”, ale nástroj, který vydělává.',
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
