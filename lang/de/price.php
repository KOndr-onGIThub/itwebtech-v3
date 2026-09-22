<?php

return [

    'meta' => [
        'title'       => 'Preise — Ondřej Kriška',
        'description' => 'Unverbindliche Preise für Webseiten, Online-Shops und Webanwendungen. Klare Vorstellung Ihrer Investition vor dem ersten Gespräch.',
    ],

    'subheading' => 'Unverbindliche Preise',
    'heading'    => 'Sie wissen, worauf Sie sich einlassen — schon vor unserem ersten Gespräch.',
    // OND-198 (Befund 5.4): der Erwartungssatz muss vor der ersten Zahl stehen.
    'intro'      => 'Die meisten Projekte, die ich baue, liegen zwischen 2.200 und 6.000 €. Wenn Sie eine Website unter 800 € suchen, bin ich nicht der richtige Anbieter für Sie — und das sage ich Ihnen gleich. Jedes Projekt ist einzigartig — den genauen Preis erfahren Sie nach einer kostenlosen Beratung.',

    // OND-135 P2 iter 5 — Plan §3.1 Hero (Page-Mark + Amber-Akzent).
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt — Agency-
    // Portfolio-Artefakt per CEO PR #78 Präzedenzfall (Home / Kontakt).
    'hero' => [
        'page_mark_label' => 'PREISE',
        'upline'          => 'Kein „Auf Anfrage"-Versteckspiel.',
        // OND-198 (Befund 5.4): Standard führt die Subline an, nicht die günstigste Stufe.
        'heading_html'    => 'Drei Stufen,<br>ein <em>klarer Preis</em>.',
        'subline'         => 'Die Rechnung entspricht dem Angebot. Keine Mehrkosten ohne Ihr Wissen.',
    ],

    // Sticky CTA — durchgehend sichtbar, „der Preis verschwindet nie".
    'sticky_cta' => [
        'label' => 'Stufe wählen',
        'cta'   => 'Unverbindliches Angebot anfordern',
    ],

    'popular'   => 'Beliebteste Wahl',
    'quotation' => 'Angebot anfragen',

    'price_note' => 'unverbindlicher Preis',

    // OND-136: Tier-Namen und Preise angeglichen an die CS-Taxonomie
    // Startovní/Standard/Custom = 25/55/95 Tausend CZK → EUR-Umrechnung (CEO-bestätigter 1:25-Anker).
    'tiers' => [
        [
            'name'    => 'Standard',
            'desc'    => 'Für Unternehmen, die ihre Webseite als bestes Verkaufswerkzeug nutzen möchten.',
            'price'   => '2.200 €',
            'popular' => true,
            'features' => [
                'Bis zu 12 individuelle Seiten',
                'Konversionsorientiertes Design',
                'Blog oder Galerie mit Inhaltsverwaltung',
                'Mehrsprachige Website',
                'Analytics und Konversionsmessung',
                'Hosting und Domain für 1 Jahr kostenlos',
                '1 Monat Support nach dem Launch',
            ],
            'cta' => 'Unverbindliches Angebot anfordern',
        ],
        [
            'name'    => 'Custom',
            'desc'    => 'Für anspruchsvolle Projekte ohne Kompromisse — Online-Shop, Buchungssystem oder Webanwendung.',
            'price'   => 'ab 3.800 €',
            'popular' => false,
            'features' => [
                'Unbegrenzter Projektumfang',
                'Online-Shop oder Buchungssystem',
                'Eigenes Verwaltungsinterface',
                'Erweiterte SEO-Strategie mit Reporting',
                'Integration externer Systeme',
                '3 Monate Support nach dem Launch',
            ],
            'cta' => 'Unverbindliches Angebot anfordern',
        ],
        [
            'name'    => 'Starter',
            // OND-198 (Befund 5.4): günstigste Stufe steht zuletzt, gerahmt als Ausnahme.
            'desc'    => 'Eine Ausnahme, kein Standard-Einstieg. Für Selbstständige, bei denen ein größerer Umfang keinen Sinn ergibt — glaubwürdige Online-Präsenz bis 5 Seiten.',
            'price'   => '1.000 €',
            'popular' => false,
            'features' => [
                'Bis zu 5 individuelle Seiten',
                'Modernes responsives Design',
                'Kontaktformular',
                'Technische SEO',
                'Seitengeschwindigkeits-Optimierung',
                '14 Tage Support nach dem Launch',
            ],
            'cta' => 'Unverbindliches Angebot anfordern',
        ],
    ],

    'note' => 'Kein Umsatzsteuerpflichtiger — die genannten Preise sind endgültig, es kommt nichts hinzu.',

    'guarantees' => [
        'heading' => 'Was in jedem Projekt enthalten ist',
        'items'   => [
            [
                'title' => 'Wartungsfreie Webseiten',
                'text'  => 'Kein WordPress, keine Drittanbieter-Plugins. Sparen Sie jährlich Tausende gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.',
            ],
            [
                'title' => 'Festpreis ohne Überraschungen',
                'text'  => 'Sie erhalten ein genaues Angebot vor Arbeitsbeginn. Was im Angebot steht, steht auf der Rechnung — keine zusätzlichen Kosten ohne Ihr Wissen.',
            ],
            [
                'title' => 'Direkte Kommunikation',
                'text'  => 'Sie sprechen direkt mit mir — keine Account-Manager, keine Projektkoordinatoren. Ein Ansprechpartner, eine Verantwortung.',
            ],
            [
                'title' => 'Support nach dem Launch',
                'text'  => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen, auch noch Wochen und Monate nach der Projektübergabe. Kleine Anpassungen und technische Fragen sind immer willkommen.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Zusätzliche Dienstleistungen',
        'desc'    => 'Umfassende digitale Unterstützung auch nach dem Projektstart.',
        'items'   => [
            [
                'name'  => 'SEO & Content-Marketing',
                'price' => 'ab €180 / Mo.',
                'desc'  => 'Keyword-Analyse, Content-Strategie, Leistungsüberwachung. Organische Sichtbarkeit, die auch ohne Werbebudget funktioniert.',
            ],
            [
                'name'  => 'Social-Media-Management',
                'price' => 'ab €400 / Mo.',
                'desc'  => 'Content-Erstellung, Planung und Veröffentlichung. Konsistente Präsenz, die das Vertrauen der Kunden aufbaut.',
            ],
            [
                'name'  => 'Individuelle Webanwendung',
                'price' => 'individuelles Angebot',
                'desc'  => 'Lagersysteme, interne Tools, Kundenportale. Der Preis richtet sich nach Komplexität und Umfang des Projekts.',
            ],
            [
                'name'  => 'Grafikdesign & Branding',
                'price' => 'ab €190',
                'desc'  => 'Logo, visuelle Identität, Banner. Alles, was Sie für eine konsistente und einprägsame Markenpräsentation benötigen.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'Was Sie genau bekommen',
        'tiers'   => ['Standard', 'Custom', 'Starter'],
        'tabs_aria'     => 'Preisstufe auswählen',
        'included'      => 'Enthalten',
        'not_included'  => 'Nicht enthalten',
        'groups'  => [
            [
                'label' => 'Projektumfang',
                'rows'  => [
                    ['label' => 'Anzahl der Seiten', 'values' => ['bis zu 12', 'unbegrenzt', 'bis zu 5']],
                    ['label' => 'Responsives Design', 'values' => [true, true, true]],
                    ['label' => 'Kontaktformular', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Webseiten-Funktionen',
                'rows'  => [
                    ['label' => 'Blog oder Galerie mit Bearbeitung', 'values' => [true, true, false]],
                    ['label' => 'Mehrsprachige Website', 'values' => [true, true, false]],
                    ['label' => 'Buchungssystem', 'values' => ['optional', true, false]],
                    ['label' => 'Online-Shop', 'values' => [false, true, false]],
                    ['label' => 'Eigene Verwaltung', 'values' => [false, true, false]],
                    ['label' => 'Integration externer Systeme', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Marketing & Leistung',
                'rows'  => [
                    ['label' => 'Technische SEO', 'values' => [true, true, true]],
                    ['label' => 'Seitengeschwindigkeits-Optimierung', 'values' => [true, true, true]],
                    ['label' => 'Analytics & Konversionsmessung', 'values' => [true, true, false]],
                    ['label' => 'Erweiterte SEO-Strategie', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Service & Support',
                'rows'  => [
                    ['label' => 'Kostenloses Hosting und Domain', 'values' => ['1 Jahr', '1 Jahr', false]],
                    ['label' => 'Support nach dem Launch', 'values' => ['1 Monat', '3 Monate', '14 Tage']],
                    ['label' => 'Wartungsfreier Betrieb', 'values' => [true, true, true]],
                ],
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Nicht sicher, was Sie brauchen?',
        'desc'    => 'Die Beratung ist kostenlos und unverbindlich. In 30 Minuten sage ich Ihnen, was für Ihr Unternehmen sinnvoll ist — ehrlich, auch wenn das bedeutet, dass wir nicht zusammenarbeiten sollten.',
        'btn'     => 'Kostenlose Beratung buchen',
    ],

];
