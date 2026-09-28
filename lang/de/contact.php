<?php

return [

    'meta' => [
        'title'       => 'Kontakt — Ondřej Kriška, ONDRAWEB',
        'description' => 'Rufen Sie an oder schreiben Sie und ich melde mich zurück. Kontaktformular, Telefon und Adresse.',
    ],

    'subheading'          => 'Ich helfe Ihnen',
    'heading'             => 'Rufen Sie an oder schreiben Sie und ich melde mich zurück',
    // OND-201 (Befund 5.9): vollständige Adresse und Unternehmens-ID statt
    // nur des Landes — öffentliche Daten, stärken Vertrauen und lokale
    // Sichtbarkeit. Quelle: lang/de/about.php („Wo ich ansässig bin").
    'address_label'       => 'Adresse',
    'address_name'        => 'Ondřej Kriška',
    'address_street'      => 'Dunajovská 116',
    'address_city'        => '691 81 Březí, Tschechien',
    'address_registration' => 'IČO (tschechische Unternehmens-ID) 19231407, nicht umsatzsteuerpflichtig',
    'email_label'         => 'E-Mail',
    'phone_label'         => 'Telefon',
    'hours_label'         => 'Verfügbarkeit',
    'open_hours'          => 'Ich melde mich spätestens am nächsten Arbeitstag. Wochenenden und Feiertage zählen nicht mit, aber nichts geht verloren.',
    'cta_consultation'    => 'Schreiben Sie mir',

    // OND-448 (B-01): Felder, Button, Datenschutzhinweis und Bestätigung des Formulars
    // stehen seit der Vereinheitlichung in `home.inline_form` — /kontakt rendert dasselbe `<x-lead-form>`.
    'form_heading'        => 'Kontaktformular',
    // OND-371 — viz lang/cs/contact.php: „kostenlos“ jde pryč, termín odpovědi
    // se neopakuje počtvrté, slovník drží krok 3 („Umfang, Termin, Preis“).
    // Druhá věta je eliptická („oder eine Antwort“): plné „auf Ihre Frage“
    // lámalo řádek se sirotkem „Ihre Frage.“ Takto 1 řádek, rezerva 45 px.
    'form_subheading'     => 'Worum geht es? Ich melde mich persönlich und sage Ihnen, ob ich helfen kann.',
    'required'            => 'Bitte füllen Sie dieses Feld aus.',
    'enter_valid_email'   => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
    'upload' => [
        'label'            => 'Dateien hinzufügen',
        'drag_text'        => '— oder hierher ziehen',
        'browse'           => 'Dateien auswählen',
        'hint'             => 'PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP…',
        'max_files'        => 'Max. 5 Dateien',
        'max_size'         => 'insgesamt 20 MB',
        'remove'           => 'Entfernen',
        'error_too_many'   => 'Es lassen sich höchstens 5 Dateien auf einmal anhängen.',
        'error_too_large'  => 'Die Anhänge dürfen zusammen 20 MB nicht überschreiten.',
        // OND-264: Meldungen der serverseitigen Anhang-Validierung.
        'error_per_file'   => 'Eine einzelne Datei darf höchstens :max MB groß sein.',
        'error_mime'       => 'Dieser Dateityp kann nicht gesendet werden. Erlaubt: :types.',
        'error_failed'     => 'Der Anhang konnte nicht gespeichert werden. Bitte versuchen Sie es erneut — oder senden Sie mir die Datei an ok@ondraweb.cz.',
    ],

    'message_success'     => 'Danke für Ihre Nachricht.',
    'message_error'       => 'Beim Senden ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut — oder schreiben Sie mir direkt an ok@ondraweb.cz.',

    // OND-201 (Punkt 8): `contact.blade.php` nutzt hero / next_steps /
    // thank_you und das optionale Budget-Feld (in OND-136 nur für CS ergänzt),
    // deshalb rendert die deutsche Seite rohe Übersetzungsschlüssel.
    // Copy folgt der freigegebenen CS-Fassung.
    'hero' => [
        'page_mark_label' => 'KONTAKT',
        'upline'          => 'Sie schreiben mir direkt.',
        'heading_html'    => 'Kein CRM,<br>kein Callcenter — <em>nur Ondřej</em>.',
        'eyebrow'         => 'Sie schreiben mir direkt',
        'heading'         => 'Sie schreiben direkt an mich, Ondřej.',
        'subline'         => 'Ich lese Ihre Nachricht persönlich. Schreiben Sie mir heute, dann melde ich mich spätestens am :date.',
        'photo_alt'       => 'Ondřej Kriška — Autor dieser Website und Ihr Ansprechpartner',
        'role_label'      => 'Entwickler, Autor dieser Website, Ihr einziger Ansprechpartner',
    ],

    'next_steps' => [
        'eyebrow' => 'Was danach passiert',
        'heading' => 'Drei Schritte — kein Marketing-Funnel.',
        'steps'   => [
            [
                'title' => 'Ich melde mich spätestens am nächsten Arbeitstag',
                'text'  => 'Sie erhalten eine E-Mail von mir persönlich, keine automatische Bestätigung. Wenn Sie am Freitagabend schreiben, melde ich mich am Montag.',
            ],
            [
                'title' => 'Ein kurzes Erstgespräch',
                'text'  => 'Etwa 15 Minuten am Telefon. Ich kläre, worum es geht, und sage Ihnen gleich, ob ich Ihnen helfen kann. Ohne Präsentation und ohne Verkaufsdruck.',
            ],
            [
                'title' => 'Wir vereinbaren das weitere Vorgehen',
                'text'  => 'Wenn es Sinn ergibt, gehen wir die Details durch und ich schreibe eine Spezifikation mit Umfang, Termin und genauem Preis. Was in der Spezifikation steht, steht auch auf der Rechnung.',
            ],
        ],
    ],

    'budget_label'   => 'Orientierendes Budget (optional)',
    'budget_options' => [
        'Bis 25.000 CZK',
        '25.000 bis 55.000 CZK',
        '55.000 bis 95.000 CZK',
        '95.000 CZK und mehr',
        'Weiß ich noch nicht — beraten Sie mich',
    ],

];
