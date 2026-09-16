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
    'hours_label'         => 'Verfügbarkeit',
    'open_hours'          => 'Ich antworte an Werktagen, in der Regel innerhalb von zwei Arbeitstagen. An Wochenenden und Feiertagen halte ich keine Bereitschaft, aber nichts geht verloren.',
    'cta_consultation'    => 'Schreiben Sie mir',

    'form_heading'        => 'Kontaktformular',
    'form_subheading'     => 'Erhalten Sie ein kostenloses, unverbindliches Angebot oder senden Sie eine Anfrage',
    'name'                => 'Vollständiger Name',
    'email'               => 'E-Mail-Adresse',
    'tel'                 => 'Telefonnummer',
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
        'subline'         => 'Ich lese Ihre Nachricht persönlich und antworte meist bis zum nächsten Werktag.',
        'photo_alt'       => 'Ondřej Kriška — Autor dieser Website und Ihr Ansprechpartner',
        'role_label'      => 'Entwickler, Autor dieser Website, Ihr einziger Ansprechpartner',
    ],

    'next_steps' => [
        'eyebrow' => 'Was danach passiert',
        'heading' => 'Drei Schritte — kein Marketing-Funnel.',
        'steps'   => [
            [
                'title' => 'Ich antworte innerhalb von 24 Stunden',
                'text'  => 'Sie erhalten eine E-Mail von mir persönlich, keine automatische Bestätigung. Bin ich unterwegs, melde ich mich spätestens am nächsten Werktag.',
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
        'subline'      => 'Danke. Ich lese sie persönlich und antworte spätestens bis zum nächsten Werktag.',
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
