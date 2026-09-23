<?php

return [

    // OND-201 (Befund 5.7): Der Seitentitel darf sich nicht über die Negation
    // der Konkurrenz definieren, und „kein WordPress" sagt jemandem nichts,
    // der nicht weiß, was WordPress ist (Prinzip 0).
    'meta' => [
        'title'       => 'Websites und Webanwendungen nach Maß | ONDRAWEB',
        'description' => 'Websites, Onlineshops und Webanwendungen nach Maß für kleine und mittlere Unternehmen. Eigener Code, genauer Preis im Voraus, und Sie sprechen direkt mit mir. Ich bin Ondřej Kriška.',
    ],

    // TODO (OND-136 P3): final DE tone polish — Content Writer scope.
    'hero' => [
        // OND-127 P0 incident hotfix (2026-05-14) — Plagiat-Strings entfernt.
        // Placeholder copy aus meta description = pre-redesign safe copy.
        // FINAL COPY: Content Writer liefert in OND-136 P3 (SLA 2h von 11:10 UTC).
        'page_mark_label' => 'MASSGESCHNEIDERT',
        // OND-145 P0.3: page_mark_index entfernt — Agency-Portfolio-Pagination-
        // Artefakt, itwebtech hat im Hero-Kontext keine „pages" Hierarchie.
        // OND-198 (Befund 5.1): die alte Überschrift versprach das Geschäfts-
        // ergebnis des Kunden. Ersetzt durch den freigegebenen Hero-Text (CS = Quelle).
        'upline'          => 'Für Unternehmen, die den Unterschied erkennen.',
        'heading_html'    => 'Websites und Anwendungen <em>nach Maß</em>.<br>Ich baue sie selbst, mit eigenem Code.',
        'subline'         => 'Ich bin Ondřej Kriška, erfahrener Entwickler. Sie arbeiten direkt mit mir — ohne Agentur, ohne Zwischenhändler. Ich baue Websites so, dass sie jahrelang laufen und Sie nicht mit Wartung aufhalten.',
        'note'            => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen. Unverbindlich besprechen wir, was sinnvoll ist.',

        // Backwards compat (consultation modal, fallback render).
        'eyebrow'       => 'Maßgeschneiderte Websites & Webanwendungen',
        'heading'       => 'Websites und Anwendungen nach Maß. Ich baue sie selbst, mit eigenem Code.',
        'cta_primary'   => 'Schreiben Sie mir, was Sie brauchen',
        'cta_secondary' => '30-Min-Beratung vereinbaren',
        'phone_label'   => 'oder anrufen:',
    ],

    'modal' => [
        'title'             => 'Lassen Sie uns sprechen',
        'subtitle'          => 'Kostenlose Beratung — unverbindlich, ohne Registrierung.',
        'calendly_btn'      => 'Beratungstermin auswählen',
        'cta_note'          => 'Kostenlos. Unverbindlich.',
        'play_btn'          => 'Video abspielen',
    ],

    'anchors' => [
        'how_i_work' => 'wie-ich-arbeite',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        'rating_aria'  => 'Bewertung 5 von 5',
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

    // OND-201 (Befund 5.7): Der Abschnitt definierte sich über die Negation
    // der Konkurrenz und zwei von drei Punkten sagten dasselbe. Jetzt führt,
    // was ich tue (`lead`), die Abgrenzung ist kurz und die doppelten
    // Punkte sind zu einem zusammengeführt.
    'problems' => [
        'heading'            => 'Wie ich Websites baue',
        'lead'               => 'Jedes Projekt beginnt mit dem Verständnis Ihres Unternehmens. Ich schreibe eigenen Code von Grund auf, damit die Website dem folgt, wie Ihre Firma tatsächlich arbeitet. Sie sprechen direkt mit mir — von der ersten Nachricht bis zum Launch und darüber hinaus.',
        'transition_heading' => 'Was Sie sich damit ersparen',
        'transition_text'    => 'Die zwei Dinge, die ich bei Web-Projekten am häufigsten sehe.',
        'items' => [
            [
                'heading'      => 'Eine Vorlage als individuelle Lösung verkauft',
                'text'         => 'Ein Anbieter verwendet ein Layout, das er schon fünfmal genutzt hat, und fügt Ihren Text und Ihr Logo ein. Das Ergebnis wirkt professionell — bis Sie die Website der Konkurrenz öffnen und dieselben Abschnitte und dieselben Worte finden. Dazu hält die Plattform Sie in einem monatlichen Abonnement, das Sie nicht mitnehmen können.',
                'quote_text'   => 'Ganz anders als die Möchtegern-Webdesigner, die für überhöhte Preise einfach Vorlagen mit Inhalten befüllen.',
                'quote_author' => 'Petr Kroulík, Nové Interiéry s.r.o.',
            ],
            [
                'heading' => 'Sie sprechen nie mit der Person, die die Website erstellt',
                'text'    => 'Die Person, die Ihnen die Website verkauft, baut sie nicht. Die Personen, die sie bauen, sprechen nicht mit Ihnen. Kontext und Absicht gehen in der Mitte verloren — und das Ergebnis entspricht nicht dem, was Sie wollten.',
            ],
        ],
        // OND-269 (Audit OND-254, Befund 7): Rest der gestrichenen Sektion
        // „Generator versus Ihr Geschäft". Dasselbe Argument wie im ersten
        // Punkt oben („eine Vorlage, verkauft als Maßarbeit") — es gehört
        // hierher, nicht in eine eigene Sektion vier Bildschirme weiter unten.
        'ai_heading' => 'Eine Vorlage ist schnell fertig. Anfragen kommen davon nicht.',
        'ai_text'    => 'Ein Generator klickt ein Layout zusammen und füllt Texte und Bilder ein — er findet aber nicht heraus, an wen Sie verkaufen, warum ein Kunde Sie wählen sollte und wo Interessenten abspringen. KI nutze ich als Werkzeug; die Entscheidung, was die Website sagen soll und in welcher Reihenfolge, trifft sie nicht für Sie.',
    ],

    'how_i_work' => [
        'heading'   => 'Von der ersten Nachricht zur veröffentlichten Website — 4 klare Schritte.',
        'cta_intro' => 'Gleich zu Schritt 1.',
        'cta_label' => 'Beratung vereinbaren',
        'steps'   => [
            [
                'heading'      => 'Beratung',
                'time'         => '60 Min., binnen einer Woche',
                'text'         => 'Ich beginne mit einem Gespräch, nicht mit einem Formular. Ich muss Ihr Unternehmen und Ihre Kunden verstehen — und wissen, was die Website wirklich leisten soll: Kontakte bringen, ein Produkt verkaufen oder Vertrauen aufbauen.',
                'quote_text'   => 'Er hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Spezifikation',
                'time'         => '2–5 Tage',
                'text'         => 'Bevor ich mit der Arbeit beginne, erhalten Sie eine schriftliche Spezifikation: was auf der Website sein wird, wie viele Seiten, welche Technologie und was es kostet. Keine Überraschungen auf der Rechnung. Den Liefertermin schätze ich realistisch ein — immer im Voraus, nie rückwirkend.',
                'quote_text'   => 'Er analysiert die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll.',
                'quote_author' => 'Jan Stybor, Leiter der Projektabteilung, Toyota',
                'note'         => 'Hinweis zu Terminen: Eine Website entsteht nicht nur auf meiner Seite. Genehmigungen, Unterlagen vom Kunden und Feedback sind Teil des Prozesses. Der Termin ist daher immer eine Schätzung, keine Verpflichtung — und ich sage das offen von Anfang an.',
            ],
            [
                'heading' => 'Umsetzung',
                'time'    => '3–10 Wochen',
                'text'    => 'Ich halte Sie über den Fortschritt informiert und beziehe Sie in wichtige Entscheidungen ein. Das Ergebnis entspricht dem, was Sie sich gewünscht haben — weil ich nicht bis zum Ende des Projekts warte, um das herauszufinden.',
            ],
            [
                'heading' => 'Launch und Support',
                'time'    => 'bis zum nächsten Werktag',
                'text'    => 'Nach Ihrer Freigabe geht die Website in der Regel innerhalb eines Arbeitstages live. Nach dem Launch bleibe ich für Sie erreichbar — kleine Anpassungen, technische Fragen und Analytics-Hilfe laufen direkt über mich, ohne Ticket und ohne Warten.',
                'note'    => 'Launch innerhalb von 1 Arbeitstag nach Freigabe.',
            ],
        ],
    ],

    // OND-269 (Audit OND-254, Befund 7): Die Sektion „Generator versus Ihr
    // Geschäft" (zwei Spalten, acht Stichpunkte) ist gestrichen — der Board
    // hat den Schnitt am 22. 9. freigegeben. Übrig blieben zwei Sätze in
    // `problems.ai_heading` / `problems.ai_text`, wo dasselbe Argument
    // ohnehin schon stand.

    // OND-269: `toyota` ist keine eigene Sektion mehr — der Block wird
    // innerhalb von „Warum mit mir" unter Video und Bio ausgegeben. Die
    // Überschrift „18 Jahre bei Toyota" stand zuvor zweimal auf der Seite
    // (hier und in `why_me.bio`), deshalb ist Toyota aus der Bio raus.
    'toyota' => [
        'heading'      => '18 Jahre bei Toyota. Dann bin ich gegangen.',
        'text'         => 'Die Automobilindustrie hat mir eines beigebracht: Hinter jedem Spitzenergebnis stehen immer dieselben Schritte. Analyse, Design, Testen, Verifizieren — und dann wieder. Keine Abkürzungen, keine Schätzungen. Prinzipien, die unabhängig von der Branche funktionieren.',
        'text_2'       => 'Diese Prinzipien wende ich jetzt auf jedes Web-Projekt an. Sie werden es bei der ersten Beratung merken, in der Spezifikation, die Sie vor Arbeitsbeginn erhalten — und im Ergebnis.',
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
        'heading_primary'  => 'Was ich baue — Websites, Anwendungen und Online-Shops auf Maß',
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
                'description' => 'Eine Präsentations-Website, die sich von schablonenhaften Wettbewerbern abhebt und verständlich erklärt, was Sie tun und worin Sie sich unterscheiden.',
                'bullets'     => [
                    'Eigener Code — kein WordPress, keine Vorlagen',
                    'Konversionsorientierte Struktur passend zu Ihrem Geschäft',
                    'Wartungsfreier Betrieb und schnelle Ladezeiten',
                ],
            ],
            'aplikace' => [
                'title'       => 'Webanwendungen',
                'description' => 'Interne Systeme, Kundenportale und Verwaltungstools — zugeschnitten auf die Art, wie Ihr Betrieb tatsächlich arbeitet.',
                'bullets'     => [
                    'Prozessdesign vor der ersten Codezeile',
                    'Integration in Ihre bestehenden Werkzeuge',
                    'Eigene Administration ohne monatliche Lizenzgebühren',
                ],
            ],
            'eshop' => [
                'title'       => 'Online-Shops',
                'description' => 'Ein Online-Shop, der zu Ihrem Produkt passt — ohne monatliche Gebühren für Plugins und Vorlagen.',
                'bullets'     => [
                    'Kasse und Katalog passend zu Ihrem Sortiment',
                    'Anbindung an Buchhaltung, Versanddienstleister und Zahlungsanbieter',
                    'Keine monatlichen Plattform-Gebühren',
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
                'desc'     => 'Eine Ausnahme, kein Standard-Einstieg: Präsentations-Website bis 5 Seiten für Selbstständige. Ich nehme sie nur an, wo ein größerer Umfang keinen Sinn ergibt.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detaillierte Preisliste →',
    ],

    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — wer ich bin und wie ich Websites baue',
        'heading'   => 'Warum mit mir',
        'photo_alt' => 'Ondřej Kriška — Webentwickler',
        // OND-269: Der erste Satz („18 Jahre lang habe ich bei Toyota…") ist
        // hier raus — die Toyota-Geschichte steht direkt unter diesem Absatz
        // vollständig im Block `toyota`.
        'bio'       => 'Ich arbeite allein. Sie sprechen direkt mit mir — von der ersten Beratung bis zum Launch und darüber hinaus, mit derselben präzisen Spezifikation, Analyse und Verifikation bei jedem Projekt.',
        'advantages' => [
            [
                'heading' => 'Eigener Code, keine Vorlagen',
                'text'    => 'Ich baue passend zu Ihrem Unternehmen — nicht aus einer Vorlage, die Ihre Konkurrenz bereits verwendet.',
            ],
            [
                'heading' => 'Preis im Voraus',
                'text'    => 'Sie erhalten eine Spezifikation mit genauem Preis, bevor die Arbeit beginnt. Was in der Spezifikation steht, steht auf der Rechnung.',
            ],
            [
                'heading' => 'Direkter Kontakt',
                'text'    => 'Sie kommunizieren direkt mit mir — ohne Verkäufer, Koordinator und Ticket-System.',
            ],
            [
                'heading' => 'Gebaut, damit es hält',
                'text'    => 'Wartungsfreier Betrieb ohne WordPress-Updates und Plugins — keine monatlichen Sicherheits-Patches.',
            ],
        ],
    ],

    'testimonials' => [
        'heading' => 'Was meine Kunden über die Zusammenarbeit sagen.',
        'note'    => 'Aus dem Tschechischen übersetzt — die Originale stehen auf Google, Firmy.cz und Facebook.',
    ],

    // OND-269 (Audit OND-254, Befund 7): Die Sektion „Zwei Dinge, auf die Sie
    // sich verlassen können." ist gestrichen — der Board hat den Schnitt am
    // 22. 9. freigegeben. Beide Zusagen („Preis im Voraus", „Direkter
    // Kontakt") standen wörtlich ein zweites Mal; die einzige verbleibende
    // Fassung steht in `why_me.advantages` 02 und 03.

    // OND-229 (F2 — Beweisschicht): Abschnitt „Unter der Haube" + Live-Demo
    // der Design-Tokens. Jede Aussage ist im Repo überprüfbar; die Ladezeit
    // misst die Performance API im Browser des Besuchers.
    'craft' => [
        'heading' => 'Unter der Haube',
        'intro'   => 'Die Website, die ich für Sie baue, sieht auch von innen so aus. Das sind keine Marketingsätze — alles unten lässt sich direkt auf dieser Seite überprüfen.',
        'facts'   => [
            [
                'heading' => 'Eigener Code',
                'text'    => 'Kein WordPress, kein Page-Builder, keine Plattform. Die Seite ist maßgeschrieben und läuft ohne Plugins, die monatliche Updates bräuchten.',
            ],
            [
                'heading' => 'Bilder nach Maß für Ihr Display',
                'text'    => 'Jedes Bild existiert hier in sieben Größen und im sparsamen AVIF-Format. Ihr Browser hat nur die geladen, die Ihr Display wirklich braucht.',
            ],
            [
                'heading' => 'Design mit System',
                'text'    => 'Farben, Schrift und Abstände steuert keine Vorlage, sondern ein eigenes System von Variablen. Deshalb wirkt nichts fehl am Platz — und deshalb können Sie es unten selbst ausprobieren.',
            ],
        ],
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
        'heading' => 'Häufige Fragen',
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
                'answer'   => 'Von der ersten Nachricht bis zum Launch typischerweise 4–12 Wochen — eine Woche Beratung, 2–5 Tage für die Spezifikation, 3–10 Wochen Umsetzung und Launch bis zum nächsten Werktag nach Freigabe. Den detaillierten Zeitplan für Ihr Projekt halte ich in der Spezifikation fest.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'Was, wenn ich nicht zufrieden bin?',
                'answer'   => 'Ich arbeite in kurzen Iterationen und schicke laufend Zwischenstände — ich warte nicht bis zum Projektende, um zu prüfen, ob es passt. Wenn etwas nicht stimmt, lösen wir es sofort, nicht erst nach der Rechnung. Was in der Spezifikation steht, liefere ich.',
            ],
            [
                'key'      => 'maintenance-free',
                'question' => 'Was bedeutet „wartungsfreie Website“?',
                'answer'   => 'Kein WordPress, keine Plugins, keine monatlichen Sicherheitsupdates. Die Website läuft auf eigenem Code — sie funktioniert von selbst, benötigt keine regelmäßigen Patches und fällt nicht durch Template-Konflikte aus. Kleine Inhaltsänderungen laufen direkt über mich, ohne Ticket.',
            ],
            // Archiv: weitere FAQ-Einträge wandern von der Homepage weg (nach /faq oder /sluzby — außerhalb des OND-121-Umfangs).
        ],
    ],

    'faq_form' => [
        'eyebrow'     => 'Andere Frage?',
        'heading'     => 'Schreiben Sie sie direkt.',
        'description' => 'Ich greife sie auf und melde mich innerhalb von 24 Stunden an Arbeitstagen. Kein Verkaufsdruck.',
        'name'        => 'Name',
        'email'       => 'E-Mail',
        'message'     => 'Ihre Frage',
        'placeholders' => [
            'name'    => 'Max Mustermann',
            'email'   => 'max@firma.de',
            'message' => 'Z. B. Schaffen Sie es bis zum Quartalsende?',
        ],
        'submit'      => 'Frage senden',
        'submitting'  => 'Wird gesendet…',
        'success'     => 'Danke, die Frage ist eingegangen. Ich melde mich so schnell wie möglich.',
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

    // TODO: review pro DE — copy podle CS varianty A (OND-100)
    'sticky' => [
        'cta'    => 'Beratung vereinbaren',
        'mobile' => 'Anfrage',
        'phone'  => 'Anrufen',
    ],

];
