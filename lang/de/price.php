<?php

return [

    'meta' => [
        'title'       => 'Preise — Ondřej Kriška',
        'description' => 'Unverbindliche Preise für Webseiten, Online-Shops und Webanwendungen. Klare Vorstellung Ihrer Investition vor dem ersten Gespräch.',
    ],

    'subheading' => 'Unverbindliche Preise',
    'heading'    => 'Sie wissen, worauf Sie sich einlassen — schon vor unserem ersten Gespräch.',
    'intro'      => 'Jedes Projekt ist einzigartig — den genauen Preis erfahren Sie nach einer kostenlosen Beratung. Diese Übersicht gibt Ihnen schon vor dem ersten Gespräch eine klare Vorstellung, was es kosten wird.',

    'popular'   => 'Beliebteste Wahl',
    'quotation' => 'Angebot anfragen',

    'price_note' => 'unverbindlicher Preis',

    // OND-136: Tier-Namen und Preise angeglichen an die CS-Taxonomie
    // Startovní/Standard/Custom = 25/55/95 Tausend CZK → EUR-Umrechnung.
    // Vorgeschlagene EUR-Werte vorbehaltlich Bestätigung durch Ondřej/CEO.
    'tiers' => [
        [
            'name'    => 'Starter',
            'desc'    => 'Für Selbstständige und kleine Unternehmen, die eine glaubwürdige Online-Präsenz benötigen.',
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
                'text'  => 'Ich antworte innerhalb von 24 Stunden, auch noch Wochen und Monate nach der Projektübergabe. Kleine Anpassungen und technische Fragen sind immer willkommen.',
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
        'tiers'   => ['Starter', 'Standard', 'Custom'],
        'groups'  => [
            [
                'label' => 'Projektumfang',
                'rows'  => [
                    ['label' => 'Anzahl der Seiten', 'values' => ['bis zu 5', 'bis zu 12', 'unbegrenzt']],
                    ['label' => 'Responsives Design', 'values' => [true, true, true]],
                    ['label' => 'Kontaktformular', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Webseiten-Funktionen',
                'rows'  => [
                    ['label' => 'Blog oder Galerie mit Bearbeitung', 'values' => [false, true, true]],
                    ['label' => 'Mehrsprachige Website', 'values' => [false, true, true]],
                    ['label' => 'Buchungssystem', 'values' => [false, 'optional', true]],
                    ['label' => 'Online-Shop', 'values' => [false, false, true]],
                    ['label' => 'Eigene Verwaltung', 'values' => [false, false, true]],
                    ['label' => 'Integration externer Systeme', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Marketing & Leistung',
                'rows'  => [
                    ['label' => 'Technische SEO', 'values' => [true, true, true]],
                    ['label' => 'Seitengeschwindigkeits-Optimierung', 'values' => [true, true, true]],
                    ['label' => 'Analytics & Konversionsmessung', 'values' => [false, true, true]],
                    ['label' => 'Erweiterte SEO-Strategie', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Service & Support',
                'rows'  => [
                    ['label' => 'Kostenloses Hosting und Domain', 'values' => [false, '1 Jahr', '1 Jahr']],
                    ['label' => 'Support nach dem Launch', 'values' => ['14 Tage', '1 Monat', '3 Monate']],
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
