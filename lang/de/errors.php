<?php

return [

    // OND-136: Minimal-Copy für Fehlerseiten (404, 500, 503, …).
    // Behält den Ton der Seite — keine Witze, nur ein schneller Rückweg
    // zum Konversionspfad (Homepage / Kontakt). Engineer (B2) verdrahtet
    // das in `resources/views/errors/{code}.blade.php`.

    '404' => [
        'meta' => [
            'title'       => 'Seite nicht gefunden (404) — Ondřej Kriška',
            'description' => 'Diese Seite existiert nicht mehr oder wurde verschoben. Gehen Sie zurück zur Startseite oder schreiben Sie mir — ich helfe Ihnen weiter.',
        ],
        'eyebrow'      => 'Fehler 404',
        'heading'      => 'Diese Seite gibt es leider nicht.',
        'subheading'   => 'Die gesuchte Seite wurde entweder verschoben, gelöscht oder hat nie existiert.',
        'help'         => 'Wenn Sie über einen Link hierhergekommen sind, der eigentlich funktionieren sollte, sagen Sie mir Bescheid — ich repariere es.',
        'cta_primary'  => 'Zurück zur Startseite',
        'cta_secondary'=> 'Sagen Sie mir, was Sie gesucht haben',
    ],

    '500' => [
        'meta' => [
            'title'       => 'Serverfehler (500) — Ondřej Kriška',
            'description' => 'Etwas ist auf unserer Seite schiefgelaufen. Bitte versuchen Sie es in Kürze erneut oder kontaktieren Sie mich direkt.',
        ],
        'heading'      => 'Auf unserer Seite ist etwas schiefgelaufen.',
        'subheading'   => 'Der Fehler liegt bei mir, nicht bei Ihnen. Bitte versuchen Sie es in Kürze erneut.',
        'help'         => 'Wenn das Problem weiterhin besteht, melden Sie sich direkt — ich kümmere mich darum.',
        'cta_primary'  => 'Erneut versuchen',
        'cta_secondary'=> 'Kontakt aufnehmen',
    ],

    '503' => [
        'meta' => [
            'title'       => 'Seite vorübergehend nicht erreichbar — Ondřej Kriška',
            'description' => 'Die Seite ist kurzzeitig wegen Wartung offline. Sie ist in Kürze wieder erreichbar.',
        ],
        'heading'      => 'Kurze Wartungsarbeiten.',
        'subheading'   => 'Die Seite ist in Kürze wieder erreichbar. Wenn Sie etwas Dringendes brauchen, melden Sie sich direkt.',
        'cta_primary'  => 'E-Mail schreiben',
    ],

];
