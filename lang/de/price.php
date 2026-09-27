<?php

return [

    'meta' => [
        'title'       => 'Preise — Ondřej Kriška',
        'description' => 'Unverbindliche Preise für Websites, Online-Shops und Webanwendungen. Klare Vorstellung Ihrer Investition vor dem ersten Gespräch.',
    ],

    'subheading' => 'Unverbindliche Preise',
    'heading'    => 'Sie wissen, worauf Sie sich einlassen — schon vor unserem ersten Gespräch.',
    // OND-198 (Befund 5.4): der Erwartungssatz muss vor der ersten Zahl stehen.
    // OND-354: Untergrenze plus Spanne statt Menü aus drei Paketen (Ondřej,
    // 26. 9. 2026 auf OND-347); der Ablehnungssatz ist damit weg. Die
    // CZK-Untergrenze (20.000) wird absichtlich NICHT umgerechnet — siehe
    // lang/de/home.php.
    'intro'      => 'Die meisten Projekte liegen zwischen 2.200 und 6.000 €. Das Kleinste, was ich baue, ist eine Präsentationswebsite mit bis zu fünf Seiten. Was auf der Website steht und was sie kostet, erhalten Sie schriftlich vor Arbeitsbeginn — und diese Zahl steht auch auf der Rechnung.',

    // OND-135 P2 iter 5 — Plan §3.1 Hero (Page-Mark + Amber-Akzent).
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt — Agency-
    // Portfolio-Artefakt per CEO PR #78 Präzedenzfall (Home / Kontakt).
    'hero' => [
        'page_mark_label' => 'PREISE',
        'upline'          => 'Kein „Auf Anfrage“-Versteckspiel.',
        // OND-354: „Drei Stufen, ein klarer Preis" stimmt nicht mehr — nach
        // dieser Änderung gibt es keine Preisstufen.
        'heading_html'    => 'Was eine individuelle<br><em>Website kostet</em>.',
        'subline'         => 'Die Rechnung entspricht dem Angebot. Keine Mehrkosten ohne Ihr Wissen.',
    ],

    // Sticky CTA — durchgehend sichtbar, „der Preis verschwindet nie".
    'sticky_cta' => [
        'label' => 'Stufe wählen',
        'cta'   => 'Unverbindliches Angebot anfordern',
    ],

    'popular'   => 'Beliebteste Wahl',
    'quotation' => 'Angebot anfragen',

    // OND-354: Die Stufen heißen nach dem UMFANG, nicht nach einer Preisstufe,
    // und tragen keinen Preis — der Schlüssel `price` ist weg (und mit ihm
    // `price_note`, das ohne Zahl nichts zu beschreiben hätte). `key` ist eine
    // technische ID für Analytics (Dimension `pricing_tier_shown`, früher aus
    // den Preisziffern abgeleitet) und wird nie ausgegeben. Namen und `desc`
    // sind identisch mit dem Startseiten-Anker (`home.price_anchor.items`),
    // die Feature-Listen sind unverändert.
    'tiers' => [
        [
            'key'     => 'presentation',
            'name'    => 'Präsentationswebsite',
            'scope'   => 'bis 5 Seiten',
            'desc'    => 'Ein glaubwürdiger Online-Auftritt für Selbstständige und kleine Unternehmen.',
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
            'key'     => 'business',
            'name'    => 'Firmenwebsite',
            'scope'   => 'bis 12 Seiten',
            'desc'    => 'Eine mehrsprachige Website mit Blog, Konversionsmessung und Buchungssystem.',
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
            'key'     => 'custom',
            'name'    => 'Individuell',
            'scope'   => 'ohne Umfangsgrenze',
            'desc'    => 'Ein Online-Shop, eine Webanwendung oder ein komplexes Portal.',
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

    // OND-354: ersetzt das entschuldigende „Eine Ausnahme, kein
    // Standard-Einstieg." auf der niedrigsten Stufe.
    'entry_note' => 'Die kleinste Website, die ich baue, hat bis zu fünf Seiten. Sie wird schnell sein, auf dem Handy sauber funktionieren, und kein Link zum Anfrageformular wird ins Leere führen. Erwarten Sie nicht, dass sie von allein Aufträge bringt — dafür braucht es mehr Arbeit, als der kleinste Umfang zulässt. Aber sie wird ordentlich gemacht.',

    'note' => 'Ich bin nicht umsatzsteuerpflichtig — die genannten Preise sind Endpreise, es kommt keine Mehrwertsteuer hinzu.',

    'guarantees' => [
        'heading' => 'Was in jedem Projekt enthalten ist',
        'items'   => [
            [
                'title' => 'Wartungsfreie Websites',
                'text'  => 'Kein WordPress, keine Drittanbieter-Plugins. Sparen Sie jedes Jahr mehrere hundert Euro gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.',
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
                'text'  => 'Auch Wochen und Monate nach der Projektübergabe melde ich mich spätestens am nächsten Arbeitstag. Kleine Anpassungen und technische Fragen sind immer willkommen.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Zusätzliche Dienstleistungen',
        'desc'    => 'Umfassende digitale Unterstützung auch nach dem Projektstart.',
        'items'   => [
            [
                'name'  => 'SEO & Content-Marketing',
                'price' => 'ab 180 € / Monat',
                'desc'  => 'Keyword-Analyse, Content-Strategie, Leistungsüberwachung. Organische Sichtbarkeit, die auch ohne Werbebudget funktioniert.',
            ],
            [
                'name'  => 'Social-Media-Management',
                'price' => 'ab 400 € / Monat',
                'desc'  => 'Content-Erstellung, Planung und Veröffentlichung. Konsistente Präsenz, die das Vertrauen der Kunden aufbaut.',
            ],
            [
                'name'  => 'Individuelle Webanwendung',
                'price' => 'individuelles Angebot',
                'desc'  => 'Lagersysteme, interne Tools, Kundenportale. Der Preis richtet sich nach Komplexität und Umfang des Projekts.',
            ],
            [
                'name'  => 'Grafikdesign & Branding',
                'price' => 'ab 190 €',
                'desc'  => 'Logo, visuelle Identität, Banner. Alles, was Sie für eine konsistente und einprägsame Markenpräsentation benötigen.',
            ],
        ],
    ],

    // OND-354: Die Achse der Tabelle ändert sich — früher verglich sie drei
    // benannte Preisstufen, die es nicht mehr gibt. Begründung in
    // lang/cs/price.php.
    'compare' => [
        'heading' => 'Was den Preis erhöht und was ihn senkt',
        'up'   => [
            'label' => 'Erhöht den Preis',
            'items' => [
                'Mehr als fünf Seiten',
                'Eine zweite und weitere Sprachen',
                'Ein Online-Shop oder ein Buchungssystem',
                'Eine eigene Inhaltsverwaltung',
                'Anbindung an Systeme, die Sie bereits nutzen',
                'Texte und Fotos, die erst erstellt werden müssen',
            ],
        ],
        'down' => [
            'label' => 'Senkt den Preis',
            'items' => [
                'Texte und Fotos liegen bereit',
                'Weniger Seiten',
                'Eine Sprache',
                'Sie pflegen die Inhalte nach einer Einführung selbst',
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Nicht sicher, was Sie brauchen?',
        'desc'    => 'Die Beratung ist kostenlos und unverbindlich. In 30 Minuten sage ich Ihnen, was für Ihr Unternehmen sinnvoll ist — ehrlich, auch wenn das bedeutet, dass wir nicht zusammenarbeiten sollten.',
        'btn'     => 'Kostenlose Beratung buchen',
    ],

];
