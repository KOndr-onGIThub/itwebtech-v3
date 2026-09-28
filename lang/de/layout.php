<?php

return [

    'nav' => [
        'home'     => 'Startseite',
        'contact'  => 'Kontakt',
        'price'    => 'Preisliste',
        'projects' => 'Projekte',
        'reviews'  => 'Bewertungen',
        'blog'     => 'Notizen',
        'about'    => 'Über mich',
        'lang_switcher' => 'Sprachumschalter',
    ],

    // OND-369: Überrest aus OND-307 — dieser Schlüssel wird nirgends
    // ausgegeben (Header und Drawer nutzen `home.sticky.cta`). Wortlaut an
    // den restlichen Web angeglichen, damit die alte Sprache nicht zurückkommt.
    'cta' => [
        'contact' => 'Anfrage schreiben',
    ],

    'footer' => [
        'rights'    => 'Alle Rechte vorbehalten.',
        'developer' => 'Website von',
        // OND-387: Claim und Navigation sind aus dem Pre-Footer hierher
        // umgezogen; der Pre-Footer ist entfernt (Unterseiten-Grundlage §3).
        // `prefooter.cta` und `prefooter.nav_label` entfallen — kein Button.
        'tagline'   => 'Websites und Anwendungen nach Maß. Direkt.',
        'nav_label' => 'Footer-Navigation',
    ],

    'modal' => [
        'close' => 'Schließen',
    ],

    // OND-167 — Cookie consent modal.
    'cookies' => [
        'title'       => 'Darf ich ein paar Cookies setzen?',
        'body'        => 'Ich messe nur ein paar Zahlen darüber, was auf der Seite funktioniert. Kein Datenhandel.',
        'policy_link' => 'Details in den Richtlinien',
        'accept'      => 'Alle akzeptieren',
        'reject'      => 'Ablehnen',
        'close'       => 'Schließen',
    ],

    'gdpr_form_note' => 'Mit dem Absenden stimmen Sie unserer',
    'gdpr_form_link' => 'Datenschutzerklärung',
    'footer_privacy_link' => 'Datenschutzerklärung',
    'cookies_link'   => 'Cookies',

    'meta' => [
        'description' => 'Entwicklung von Websites und Webanwendungen. Ich helfe Unternehmern, in der Online-Welt erfolgreich zu sein.',
    ],

];
