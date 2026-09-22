<?php

return [

    'meta' => [
        'title'       => 'Was ich gebaut habe | ONDRAWEB',
        'description' => 'Webseiten, Onlineshops und Webanwendungen, die ich gebaut habe und die heute laufen. Bei jedem führt ein Link zur Live-Version, damit Sie es selbst prüfen können.',
    ],

    'subheading'       => 'Realisierte',
    'heading'          => 'PROJEKTE',
    // OND-201 (Punkt 5): Intro nach Abschnitt 4 des Dokuments texty-podstranky (OND-186).
    'intro'            => 'Hier finden Sie Webseiten, die ich gebaut habe und die heute laufen. Es sind keine Bilder in einer Galerie — jede lässt sich anklicken und live ansehen. Ich zeige lieber fertige Arbeit als Versprechen.',

    // OND-135 P2 iter 6 — Plan §3.1 Hero (Page-Mark + Amber-Akzent).
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt — Agency-
    // Portfolio-Artefakt per CEO PR #78/#80/#82 Präzedenzfall (Home/Kontakt/Preise).
    // OND-201 (Befund 5.2, KRITISCH): Der Hero versprach „harte Zahlen" und
    // „kein Screenshot ohne Zahl" — ein Versprechen, das die Seite zwei
    // Absätze weiter selbst brach, weil harte Zahlen nicht für alle Projekte
    // vorliegen. Umformuliert auf Nachweise, die wir belegen können: was die
    // Website kann, ein Live-Link, der Umfang. Keine erfundenen Zahlen.
    'hero' => [
        'page_mark_label' => 'PROJEKTE',
        'upline'          => 'Live-Webseiten, keine Bilder in einer Galerie.',
        'heading_html'    => 'Was ich<br><em>gebaut habe</em>.',
        'subline'         => 'Bei jedem Projekt steht, was die Website kann und in welchem Umfang ich sie gebaut habe. Der Link führt zur Live-Version — prüfen Sie es selbst.',
    ],

    'filter_all'       => 'Alle',
    'filter_websites'  => 'Webseiten',
    'filter_webapps'   => 'Anwendungen',
    'filter_other'     => 'Sonstiges',
    'filter_aria'      => 'Projekte nach Kategorie filtern',
    'count_label'      => 'Projekte angezeigt',

    'info_client'      => 'Kunde',
    'info_date'        => 'Datum',
    'info_categories'  => 'Kategorien',
    'info_price'       => 'Ungefährer Preis',

    'why_me' => [
        'subheading' => 'So arbeite ich',
        'heading'    => 'Das stecke ich in Projekte',
        'items'      => [
            ['title' => 'Expertise und Praxis',      'description' => 'Dank 18 Jahren Erfahrung bei Toyota habe ich einzigartige Erfahrung in der Prozessoptimierung und Webanwendungsentwicklung.'],
            ['title' => 'Stabilität und Robustheit', 'description' => 'Ich baue keine Webseiten aus fremden Add-ons, die beim ersten Update kaputtgehen. Ich schreibe eigenen Code, der hält.'],
            ['title' => 'Gründliches Testen',        'description' => 'Ich überlasse nichts dem Zufall. Ich teste Apps und Webseiten während der Entwicklung und danach.'],
            ['title' => 'Geschwindigkeit und Design','description' => 'Priorität hat schnelles Laden und modernes Design für einen positiven ersten Eindruck.'],
            ['title' => 'Maßgeschneiderte Lösungen', 'description' => 'Jedes Projekt ist für mich einzigartig und ich suche immer die beste Lösung für jeden Kunden.'],
            ['title' => 'Liebe zum Detail',          'description' => 'Ich achte immer sehr auf Details, die für den Erfolg Ihres Projekts entscheidend sein können.'],
        ],
    ],

    'cta_all' => 'Weitere Projekte ansehen',

    // OND-201 (Befund 5.2, KRITISCH): Der Abschnitt zeigte drei anonyme
    // „Ergebnis-Snapshots" mit erfundenen Terminen (4/6/7 Wochen) und nicht
    // belegbarer Wirkung. Ersetzt durch echte Case Studies aus dem Dokument
    // `pripadovky` (OND-186) — auf den Live-Seiten überprüft, keine
    // erfundenen Zahlen. Die Zustimmung zur Toyota-TSM-Einsparung liegt vor.
    'snapshots' => [
        'subheading' => 'Case Studies',
        'heading'    => 'Vier Projekte aus der Nähe',
        'desc'       => 'Bei jedem steht, womit der Kunde kam, was ich gebaut habe und was die Website kann. Wo die Website öffentlich ist, führt der Link zur Live-Version.',
        'live_label' => 'Live-Website',
        'items'      => [
            [
                'type'     => 'Onlineshop — Motorräder und Ersatzteile',
                'domain'   => 'shop.pitarena.cz',
                'url'      => 'https://shop.pitarena.cz',
                'title'    => 'PitArena',
                'summary'  => 'Der Kunde verkauft YCF-Pitbikes und Ersatzteile. Er brauchte einen Online-Verkauf — und bei Teilen ist entscheidend, das richtige Stück für Modell und Baujahr zu finden. Ich habe einen Onlineshop mit Katalog für Motorräder und Teile gebaut, sortiert nach Modellen und Teilegruppen.',
                'outcomes' => [
                    'Warenkorb und Kundenkonto.',
                    'Kategorien nach Modell (LITE 125, PILOT 125, Factory 190) und Teilegruppe — Bremsen, Motoren, Federung, Elektrik.',
                    'Filter nach Modell und Baujahr.',
                    'Favoriten und Produktvergleich.',
                    'Übersichtliche Navigation bei großem Sortiment.',
                ],
            ],
            [
                'type'     => 'Präsentationswebsite — Aluminiumkonstruktionen',
                'domain'   => 'barana.cz',
                'url'      => 'https://barana.cz',
                'title'    => 'BARANA',
                'summary'  => 'Der Kunde fertigt Aluminium-Pergolen, Tore und Zäune nach Maß. Er brauchte eine Website, die verständlich zeigt, was er macht, und über die sich Interessenten leicht melden. Ich habe eine Präsentationswebsite mit Leistungen, Referenzgalerie und Anfrageformular gebaut.',
                'outcomes' => [
                    'Aufgeteilte Leistungen — bioklimatische Pergolen, Tore und Zäune, Planung nach Maß.',
                    'Galerie fertiger Projekte.',
                    'Anfrageformular und Kontakt.',
                    'Abschnitt „Wie es abläuft".',
                    'Klares, aufgeräumtes Design.',
                ],
            ],
            [
                'type'     => 'Präsentationswebsite mit Online-Buchung — Zahnmedizin',
                'domain'   => 'zubniprovazek.cz',
                'url'      => 'https://zubniprovazek.cz',
                'title'    => 'Zahnarztpraxis Provázek',
                'summary'  => 'Der Kunde führt eine Zahnarztpraxis für Erwachsene und Kinder. Er brauchte eine Website mit Informationen zur Praxis und vor allem eine einfache Online-Buchung. Ich habe eine Präsentationswebsite mit Online-Buchung gebaut.',
                'outcomes' => [
                    'Online-Buchung.',
                    'Übersicht der Leistungen — Prophylaxe, Dentalhygiene, Bleaching, Zahnerhaltung, Prothetik und Implantate, Kinderzahnheilkunde.',
                    'Preisliste.',
                    'Über uns, Kontakt, Öffnungszeiten und Anfahrt.',
                    'Übersichtliches, freundliches Design.',
                ],
            ],
            [
                'type'     => 'Interne Anwendung — Logistik',
                'domain'   => null,
                'url'      => null,
                'title'    => 'Toyota — die Anwendung TSM',
                'summary'  => 'Eine Webanwendung, gebaut auf die realen Logistikprozesse, die ich während meiner achtzehn Jahre bei Toyota programmiert habe. Sie ersetzte langwierige Handarbeit und brachte dem Unternehmen eine Einsparung in Millionenhöhe (in tschechischen Kronen).',
                'outcomes' => [
                    'Auf den realen Logistikprozess gebaut, kein generisches Werkzeug.',
                    'Ersatz langwieriger Handarbeit.',
                    'Einsparung in Millionenhöhe (CZK).',
                    'Internes System — nicht öffentlich zugänglich, daher ohne Link.',
                ],
            ],
        ],
    ],

    'fit' => [
        'subheading'    => 'Schnelle Einordnung',
        'heading'       => 'Lohnt sich die Umsetzung jetzt?',
        'items'         => [
            'Ihre Webseite hat Traffic, aber Anfragen kommen unregelmäßig.',
            'Ihr Angebot ist unklar oder im Inhalt versteckt.',
            'Es fehlt ein klarer Ablauf nach dem Absenden einer Anfrage.',
            // OND-201 (Befund 5.1): „Business-Tool" versprach ein Ergebnis,
            // für das ich allein nicht einstehen kann.
            'Sie wollen keine weitere „schöne Webseite", sondern etwas, das auf der realen Arbeitsweise Ihrer Firma aufbaut.',
        ],
        'cta_heading'   => 'Wenn 2+ Punkte passen, lohnt sich die Umsetzung jetzt.',
        'cta_text'      => 'Im Erstgespräch definieren wir den kürzesten Weg zu einer funktionierenden Lösung ohne unnötige Extras.',
        'cta_primary'   => 'Beratung buchen',
        'cta_secondary' => 'Zuerst Preise ansehen',
    ],

    'empty'            => 'Derzeit sind keine Projekte verfügbar.',
    'view_project'     => 'Projekt anzeigen',

    // OND-265: Alt-Text für Projektvorschauen in der Übersicht und in „Weitere Projekte".
    'card' => [
        'thumbnail_alt' => 'Vorschau des Projekts :project',
    ],
    'back_to_projects' => '← Zurück zu Projekten',

    'before_after' => 'Vorher-Nachher-Vergleich',
    'before'       => 'Vorher',
    'after'        => 'Nachher',
    'screenshots'  => 'Projektscreenshots',

    'detail' => [
        'challenge'       => 'Herausforderung',
        'solution'        => 'Lösung',
        'result'          => 'Ergebnis',
        'no_content'      => 'Eine ausführliche Beschreibung zu diesem Projekt ist noch nicht verfügbar.',
        'related_heading' => 'Weitere Projekte',
        'visit_live'      => 'Live-Seite besuchen',
        'meta'            => [
            'client'   => 'Kunde',
            'year'     => 'Jahr',
            'duration' => 'Dauer',
            'category' => 'Kategorie',
            'live_url' => 'Live-Seite',
            'tags'     => 'Technologien',
        ],
        'category_label'  => [
            'website'     => 'Webseite',
            'application' => 'Webanwendung',
            'other'       => 'Sonstiges',
        ],
    ],

    'cta' => [
        'heading' => 'Möchten Sie ein ähnliches Ergebnis für Ihr Unternehmen?',
        'primary' => 'Beratung buchen',
    ],

];
