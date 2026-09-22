<?php

return [

    // OND-204 (OND-197 Punkt 11b): Blog in Ondřejs eigener Stimme neu geschrieben.
    // Die alte Rubrik „Wie geht das" versprach allgemeine Anleitungen — genau
    // der Inhalt, der verschwinden soll. URL-Slug bleibt unverändert (SEO).
    'meta' => [
        'title'       => 'Notizen — Ondřej Kriška',
        'description' => 'Ich schreibe darüber, womit ich beim Bau von Webseiten und Anwendungen wirklich zu tun habe. Preise, Briefings, Redesign, Anwendungen nach Maß.',
    ],

    'subheading'    => 'Notizen',
    'heading'       => 'Ich schreibe über das, was ich selbst mache.',

    // OND-130 P2 iter 8 — Plan §3.1 Page-Mark Hero (Plex Sans Display + Amber-Akzent).
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt — Agency-
    // Portfolio-Artefakt per CEO PR #78/#80/#82/#83 (Home/Kontakt/Preise/Projekte).
    'hero' => [
        'page_mark_label' => 'NOTIZEN',
        'upline'          => 'Womit ich bei der Arbeit wirklich zu tun habe.',
        'heading_html'    => 'Ich schreibe über das,<br>was ich <em>selbst</em> mache.',
        'subline'         => 'Keine allgemeinen Ratschläge. Nur Dinge aus echten Aufträgen: was es kostet, wie ein Briefing entsteht, wann ein Redesign Sinn ergibt.',
    ],

    'read_more'     => 'Lesen',
    'updated'       => 'aktualisiert',
    'share'         => 'Artikel weitergeben',
    'more_articles' => 'Weitere Artikel',
    'empty'         => 'Hier gibt es noch nichts Neues.',
    'not_published' => 'Dieser Artikel ist derzeit nicht veröffentlicht.',

    // OND-204: Blöcke `now` (Inhalt in Vorbereitung), `audit` (kostenloser
    // Website-Audit) und `sidebar_ad` entfernt — das Audit-Angebot versprach
    // Ergebnisse anstelle des Kunden und die Seite hatte drei CTAs nebeneinander.

    'back_to_blog' => '← Zurück zum Blog',

    // OND-130 P2 iter 8 — Article page-mark eyebrow + Autor-Box.
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt per
    // Sitewide-Präzedenzfall (PR #78/#80/#82/#83).
    'article' => [
        'page_mark_label' => 'NOTIZEN',
        'author' => [
            'eyebrow'  => 'Über den Autor',
            'name'     => 'Ondřej Kriška',
            'role'     => 'Ich baue Webseiten und Anwendungen nach Maß. Allein, mit eigenem Code.',
            'bio'      => 'Achtzehn Jahre habe ich in der Logistik von Toyota gearbeitet. Heute baue ich Webseiten, Onlineshops und Anwendungen nach Maß für kleine und mittlere Firmen. Den Preis nenne ich vorab und Sie sprechen direkt mit mir.',
            'linkedin_label' => 'LinkedIn',
            'linkedin_url'   => 'https://www.linkedin.com/in/ondrejkriska/',
            'contact_cta'    => 'Ondřej schreiben',
        ],
    ],


    'cta' => [
        'heading' => 'Geht es um eine Webseite oder eine Anwendung?',
        'text'    => 'Schreiben Sie mir, was Sie brauchen. Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und sage Ihnen, ob ich helfen kann.',
        'primary' => 'Ondřej schreiben',
    ],

];
