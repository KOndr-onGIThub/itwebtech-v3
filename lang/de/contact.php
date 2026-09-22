<?php

return [

    'meta' => [
        'title'       => 'Kontakt — Ondřej Kriška',
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
    'address_registration' => 'Unternehmens-ID 19231407, nicht umsatzsteuerpflichtig',
    'phone_label'         => 'Telefon',
    'hours_label'         => 'Verfügbarkeit',
    'open_hours'          => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen. An Wochenenden und Feiertagen halte ich keine Bereitschaft, aber nichts geht verloren.',
    'cta_consultation'    => 'Schreiben Sie mir',

    'form_heading'        => 'Kontaktformular',
    'form_subheading'     => 'Erhalten Sie ein kostenloses, unverbindliches Angebot oder senden Sie eine Anfrage',
    'name'                => 'Vollständiger Name',
    'email'               => 'E-Mail-Adresse',
    'tel'                 => 'Telefon (optional)',
    'tel_hint'            => 'Mit Nummer melde ich mich schneller.',
    'subject'             => 'Betreff',
    'message'             => 'Ihre Nachricht',
    'message_placeholder' => 'Beschreiben Sie kurz, was Sie benötigen, oder schreiben Sie, wann ich Sie anrufen soll ...',
    'agree'               => 'Ich stimme der Verarbeitung meiner Daten gemäß der ',
    'policy'              => 'Datenschutzerklärung',
    'send'                => 'Nachricht senden',
    'sending'             => 'Wird gesendet...',
    'required'            => 'Bitte füllen Sie dieses Feld aus.',
    'enter_valid_email'   => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
    'policy_not_agreed'   => 'Sie haben der Datenschutzerklärung nicht zugestimmt.',
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
        'upline'          => 'Sie schreiben direkt mir.',
        'heading_html'    => 'Kein CRM,<br>kein Callcenter — <em>nur Ondřej</em>.',
        'eyebrow'         => 'Sie schreiben direkt mir',
        'heading'         => 'Sie schreiben direkt mir, Ondřej.',
        'subline'         => 'Ich lese Ihre Nachricht persönlich. Ich melde mich innerhalb von 24 Stunden an Arbeitstagen.',
        'photo_alt'       => 'Ondřej Kriška — Autor dieser Website und Ihr Ansprechpartner',
        'role_label'      => 'Entwickler, Autor dieser Website, Ihr einziger Ansprechpartner',
    ],

    'next_steps' => [
        'eyebrow' => 'Was danach passiert',
        'heading' => 'Drei Schritte — kein Marketing-Funnel.',
        'steps'   => [
            [
                'title' => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen',
                'text'  => 'Sie erhalten eine E-Mail von mir persönlich, keine automatische Bestätigung. An Wochenenden und Feiertagen halte ich keine Bereitschaft — ich melde mich am ersten Arbeitstag.',
            ],
            [
                'title' => 'Wir vereinbaren 30 Minuten Gespräch',
                'text'  => 'Ein kurzes Telefon- oder Videogespräch — wir klären, ob eine Zusammenarbeit Sinn ergibt. Ohne Präsentation, ohne Folien, ohne Verkaufsdruck.',
            ],
            [
                'title' => 'Sie bekommen ein schriftliches Angebot',
                'text'  => 'Innerhalb einer Woche schicke ich eine Spezifikation mit Umfang, Termin und genauem Preis. Was in der Spezifikation steht, steht auch auf der Rechnung.',
            ],
        ],
    ],

    'thank_you' => [
        'heading'      => 'Fertig, die Nachricht ist angekommen.',
        'subline'      => 'Danke. Ich lese sie persönlich und melde mich innerhalb von 24 Stunden an Arbeitstagen.',
        'next'         => 'In der Zwischenzeit können Sie sich meine Projekte ansehen oder die Preise lesen.',
        'cta_projects' => 'Projekte',
        'cta_price'    => 'Preise',
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
