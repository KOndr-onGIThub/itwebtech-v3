<?php

return [

    'meta' => [
        'title'       => 'Kontakt — Ondřej Kriška',
        'description' => 'Schreiben Sie mir und ich melde mich zurück. Kontaktformular und Adresse.',
    ],

    'subheading'          => 'Ich helfe Ihnen',
    'heading'             => 'Schreiben Sie mir und ich melde mich innerhalb von 24 Stunden',
    'address_label'       => 'Adresse',
    'hours_label'         => 'Verfügbarkeit',
    'open_hours'          => 'Mo–Fr: 9:00–19:00<br>Sa–So: 12:00–17:00',
    'cta_consultation'    => 'Online-Termin vereinbaren',

    'form_heading'        => 'Kontaktformular',
    'form_subheading'     => 'Erhalten Sie kostenlos und unverbindlich ein Angebot — oder schicken Sie mir eine beliebige Frage.',
    'name'                => 'Vollständiger Name',
    'email'               => 'E-Mail',
    'tel'                 => 'Telefonnummer',
    'subject'             => 'Betreff',
    'message'             => 'Ihre Nachricht',
    'message_placeholder' => 'Beschreiben Sie kurz, was Sie benötigen — oder schreiben Sie, wann ich Sie anrufen soll…',
    'agree'               => 'Ich stimme der Verarbeitung meiner Daten gemäß der ',
    'policy'              => 'Datenschutzerklärung',
    'send'                => 'Nachricht senden',
    'sending'             => 'Wird gesendet...',
    'required'            => 'Bitte füllen Sie dieses Feld aus.',
    'enter_valid_email'   => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
    'policy_not_agreed'   => 'Zum Senden brauchen wir Ihre Einwilligung zur Datenverarbeitung.',
    'message_success'     => 'Danke für Ihre Nachricht.',
    'message_error'       => 'Etwas ist schiefgelaufen. Bitte versuchen Sie es erneut — oder schreiben Sie mir direkt an ok@itwebtech.cz.',

    // OND-136: Net-new Copy-Blöcke für /contact-Redesign (Plan §1).
    // Engineer (B2) verdrahtet diese Schlüssel in `resources/views/pages/contact.blade.php`.

    // Trust-Signal-Hero — „es-ist-ein-echter-Mensch".
    'hero' => [
        'eyebrow'      => 'Sie schreiben direkt an mich',
        'heading'      => 'Sie schreiben direkt an mich, Ondřej.',
        'subline'      => 'Kein CRM, kein Callcenter, kein Formular-Dispatcher. Ich lese Ihre Nachricht persönlich und antworte in der Regel bis zum nächsten Werktag.',
        'photo_alt'    => 'Ondřej Kriška — Autor und Ansprechpartner',
        'role_label'   => 'Entwickler, Autor dieser Seite, einziger Ansprechpartner',
    ],

    // 3-Schritt „Was als Nächstes passiert" — reduziert die Hemmschwelle, das Formular abzuschicken.
    'next_steps' => [
        'eyebrow' => 'Was als Nächstes passiert',
        'heading' => 'Drei Schritte — kein Marketing-Trichter.',
        'steps'   => [
            [
                'title' => 'Antwort innerhalb von 24 Stunden',
                'text'  => 'Sie erhalten eine E-Mail von mir persönlich, keine automatische Bestätigung. Wenn ich unterwegs bin, melde ich mich spätestens am nächsten Werktag.',
            ],
            [
                'title' => '30-minütiges Gespräch vereinbaren',
                'text'  => 'Ein kurzes Telefonat oder Videocall — wir prüfen gemeinsam, ob die Zusammenarbeit Sinn ergibt. Keine Präsentation, keine Folien, kein Verkaufsdruck.',
            ],
            [
                'title' => 'Sie erhalten ein schriftliches Angebot',
                'text'  => 'Innerhalb einer Woche schicke ich eine Spezifikation mit Umfang, Termin und genauem Preis. Was in der Spezifikation steht, steht auf der Rechnung.',
            ],
        ],
    ],

    // Thank-you-State — ersetzt das Formular nach erfolgreichem Absenden.
    'thank_you' => [
        'heading'  => 'Erledigt — Ihre Nachricht ist da.',
        'subline'  => 'Vielen Dank. Ich lese sie persönlich und antworte spätestens bis zum nächsten Werktag.',
        'next'     => 'In der Zwischenzeit können Sie sich umgesetzte Projekte oder die Preisliste ansehen.',
        'cta_projects' => 'Umgesetzte Projekte',
        'cta_price'    => 'Preisliste',
    ],

    // Optionales Budget-Feld (angeglichen an home.inline_form und Landing-Budgets).
    'budget_label'   => 'Orientierendes Budget (optional)',
    'budget_options' => [
        'Bis 1.000 €',
        '1.000 € – 2.200 €',
        '2.200 € – 3.800 €',
        '3.800 € und mehr',
        'Noch unsicher — bitte beraten',
    ],

];
