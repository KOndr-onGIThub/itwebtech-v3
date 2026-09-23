<?php

return [

    'meta' => [
        'title'       => 'Cookies und Einwilligung — ondraweb.cz',
        'description' => 'Informationen zu den Cookies und Analysetools, die ich auf ondraweb.cz verwende, und wie Sie Ihre Einwilligung jederzeit widerrufen können.',
    ],

    'hero' => [
        'page_mark_label' => 'COOKIES & MESSUNG',
        'upline'          => 'Keine Werbe-Cookies. Kein Datenverkauf.',
        'heading_html'    => 'Was ich messe und <em>warum</em>.',
        'subline'         => 'Anonyme Besucherstatistiken — damit ich weiß, was funktioniert. Kein Ad-Targeting, keine Zwischenhändler.',
    ],

    'tldr' => [
        'eyebrow' => 'In Kürze',
        'items'   => [
            'Ich messe nur anonyme Besuche (GA4) und anonymisierte Heatmaps (Clarity).',
            'Keine Werbe-Cookies und kein Ad-Targeting — <code>ad_storage</code> ist dauerhaft auf <code>denied</code>.',
            'Sie können Ihre Einwilligung jederzeit über die Schaltfläche unten oder durch Löschen der Cookies im Browser widerrufen.',
        ],
    ],

    'intro' => 'Diese Seite fasst zusammen, welche Cookies und Analysetools auf der Website <strong>ondraweb.cz</strong> eingesetzt werden, wozu sie dienen und wie Sie Ihre Einwilligung jederzeit widerrufen können.',

    'what_we_use' => [
        'heading' => 'Was ich verwende',
        'items'   => [
            '<strong>Google Analytics 4 (GA4)</strong> — anonyme Besucherstatistiken, die zeigen, wie viele Menschen die Website besuchen, woher sie kommen und welche Bereiche sie ansprechen.',
            '<strong>Microsoft Clarity</strong> — Heatmaps und Sitzungsaufzeichnungen (mit anonymisierten Inhalten), die zeigen, wo Besucher Schwierigkeiten haben, das Gesuchte zu finden.',
        ],
        'note'    => 'Ich verwende keine Werbe-Cookies und kein Ad-Targeting. In GA4 bleiben die Werbe-Einwilligungen (<code>ad_storage</code>, <code>ad_user_data</code>, <code>ad_personalization</code>) dauerhaft auf <code>denied</code>.',
    ],

    'what_we_measure' => [
        'heading' => 'Was ich messe',
        'items'   => [
            'Besuche und Quellen (woher die Besucher kommen, wie viele Seiten sie aufrufen, wie lange sie bleiben).',
            // OND-269 (Audit OND-254): Den CTA „Angebot erhalten“ gibt es auf
            // der Website nicht. Hier wird bewusst kein Button-Text zitiert —
            // ein Rechtstext soll nicht jedes Mal veralten, wenn ein Button
            // umformuliert wird. Beschrieben ist das Verhalten.
            'Interaktionen mit den wichtigsten Handlungsaufforderungen — Klicks auf den CTA-Button, die Telefonnummer, Öffnen des Formulars, Absenden der Anfrage.',
            'Sitzungsaufzeichnungen (Clarity) — anonymisierte Videoaufzeichnung von Cursorbewegungen und Klicks, um Stellen zu erkennen, an denen Besucher sich verirren.',
        ],
    ],

    'retention' => [
        'heading' => 'Speicherdauer',
        'items'   => [
            'Einwilligung „Alle akzeptieren“ — im Browser (<code>localStorage</code>) <strong>365 Tage</strong> gespeichert, danach werden Sie erneut gefragt.',
            '„Ablehnen“ — <strong>180 Tage</strong> gespeichert; in diesem Zeitraum fragt der Banner nicht erneut und es werden keine Analyse-Cookies gesetzt.',
            'GA4-Cookies (<code>_ga</code>, <code>_ga_*</code>) — standardmäßig 2 Jahre (nur bei erteilter Einwilligung).',
            'Microsoft-Clarity-Cookies (<code>_clck</code>, <code>_clsk</code>, <code>MUID</code>, <code>CLID</code>) — gemäß Microsoft-Einstellungen (typischerweise 1 Jahr).',
        ],
    ],

    'revoke' => [
        'heading'      => 'Einwilligung widerrufen',
        'description'  => 'Wenn Sie Ihre Einwilligung widerrufen möchten, klicken Sie auf die folgende Schaltfläche. Die gespeicherte Einwilligung sowie etwaige GA-/Clarity-Cookies werden gelöscht und der Banner erscheint nach dem Neuladen der Seite erneut.',
        'button'       => 'Einwilligung widerrufen und Cookies löschen',
        'manual'       => 'Alternativ können Sie die Cookies für die Domain <code>ondraweb.cz</code> manuell in den Browsereinstellungen löschen.',
    ],

    'controller' => [
        'heading' => 'Datenverantwortlicher',
        'name'    => 'Ondřej Kriška – ONDRAWEB',
        'email_label' => 'E-Mail',
        'see_privacy_html' => 'Weitere Informationen zur Verarbeitung personenbezogener Daten finden Sie in der :link.',
        'see_privacy_link' => 'Datenschutzerklärung',
    ],

];
