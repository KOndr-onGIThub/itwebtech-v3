<?php

return [

    // OND-201 (Befund 5.7): Der Seitentitel darf sich nicht über die Negation
    // der Konkurrenz definieren, und „kein WordPress" sagt jemandem nichts,
    // der nicht weiß, was WordPress ist (Prinzip 0).
    'meta' => [
        'title'       => 'Websites nach Maß, die tragen, was gut läuft | ONDRAWEB',
        'description' => 'Websites, Onlineshops und Webanwendungen nach Maß. Ich baue alles selbst mit eigenem Code, den Preis nenne ich vorab. 5,0 aus 21 Bewertungen.',
    ],

    'hero' => [
        // OND-127 P0 incident hotfix (2026-05-14) — Plagiat-Strings entfernt.
        // Placeholder copy aus meta description = pre-redesign safe copy.
        // FINAL COPY: Content Writer liefert in OND-136 P3 (SLA 2h von 11:10 UTC).
        'page_mark_label' => 'MASSGESCHNEIDERT',
        // OND-145 P0.3: page_mark_index entfernt — Agency-Portfolio-Pagination-
        // Artefakt, itwebtech hat im Hero-Kontext keine „pages" Hierarchie.
        // OND-198 (Befund 5.1): die alte Überschrift versprach das Geschäfts-
        // ergebnis des Kunden. Ersetzt durch den freigegebenen Hero-Text (CS = Quelle).
        // OND-310: DE folgt jetzt dem neu geschriebenen CS-Hero (OND-307 /
        // OND-308). Die alte Überschrift führte mit „eigenem Code" — ein
        // technischer Begriff an genau der Stelle, an der der Besucher
        // entscheidet, ob er überhaupt schreibt. Der eigene Code verschwindet
        // nicht, er rutscht in die Subline und in „Was ich baue".
        'upline'          => 'Für Unternehmen, die wachsen.',
        'heading_html'    => 'Eine Website, die <em>trägt</em>, was bei Ihnen gut läuft.',
        'subline'         => 'Ich bin Ondřej Kriška. Websites und Anwendungen baue ich mit eigenem Code, und ich mache die Arbeit selbst — vom ersten Gespräch bis zum Start sprechen Sie nur mit mir.',
        'note'            => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen. Unverbindlich besprechen wir, was sinnvoll ist.',

        // Backwards compat (fallback render).
        // OND-308: `cta_secondary` und `phone_label` entfernt — seit OND-303
        // gibt es keine Reservierungen, und die Telefonnummer zersplittert
        // die Entscheidung direkt unter dem Haupt-CTA.
        'eyebrow'       => 'Maßgeschneiderte Websites & Webanwendungen',
        'heading'       => 'Eine Website, die trägt, was bei Ihnen gut läuft.',
        'cta_primary'   => 'Schreiben Sie mir, was Sie brauchen',
    ],

    'anchors' => [
        'how_i_work' => 'wie-ich-arbeite',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        // OND-315: `rating_aria` beschreibt nur die Bewertung und steht darum
        // an dieser einen Zahl — im Streifen stehen auch Projekte, Jahre,
        // Antwortzeit und Auszeichnung. Landmark-Label: `strip_aria`.
        'rating_aria'  => 'Bewertung 5 von 5',
        'strip_aria'   => 'Zahlen zu meiner Arbeit',
        'clients_aria' => 'Kunden',
        'rating_value' => '5,0',
        'reviews'      => '(21 Bewertungen auf Google + Firmy.cz)',
        'projects'     => '23+ realisierte Projekte',
        'experience'   => '18 Jahre Erfahrung',
        'response'     => 'Antwort innerhalb von 24 Stunden an Arbeitstagen',
        // OND-201 (Befund 5.11): Auszeichnung TOP firma 2025 von Firmy.cz —
        // überprüfbarer Nachweis Dritter, der auf Staging fehlte.
        'award'        => 'TOP firma 2025 auf Firmy.cz',
        'brands' => [
            ['name' => 'MAKOplast',             'image' => null],
            ['name' => 'Yolk Studio',           'image' => 'yolk_studio.png'],
            ['name' => 'BARANA s.r.o.',         'image' => null],
            ['name' => 'Nové Interiéry s.r.o.', 'image' => null],
            ['name' => 'Zubní Provázek',        'image' => null],
            ['name' => 'Autokemp Veselka',      'image' => null],
            ['name' => 'PitAréna',              'image' => 'pitarena.png'],
        ],
    ],

    // OND-202: Arbeitsproben als primäres Bildmaterial (live Kunden-Websites).
    // OND-269 (Audit OND-254, Befund 7): Der Block `showcase` („Websites, die
    // in der Praxis laufen" — drei Kacheln mit Link zur Live-Website) ist
    // gestrichen. Zwei Projektsektionen sagten dasselbe, BARANA und PitArena
    // standen in beiden. Zusammengelegt in die eine Sektion `portfolio`
    // unten, die die Überschrift von hier übernommen hat und einen Link zur
    // Live-Website bekam (`live_cta` / `live_aria`).

    // OND-308: Der Block `problems` ist weg — die neue Startseite definiert
    // sich nicht mehr über die Negation der Konkurrenz. An seiner Stelle steht
    // ein Absatz zur Situation des Kunden. OND-310 liefert die deutsche
    // Fassung, damit der Absatz auch auf /de/ erscheint. Er beschreibt eine
    // Situation, keinen Schmerz — der Leser nickt mit. Kein Schüren von
    // Angst (Zadání, Kapitel 0.5).
    //
    // OND-320 (Variante G): Der Satz ist eine Aussage über Ondřej — wer ihm
    // schreibt — keine Behauptung über den Leser. Nicht in die zweite Person
    // zurückdrehen und kein Possessiv „Ihre Website": großgeschrieben liest
    // sich das als Anrede, also genau die Perspektive, die hier wegsollte.
    // Der Absatz muss einzeilig bleiben (`.pd-lead--wide`, 52ch): 70 Zeichen
    // passen, ab ~74 bricht die Zeile um.
    'situation' => [
        'text' => 'Meist schreiben mir Leute, denen es gut läuft — nur die Website nicht.',
    ],

    'how_i_work' => [
        'heading'   => 'In vier Schritten von der ersten Nachricht zur fertigen Website',
        'cta_intro' => 'Gleich zu Schritt 1.',
        'steps'   => [
            [
                'heading'      => 'Beratung',
                'time'         => '60 Min., binnen einer Woche',
                'text'         => 'Sie schreiben mir über das Formular unten, worum es geht. Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir verabreden ein Telefonat oder ein Treffen. Sie sprechen mit mir, nicht mit einem Vertriebler — mich interessiert, an wen Sie verkaufen, wie Anfragen bei Ihnen entstehen und was die Website leisten soll.',
                'quote_text'   => 'Er hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Spezifikation',
                'time'         => '2–5 Tage',
                'text'         => 'Sie bekommen es schriftlich: was auf der Website steht, wie viele Seiten sie hat und was sie kostet. Was in der Spezifikation steht, steht auf der Rechnung. Den Liefertermin schätze ich vorher ein, nicht hinterher.',
                'quote_text'   => 'Er analysiert die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll.',
                'quote_author' => 'Jan Stybor, Leiter der Projektabteilung, Toyota',
                'note'         => 'Der Termin ist eine Schätzung, keine Verpflichtung. Freigaben und Unterlagen von Ihrer Seite gehören zur Arbeit, und ich sage das gleich am Anfang.',
            ],
            [
                'heading' => 'Umsetzung',
                'time'    => '3–10 Wochen',
                'text'    => 'Ich schreibe eigenen Code, deshalb richtet sich die Website nach Ihrem Unternehmen und nicht nach einem fertigen Layout. Zwischendurch schicke ich Ansichten und frage bei den Entscheidungen nach, die sich lohnen, gemeinsam zu treffen. Am Ende erfahren Sie nicht erst, ob es passt — Sie wissen es die ganze Zeit.',
            ],
            [
                'heading' => 'Launch und Support',
                'time'    => 'bis zum nächsten Werktag',
                'text'    => 'Nach Ihrer Freigabe geht die Website in der Regel innerhalb eines Arbeitstages online. Danach gibt es daran nichts zu pflegen — sie hat keine Zusatzmodule, die monatliche Updates erzwingen, deshalb kommt in zwei Jahren keine Rechnung für die Reparatur von etwas, das von selbst kaputtgegangen ist. Kleine Änderungen und Fragen nach dem Start klären Sie direkt mit mir.',
                'note'    => 'Launch innerhalb von 1 Arbeitstag nach Freigabe.',
            ],
        ],
    ],

    // OND-308: `toyota` ist eine eigene Sektion und trägt das Zitat von
    // Pavel Baudyš.
    // OND-314: das Video ist von hier nach „Wie es abläuft" umgezogen.
    // OND-310: der deutsche Text folgt dem neu geschriebenen CS. Die alte
    // Fassung nannte abstrakte Prinzipien („Analyse, Design, Testen,
    // Verifizieren"), die neue sagt, was Ondřej dort wirklich getan hat.
    // Hier prüft der Besucher das Handwerk, deshalb spricht die Sektion
    // in Ondřejs Sprache.
    // OND-344: die Sektion steht jetzt an sechster Stelle, hinter „Was ich
    // baue" — und der Schlüssel `example` (das PitArena-Beispiel aus
    // OND-318) ist weg. Nach dem Umzug stand ein Stück Angebot mitten in
    // einer persönlichen Geschichte. Nicht ohne Entscheidung auf OND-344
    // wieder einführen.
    'toyota' => [
        'heading'      => '18 Jahre bei Toyota.',
        'text'         => 'Angefangen habe ich als Arbeiter in der Logistik, gegangen bin ich als leitender Spezialist im Projektteam. Achtzehn Jahre habe ich gesucht, wo in Produktion und Montage Zeit verloren geht, und dazu eine Firmenanwendung geschrieben, die Millionen Kronen gespart hat. In der Produktion können Sie sich nicht erlauben, dass etwas ausfällt. Dort habe ich gelernt: Software macht man richtig oder gar nicht.',
        'text_2'       => 'Websites baue ich genauso. Bevor ich die erste Zeile schreibe, will ich wissen, wie Anfragen bei Ihnen entstehen und was danach mit ihnen passiert. Erst danach entsteht die Seite. Sie merken es an der Spezifikation, die Sie bekommen, bevor ich anfange.',
        'quote_text'   => 'Eine der größten Stärken von Ondřej ist sein starker Wunsch, sich zu entwickeln — nicht nur die Bedürfnisse der Kunden zu erfüllen, sondern ihre Erwartungen zu übertreffen.',
        'quote_author' => 'Pavel Baudyš, Direktor Produktion, Montage & Logistik, Toyota Motor Manufacturing Czech Republic (2024)',
    ],

    // OND-269: die einzige Projektsektion der Startseite (früher `showcase`
    // + `portfolio`). Überschrift und Intro stammen aus dem gestrichenen
    // `showcase` — sie sprechen von laufenden Websites, und das ist für
    // Besucher konkreter.
    'portfolio' => [
        'heading'    => 'Websites, die in der Praxis laufen',
        'intro'      => 'Das sind Live-Projekte, die Sie sich sofort ansehen können. Bei jedem steht auch, was es dem Kunden gebracht hat.',
        'cta'        => 'Alle Projekte →',
        'detail_cta' => 'Projekt ansehen',
        'live_cta'   => 'Live-Website öffnen',
        'live_aria'  => 'Website von :client in neuem Fenster öffnen',
        'cards' => [
            'pitarena' => [
                'client'  => 'PitArena',
                'outcome' => 'Trainingsplätze sind Monate im Voraus ausgebucht — Buchungen, Gutscheine und Event-Anmeldungen laufen ohne manuellen Eingriff über das Web.',
            ],
            'barana' => [
                'client'  => 'BARANA',
                // OND-198 (Befund 5.5): Werbeplattform-Jargon in Kundensprache umgeschrieben.
                'outcome' => 'Eine eigenständige Seite für bezahlte Werbung — Besucher verstehen das Angebot ohne Anruf.',
            ],
            'nove-interiery' => [
                'client'  => 'Nové interiéry',
                'outcome' => 'Die Website filtert irrelevante Anfragen vorab und wirkt wie das erste Verkaufsgespräch — der Kunde bestätigt eine deutlich höhere Markenglaubwürdigkeit.',
            ],
        ],
    ],

    'services' => [
        'heading_primary'  => 'Was ich baue',
        // OND-310: der Hinweis auf eigenen Code wandert aus dem Hero hierher.
        'subheading'       => 'Ich schreibe eigenen Code. Ich verwende keine Vorlage, die Ihre Konkurrenz schon hat.',
        'heading_other'    => 'Weitere Services',
        'secondary_inline' => 'Ich biete auch SEO, Grafikdesign und Social-Media-Betreuung — :pricing_link oder :contact_link.',
        'secondary_inline_pricing' => 'mehr in der Preisliste',
        'secondary_inline_contact' => 'schreiben Sie mir',
        // OND-136: angeglichen an die CS-Taxonomie 25/55/95 Tausend CZK → EUR-Umrechnung (CEO-bestätigter 1:25-Anker).
        'primary' => [
            'weby' => [
                'title'       => 'Maßgeschneiderte Websites',
                // OND-198 (Befund 5.1): „und Kunden bringt" war ein Versprechen
                // des Kundenergebnisses — ersetzt durch das, wofür ich einstehe.
                'description' => 'Eine Website, die erklärt, was Sie tun und warum man sich für Sie entscheidet. Sie richtet sich nach Ihrem Unternehmen, nicht nach einem fertigen Layout.',
                // OND-310 (Zadání, Aufgabe 3.9): eine Aufzählung ist jetzt ein
                // Paar — zuerst der Satz des Kunden, darunter der technische
                // Zusatz.
                'bullets'     => [
                    ['Die gleiche Website finden Sie nicht eine Straße weiter.', 'Ich schreibe eigenen Code und verwende keine Vorlagen.'],
                    ['Die Seiten kommen in der Reihenfolge, in der Ihr Kunde wirklich entscheidet.', 'Die Struktur entwerfe ich danach, wie Anfragen bei Ihnen entstehen.'],
                    ['In zwei Jahren kommt keine Rechnung für die Reparatur von etwas, das von selbst kaputtgegangen ist.', 'Die Website läuft nicht auf Zusatzmodulen, die monatliche Updates erzwingen.'],
                ],
            ],
            'aplikace' => [
                'title'       => 'Webanwendungen',
                'description' => 'Interne Systeme, Kundenportale und Verwaltungstools — zugeschnitten auf die Art, wie Ihr Betrieb tatsächlich arbeitet.',
                'bullets'     => [
                    ['Bevor ich zu schreiben anfange, gehen wir durch, wie es bei Ihnen heute läuft.', 'Der Prozessentwurf entsteht vor der ersten Zeile Code.'],
                    ['Aufträge gibt die Website selbst dorthin weiter, wo Sie sie schon erfassen. Niemand tippt etwas ab.', 'Ich verbinde sie mit den Werkzeugen, die Sie nutzen.'],
                    ['Die Verwaltung gehört Ihnen und Sie zahlen nicht jeden Monat dafür.', 'Keine Lizenzen pro Nutzer und keine pro Datensatz.'],
                ],
            ],
            'eshop' => [
                'title'       => 'Online-Shops',
                'description' => 'Ein Online-Shop, der auf Ihrem Sortiment aufbaut und darauf, wie Sie es verkaufen.',
                'bullets'     => [
                    ['Kasse und Katalog passen zu dem, was Sie wirklich verkaufen.', 'Ich entwerfe sie nach Ihrem Sortiment, nicht nach einer Vorlage.'],
                    ['Jeden Auftrag gibt die Website selbst an die Buchhaltung, an den Versanddienstleister und an das Bezahlsystem weiter.', 'Die Anbindungen erledige ich beim Bauen, nicht nach dem Start.'],
                    ['Niemand kassiert Miete dafür, dass Ihr Shop überhaupt online ist.', 'Keine monatlichen Gebühren für eine Plattform oder für Zusatzmodule.'],
                ],
            ],
        ],
        'seo' => [
            'title'       => 'Kunden aus Google — ohne Bezahlung pro Klick',
            'description' => 'Bezahlte Werbung funktioniert nur, solange Sie zahlen. SEO arbeitet langfristig für Sie. Ich helfe Ihnen so, dass Kunden Sie kostenlos in Google finden — auch wenn Sie gerade kein Werbebudget haben.',
        ],
        'design' => [
            'title'       => 'Visuelle Identität, die Kunden bemerken',
            'description' => 'Ein Logo und eine Markenidentität, die Ihre Kunden auf den ersten Blick erkennen. Ich entwerfe eine visuelle Identität, die zu Ihrer Branche passt — eine, die Sie von der generischen Konkurrenz abhebt.',
        ],
        'social' => [
            'title'       => 'Social Media, die Vertrauen aufbauen',
            'description' => 'Kunden prüfen Ihre Social-Media-Präsenz, bevor sie bestellen. Eine aktive, konsistente Präsenz schafft Vertrauen. Ich bereite Inhalte und eine Strategie vor, die Sie Ihrer Zielgruppe näherbringt.',
        ],
    ],

    'price_anchor' => [
        'heading' => 'Was kostet es?',
        // OND-198 (Befund 5.4): Erwartungssatz vor der ersten Zahl.
        // OND-198 (Befund 5.5): „Tiers" → „drei Stufen".
        'intro'   => 'Die meisten Projekte, die ich baue, liegen zwischen 2.200 und 6.000 €. Wenn Sie eine Website unter 800 € suchen, bin ich nicht der richtige Anbieter für Sie — und das sage ich Ihnen gleich. Unten finden Sie orientierende Einstiegspreise für drei Stufen — ein verbindliches Angebot erhalten Sie schriftlich nach einer kurzen Beratung.',
        // OND-136: 25 / 55 / 95 Tausend CZK → EUR-Umrechnung (CEO-bestätigter 1:25-Anker). Eine Quelle der Wahrheit.
        // OND-198 (Befund 5.4): Reihenfolge Standard → Custom → Starter; die
        // günstigste Stufe steht zuletzt und wird als Ausnahme gerahmt.
        'featured_label' => 'Häufigste Wahl',
        'items'   => [
            [
                'title'    => 'Standard',
                'price'    => '2.200 €',
                'desc'     => 'Mehrsprachige Website mit Blog, Konversions-Tracking und Reservierungssystem.',
                'featured' => true,
            ],
            [
                'title'    => 'Custom',
                'price'    => 'ab 3.800 €',
                'desc'     => 'Online-Shop, Webanwendung oder ein komplexes Portal auf Maß.',
                'featured' => false,
            ],
            [
                'title'    => 'Starter',
                'price'    => '1.000 €',
                // OND-310: der entschuldigende Satz ist weg (Zadání, Aufgabe 3.7).
                'desc'     => 'Präsentations-Website bis fünf Seiten für Selbstständige.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detaillierte Preisliste →',
    ],

    // OND-308: Übrig sind nur die beiden Beschriftungen für den Screenreader —
    // Video und Foto gehören zum Toyota-Abschnitt. `heading`, `bio` und die
    // vier `advantages` sind auf der neuen Startseite nicht mehr da.
    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — wer ich bin und wie ich Websites baue',
        'photo_alt' => 'Ondřej Kriška — Webentwickler',
    ],

    'testimonials' => [
        'heading' => 'Was meine Kunden sagen',
        'note'    => 'Aus dem Tschechischen übersetzt — die Originale stehen auf Google, Firmy.cz und Facebook.',
    ],

    // OND-308: Der technische Abschnitt „Unter der Haube" ist weg. Geblieben
    // ist die gemessene Ladezeit, jetzt im Zahlenstreifen — die einzige
    // Aussage, die der Besucher an sich selbst überprüft. Die Performance API
    // misst im Browser des Besuchers; wir nennen nie eine ungemessene Zahl.
    'craft' => [
        'perf_prefix' => 'Diese Seite wurde für Sie in',
        'perf_suffix' => 'geladen — gemessen gerade eben, in Ihrem Browser.',
    ],

    // OND-235: Live-Demo (OND-229) entfernt — der Website-Inhaber konnte
    // den Besuchernutzen selbst nicht benennen, und auf dem Handy scrollte
    // der Reglereffekt außer Sicht. Keys und CSS (.pd-demo*) entfernt.

    // OND-201 (Befund 5.8): Das Ende der Homepage waren drei Handlungs-
    // aufforderungen hintereinander. Die Blöcke `cta` und `final_cta` sind
    // entfernt; es bleibt eine Aufforderung mit einem Formular in
    // `inline_form` unten, das Kundenzitat ist dorthin umgezogen.

    'faq' => [
        'heading' => 'Was Sie mich am häufigsten fragen',
        // `key` ist ein stabiler Slug für Analytics (data-faq-key) und JSON-LD; nicht lokalisieren.
        'items'   => [
            // OND-222 (Kapitel 6.3, Einwand 1): schwerwiegendster Einwand bei
            // einem Auftrag über 150 Tsd. Am 2026-09-16 zurückgezogen (Fakten
            // unbestätigt), am 2026-09-17 von Ondra bestätigt, mit zwei
            // Korrekturen:
            //  - Der Code gehört dem Kunden, liegt aber bis zur Schlusszahlung
            //    bei Ondra; NICHT „von Anfang an bei Ihnen";
            //  - keine Erwähnung von Laravel — sagt dem Kunden nichts;
            //  - Dokumentation ist nicht Standard, nur auf Wunsch.
            // Weiterhin gilt: kein Versprechen einer Rund-um-die-Uhr-Bereitschaft.
            [
                'key'      => 'single-person',
                'question' => 'Sie sind eine Person. Was, wenn Sie krank werden oder aufhören?',
                'answer'   => 'Ein berechtigtes Bedenken — bei einem Projekt dieser Größe ist das die wichtigste Frage. Die Website läuft auf keiner Plattform, die Sie nicht verlassen könnten: Es ist eigener Code auf einem normalen Webhosting. Die Zugänge zur Hosting-Verwaltung und zum FTP können Sie die ganze Zeit haben, sagen Sie einfach Bescheid. Nach der vollständigen Bezahlung gehört der Code Ihnen — ich übergebe ihn, wann immer Sie darum bitten, und jeder Entwickler kann daran weiterarbeiten; wenn eine Dokumentation zur Übergabe nötig ist, schreibe ich sie. Eine Rund-um-die-Uhr-Bereitschaft halte ich nicht und werde das auch nicht behaupten. Wofür ich einstehe: Bei mir bleibt nichts eingeschlossen.',
            ],
            // OND-269 (Audit OND-254, Befund 7): Die Frage „Was kostet es?"
            // ist hier raus — dieselbe Überschrift und dieselben Zahlen
            // stehen vier Sektionen weiter oben im Preisanker
            // (`price_anchor`) und in der Preisliste.
            [
                'key'      => 'duration',
                'question' => 'Wie lange dauert es?',
                'answer'   => 'Von der ersten Nachricht bis zum Launch typischerweise 4–12 Wochen — eine Woche Beratung, 2–5 Tage für die Spezifikation, 3–10 Wochen Umsetzung und Launch bis zum nächsten Werktag nach Freigabe. Den genauen Zeitplan für Ihr Projekt halte ich in der Spezifikation fest.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'Was, wenn ich nicht zufrieden bin?',
                'answer'   => 'Ich arbeite in kurzen Iterationen und schicke laufend Zwischenstände — ich warte nicht bis zum Projektende, um zu prüfen, ob es passt. Wenn etwas nicht stimmt, lösen wir es sofort, nicht erst nach der Rechnung. Was in der Spezifikation steht, liefere ich.',
            ],
            [
                'key'      => 'maintenance-free',
                // OND-310: die alte Frage fragte, was „wartungsfrei" bedeutet —
                // das ist mein Wort, nicht das des Kunden, und die Antwort nannte
                // fremde Technik. Das hier ist die Frage, die sich der Kunde
                // selbst stellt.
                'question' => 'Braucht die Website regelmäßige Wartung?',
                'answer'   => 'Nein. Sie steht nicht auf einer fertigen Plattform mit Zusatzmodulen, die jeden Monat aktualisiert werden müssen, also kann darin nichts von selbst kaputtgehen. Wenn Sie Inhalte ändern oder eine Seite ergänzen wollen, schreiben Sie mir und ich mache das.',
            ],
            // Archiv: weitere FAQ-Einträge wandern von der Homepage weg (nach /faq oder /sluzby — außerhalb des OND-121-Umfangs).
        ],
    ],


    // OND-201 (Befund 5.8): die einzige abschließende Aufforderung der Homepage.
    'inline_form' => [
        'eyebrow'         => 'Anfrage',
        'heading'         => 'Schreiben Sie mir, was Sie brauchen',
        'description'     => 'Beschreiben Sie kurz, worum es geht. Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir gehen unverbindlich durch, was Sinn ergibt. Wenn wir nicht zusammenpassen, sage ich es Ihnen geradeheraus.',
        'quote_text'      => 'Dank des individuellen Ansatzes, der Flexibilität und der Professionalität entspricht das Ergebnis unseren Vorstellungen.',
        'quote_author'    => 'Hana Jaskmanická, Geschäftsführerin, VP Industry',
        'name'            => 'Vor- und Nachname',
        'email'           => 'E-Mail',
        'phone'           => 'Telefon (optional)',
        'phone_hint'      => 'Mit Nummer melde ich mich schneller.',
        'message'         => 'Was möchten Sie lösen?',
        'placeholders'    => [
            'name'    => 'Max Mustermann',
            'email'   => 'max@firma.de',
            'phone'   => '+420 000 000 000',
            'message' => 'Z. B. neue Website für ein produzierendes Unternehmen, 5–10 Seiten',
        ],
        'submit'          => 'Anfrage senden',
        'submitting'      => 'Wird gesendet…',
        'note'            => 'Oder schreiben Sie mir an ok@ondraweb.cz. Ich antworte persönlich, nicht über einen Formular-Roboter.',
        'privacy_prefix'  => 'Mit dem Absenden stimmen Sie der Verarbeitung personenbezogener Daten gemäß den ',
        'privacy_link'    => 'Datenschutzrichtlinien zu',
        'success'         => 'Danke, die Anfrage ist eingegangen. Ich melde mich so schnell wie möglich.',
        'error'           => 'Die Anfrage konnte gerade nicht gespeichert werden. Bitte versuchen Sie es erneut.',
    ],

    // OND-308: `cta` versprach einen Kalender, den es seit OND-303 nicht
    // mehr gibt. Das Linkziel bleibt, nur die Beschriftung ändert sich.
    'sticky' => [
        'cta'    => 'Anfrage schreiben',
        'mobile' => 'Anfrage',
        'phone'  => 'Anrufen',
    ],

];
