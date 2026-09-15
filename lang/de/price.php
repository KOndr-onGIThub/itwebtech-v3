<?php

return [

    'meta' => [
        'title'       => 'Preise — Ondřej Kriška',
        'description' => 'Unverbindliche Preisbänder für Websites, Online-Shops und maßgeschneiderte Webanwendungen.',
    ],

    'subheading' => 'Unverbindliche Preise',
    'heading'    => 'Klare Preise für jedes Projekt',
    'intro'      => 'Jedes Projekt ist anders — den endgültigen Preis vereinbaren wir vorab. Diese Übersicht gibt Ihnen eine Vorstellung von den Preisbändern. Ich bin nicht der Günstigste und strebe das auch nicht an. Wenn Sie eine Website unter einem bestimmten Budget suchen, sage ich Ihnen direkt, dass ich wahrscheinlich nicht der Richtige bin.',

    'popular'   => 'Beliebteste Wahl',
    'quotation' => 'Unverbindliche Anfrage',

    'price_note' => 'unverbindlicher Preis',

    'tiers' => [
        [
            'name'    => 'Unternehmenswebsite',
            'desc'    => 'Für Einzelunternehmer und kleine Unternehmen, die eine glaubwürdige Online-Präsenz benötigen.',
            'price'   => '50.000–90.000 CZK',
            'popular' => false,
            'features' => [
                'Maßgeschneiderte Website, in der Regel bis zu 5 Seiten',
                'Design, das auf Ihr Unternehmen abgestimmt ist',
                'Sieht auf Mobilgeräten und Desktops gut aus',
                'Kontaktformular',
                'Technische SEO-Grundlagen',
                'Schnelles Laden',
                '14 Tage Support nach dem Launch',
            ],
            'cta' => 'Projekt anfragen',
        ],
        [
            'name'    => 'Website mit CMS',
            'desc'    => 'Für Unternehmen, die Inhalte selbst verwalten möchten oder eine mehrsprachige Website benötigen.',
            'price'   => '90.000–150.000 CZK',
            'popular' => true,
            'features' => [
                'Umfangreichere maßgeschneiderte Website',
                'Einfache Inhaltsverwaltung (Texte, Fotos, Produkte)',
                'Blog oder Galerie',
                'Mehrsprachige Website',
                'Analytics-Integration',
                'Hosting und Domain für 1 Jahr kostenlos',
                '1 Monat Support nach dem Launch',
            ],
            'cta' => 'Diesen Plan wählen',
        ],
        [
            'name'    => 'Online-Shop / Anwendung',
            'desc'    => 'Für anspruchsvollere Projekte — Online-Shop, Buchungssystem oder maßgeschneiderte interne Anwendung.',
            'price'   => 'ab 150.000 CZK',
            'popular' => false,
            'features' => [
                'Umfang je nach Projektanforderungen',
                'Online-Shop oder Buchungssystem',
                'Eigenes Verwaltungsinterface',
                'Maßgeschneiderte interne Anwendung für Ihren Betrieb',
                'Integration mit anderen Systemen, die Sie nutzen',
                '3 Monate Support nach dem Launch',
            ],
            'cta' => 'Projekt anfragen',
        ],
    ],

    'note' => 'Nicht umsatzsteuerpflichtig. Preise sind Endpreise.',

    'guarantees' => [
        'heading' => 'Was in jedem Projekt enthalten ist',
        'items'   => [
            [
                'title' => 'Wartungsfreie Websites',
                'text'  => 'Kein WordPress, keine Drittanbieter-Plugins. Keine Kosten für regelmäßige Updates und Sicherheits-Patches.',
            ],
            [
                'title' => 'Festpreis ohne Überraschungen',
                'text'  => 'Sie erhalten ein genaues Angebot vor Arbeitsbeginn. Was im Angebot steht, steht auf der Rechnung — keine zusätzlichen Kosten ohne Ihr Wissen.',
            ],
            [
                'title' => 'Direkte Kommunikation',
                'text'  => 'Sie sprechen direkt mit mir — keine Verkäufer, keine Projektmanager, keine Koordinatoren. Ein Ansprechpartner, eine Verantwortung.',
            ],
            [
                'title' => 'Support nach dem Launch',
                'text'  => 'Ich antworte an Werktagen, in der Regel innerhalb von zwei Arbeitstagen, auch noch Wochen und Monate nach der Projektübergabe. Kleine Anpassungen und technische Fragen sind immer willkommen.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Zusätzliche Dienstleistungen',
        'desc'    => 'Digitale Unterstützung auch nach dem Projektstart.',
        'items'   => [
            [
                'name'  => 'SEO & Inhalte',
                'price' => 'ab 4.500 CZK / Mo.',
                'desc'  => 'Pflege der Website-Performance in Suchmaschinen und regelmäßige Inhalte.',
            ],
            [
                'name'  => 'Social-Media-Management',
                'price' => 'ab 9.900 CZK / Mo.',
                'desc'  => 'Content-Erstellung, Planung und Veröffentlichung, damit das Unternehmen auch außerhalb der Website konsistent auftritt.',
            ],
            [
                'name'  => 'Individuelle Webanwendung',
                'price' => 'individuelles Angebot',
                'desc'  => 'Lagerverwaltung, interne Systeme, Kundenportale. Der Preis richtet sich nach dem Projektumfang.',
            ],
            [
                'name'  => 'Grafik & Branding',
                'price' => 'ab 4.800 CZK',
                'desc'  => 'Logo, visuelle Identität, Banner. Für eine kohärente und einprägsame Markenpräsentation.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'Was Sie genau bekommen',
        'tiers'   => ['Unternehmenswebsite', 'Website mit CMS', 'Online-Shop / Anwendung'],
        'groups'  => [
            [
                'label' => 'Projektumfang',
                'rows'  => [
                    ['label' => 'Anzahl der Seiten', 'values' => ['bis 5', 'mehr', 'unbegrenzt']],
                    ['label' => 'Maßgeschneidertes Design', 'values' => [true, true, true]],
                    ['label' => 'Kontaktformular', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Website-Funktionen',
                'rows'  => [
                    ['label' => 'Inhaltsverwaltung (Blog, Galerie)', 'values' => [false, true, true]],
                    ['label' => 'Mehrsprachige Website', 'values' => [false, true, true]],
                    ['label' => 'Buchungssystem', 'values' => [false, 'optional', true]],
                    ['label' => 'Online-Shop', 'values' => [false, false, true]],
                    ['label' => 'Eigene Verwaltung', 'values' => [false, false, true]],
                    ['label' => 'Integration anderer Systeme', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Suchmaschinen & Performance',
                'rows'  => [
                    ['label' => 'Technische SEO-Grundlagen', 'values' => [true, true, true]],
                    ['label' => 'Schnelles Laden', 'values' => [true, true, true]],
                    ['label' => 'Besuchermessung', 'values' => [false, true, true]],
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
        'heading' => 'Nicht sicher, was Sie genau brauchen?',
        'desc'    => 'Schreiben Sie mir und schildern Sie Ihr Anliegen. Ich melde mich zurück und sage Ihnen ehrlich, was für Ihr Unternehmen sinnvoll ist — auch ob eine Zusammenarbeit überhaupt Sinn ergibt.',
        'btn'     => 'Nachricht senden',
    ],

];
