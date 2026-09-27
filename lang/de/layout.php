<?php

return [

    'nav' => [
        'home'     => 'Startseite',
        'contact'  => 'Kontakt',
        'price'    => 'Preisliste',
        'projects' => 'Projekte',
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
    ],

    'prefooter' => [
        'tagline'   => 'Websites und Anwendungen nach Maß. Direkt.',
        // OND-369: Der Pre-Footer lud zur „kostenlosen Beratung“ ein, der
        // restliche Web sagt seit OND-307 „Anfrage schreiben“. „Kostenlos“
        // fällt weg — die Preisliste (OND-354) baut auf einer Schwelle auf.
        'cta'       => 'Anfrage schreiben',
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
