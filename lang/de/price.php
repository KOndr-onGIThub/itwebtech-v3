<?php

return [

    'meta' => [
        'title'       => 'Preise — Ondřej Kriška, ONDRAWEB',
        'description' => 'Unverbindliche Preise für Websites, Online-Shops und Webanwendungen. Klare Vorstellung Ihrer Investition vor dem ersten Gespräch.',
    ],

    'subheading' => 'Unverbindliche Preise',
    'heading'    => 'Sie wissen, worauf Sie sich einlassen — schon vor unserem ersten Gespräch.',
    // OND-198 (Befund 5.4): der Erwartungssatz muss vor der ersten Zahl stehen.
    // OND-354: Untergrenze plus Spanne statt Menü aus drei Paketen (Ondřej,
    // 26. 9. 2026 auf OND-347); der Ablehnungssatz ist damit weg. Die
    // CZK-Untergrenze (20.000) wird absichtlich NICHT umgerechnet — siehe
    // lang/de/home.php.
    'intro'      => 'Die meisten Projekte liegen zwischen 3.500 und 8.000 €. Das Kleinste, was ich baue, ist eine einfache Präsentationswebsite ab 1.900 €. Was auf der Website steht und was sie kostet, erhalten Sie schriftlich vor Arbeitsbeginn — und diese Zahl steht auch auf der Rechnung.',

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
    // OND-391: nevykresluje se (plovoucí tlačítko z /cenik odešlo, OND-393).
    'sticky_cta' => [
        'label' => 'Stufe wählen',
        'cta'   => 'Anfrage schreiben',
    ],

    'popular'   => 'Beliebteste Wahl',
    // OND-391: `quotation` se nevykresluje (tlačítka u doplňků odešla, OND-393).
    'quotation' => 'Anfrage',

    // OND-359: Einleitung des Case-Study-Links in der Stufenkarte. Die
    // Bezeichnung ist dieselbe wie in `projects.snapshots.subheading`, damit
    // klar ist, wo der Klick landet.
    'proof_intro' => 'Case Study',

    // OND-354: Die Stufen heißen nach dem UMFANG, nicht nach einer Preisstufe,
    // und tragen keinen Preis — der Schlüssel `price` ist weg (und mit ihm
    // `price_note`, das ohne Zahl nichts zu beschreiben hätte). `key` ist eine
    // technische ID für Analytics (Dimension `pricing_tier_shown`, früher aus
    // den Preisziffern abgeleitet) und wird nie ausgegeben. Namen und `desc`
    // sind identisch mit dem Startseiten-Anker (`home.price_anchor.items`),
    // die Feature-Listen sind unverändert.
    //
    // OND-359: `proof` ist ein Link auf eine echte Case Study — der Preis über
    // ein Beispiel erklärt statt über eine Feature-Liste (Auftrag im Dokument
    // zu [OND-347], Abschnitt 4.3). `slug` ist der sprachneutrale
    // `portfolio_projects.slug`; keines der drei Projekte hat einen
    // lokalisierten Slug, die Adresse ist also in allen Sprachen gleich und
    // `detailUrl()` setzt den Locale-Prefix davor (/projekty, /en/projects,
    // /de/projekte). `label` trägt den Titel, der auch in der Case Study steht.
    'tiers' => [
        [
            'key'     => 'presentation',
            'name'    => 'Präsentationswebsite',
            'scope'   => 'Damit Kunden Sie prüfen können',
            'desc'    => 'Wer Sie sind, was Sie tun, wie man Sie erreicht',
            'popular' => false,
            'features' => [
                'Wer Sie sind, was Sie tun und wie man Sie erreicht, individuell umgesetzt',
                'Modernes responsives Design',
                'Kontaktformular',
                'Technische SEO',
                'Seitengeschwindigkeits-Optimierung',
                '14 Tage Support nach dem Launch',
            ],
            'proof' => [
                'slug'  => 'kemp-veselka',
                'label' => 'Autokemp Veselka',
            ],
            'cta' => 'Anfrage schreiben',
        ],
        [
            'key'     => 'business',
            'name'    => 'Firmenwebsite',
            'scope'   => 'Damit Kunden verstehen, warum gerade Sie',
            'desc'    => 'Mehr Leistungen, mehr Sprachen, Referenzen und Blog',
            'popular' => true,
            'features' => [
                'Eine Struktur, die darauf aufbaut, wonach Ihre Kunden suchen',
                'Konversionsorientiertes Design',
                'Blog oder Galerie mit Inhaltsverwaltung',
                'Mehrsprachige Website',
                'Analytics und Konversionsmessung',
                'Hosting und Domain für 1 Jahr kostenlos',
                '1 Monat Support nach dem Launch',
            ],
            'proof' => [
                'slug'  => 'zubni-provazek',
                'label' => 'Zubní Provázek',
            ],
            'cta' => 'Anfrage schreiben',
        ],
        [
            'key'     => 'custom',
            'name'    => 'Online-Shops und Anwendungen',
            'scope'   => 'Damit das System für Sie arbeitet',
            'desc'    => 'Online-Shop, Buchungen, Anbindung an Ihre Systeme',
            'popular' => false,
            'features' => [
                'Umfang danach, was das System können muss',
                'Online-Shop oder Buchungssystem',
                'Eigenes Verwaltungsinterface',
                'Erweiterte SEO-Strategie mit Reporting',
                'Integration externer Systeme',
                '3 Monate Support nach dem Launch',
            ],
            'proof' => [
                'slug'  => 'pitarena-eshop',
                'label' => 'PitArena — Onlineshop',
            ],
            'cta' => 'Anfrage schreiben',
        ],
    ],

    // OND-448 (B-08): Satz direkt unter den Stufen (nur /cenik, nicht auf der Startseite).
    // Die Seitenzahl wird nirgends als Preis- oder Stufengrenze genannt.
    'pages_note' => 'Den Preis bestimmt nicht die Seitenzahl, sondern wie viel die Website erklären und können muss. Den genauen Umfang und Preis haben Sie schriftlich in der Spezifikation.',

    // OND-354: ersetzt das entschuldigende „Eine Ausnahme, kein
    // Standard-Einstieg." auf der niedrigsten Stufe.
    'entry_note' => 'Für 1.900 € baue ich Ihnen eine einfache Präsentationswebsite. Sie wird schnell sein, auf dem Handy sauber funktionieren, und kein Link zum Anfrageformular wird ins Leere führen. Erwarten Sie nicht, dass sie von allein Aufträge bringt — dafür braucht es mehr Arbeit, als der kleinste Umfang zulässt. Aber sie wird ordentlich gemacht.',

    'note' => 'Ich bin nicht umsatzsteuerpflichtig — die genannten Preise sind Endpreise, es kommt keine Mehrwertsteuer hinzu.',

    'guarantees' => [
        'heading' => 'Was in jedem Projekt enthalten ist',
        'items'   => [
            // OND-448 (B-08): Die finalen Texte schreibt immer Ondřej aus den Antworten des Kunden.
            [
                'title' => 'Die Texte schreibe ich',
                'text'  => 'Sie müssen nichts schreiben. Ich frage Sie nach dem Wesentlichen und schreibe aus Ihren Antworten die Texte der ganzen Website. Anregungen, etwa für die Seite über Sie, sind willkommen.',
            ],
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
                'price' => 'ab 490 € / Monat',
                'desc'  => 'Keyword-Analyse, Content-Strategie, Leistungsüberwachung. Organische Sichtbarkeit, die auch ohne Werbebudget funktioniert.',
            ],
            [
                'name'  => 'Social-Media-Management',
                'price' => 'ab 590 € / Monat',
                'desc'  => 'Content-Erstellung, Planung und Veröffentlichung. Konsistente Präsenz, die das Vertrauen der Kunden aufbaut.',
            ],
            [
                'name'  => 'Individuelle Webanwendung',
                'price' => 'individuelles Angebot',
                'desc'  => 'Lagersysteme, interne Tools, Kundenportale. Der Preis richtet sich nach Komplexität und Umfang des Projekts.',
            ],
            [
                'name'  => 'Grafikdesign & Branding',
                'price' => 'ab 890 €',
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
                'Mehr Leistungen oder Produkte, die verständlich erklärt werden müssen',
                'Eine zweite und weitere Sprachen',
                'Ein Online-Shop, Buchungen oder Online-Zahlungen',
                'Eine eigene Inhaltsverwaltung',
                'Anbindung an Systeme, die Sie bereits nutzen',
                'Fotos, die erst gemacht oder gekauft werden müssen',
            ],
        ],
        'down' => [
            'label' => 'Senkt den Preis',
            'items' => [
                'Vollständige und schnelle Antworten auf meine Fragen',
                'Eine Person auf Ihrer Seite, die entscheidet',
                'Fotos, die Sie bereits in guter Qualität haben',
                'Eine Sprache',
                'Sie pflegen die Inhalte nach einer Einführung selbst',
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Nicht sicher, was Sie brauchen?',
        // OND-369: `desc` ist der Untertitel derselben Handlungsaufforderung
        // wie der Button, keine Prosa an anderer Stelle — „kostenlos“ fällt
        // also mit dem Label weg. Die Preisliste (OND-354) baut auf einer
        // Schwelle auf.
        // OND-448 (B-02): Der erste Schritt ist ein Erstgespräch (ca. 15 Minuten),
        // keine „Beratung“; nirgends ein kostenloses oder unverbindliches Angebot.
        'desc'    => 'Ein kurzes Erstgespräch genügt, etwa 15 Minuten. Ich kläre, was für Ihr Unternehmen sinnvoll ist, und sage Ihnen ehrlich auch, wenn eine Zusammenarbeit keinen Sinn ergibt.',
        'btn'     => 'Anfrage schreiben',
    ],

];
