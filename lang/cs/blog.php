<?php

return [

    // OND-204 (OND-197 bod 11b): blog přepsán do Ondrova hlasu.
    // Rubrika „Jak na to" slibovala návody = obecný obsah, který má z webu
    // zmizet. OND-266: název rubriky i adresa sjednoceny na „Zápisky“ /
    // `/zapisky`; ze starého `/jak-na-to` drží 301 (routes/web.php).
    'meta' => [
        'title'       => 'Zápisky — Ondřej Kriška',
        'description' => 'Píšu o tom, co při stavění webů a aplikací reálně řeším. Ceny, zadání, redesign, aplikace na míru.',
    ],

    'subheading'    => 'Zápisky',
    'heading'       => 'Píšu o tom, co sám dělám.',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero (Plex Sans display + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-portfolio
    // artefakt per CEO PR #78/#80/#82/#83 precedent (home/kontakt/cenik/realizace).
    'hero' => [
        'page_mark_label' => 'ZÁPISKY',
        'upline'          => 'Co reálně řeším při práci.',
        'heading_html'    => 'Píšu o tom,<br>co <em>sám</em> dělám.',
        'subline'         => 'Žádné obecné rady. Jen věci, na které narazím u zakázek: kolik co stojí, jak se píše zadání, kdy má smysl web předělat.',
    ],

    'read_more'     => 'Přečíst',
    'updated'       => 'aktualizováno',
    'share'         => 'Poslat článek dál',
    'more_articles' => 'Další články',
    'empty'         => 'Zatím tu nic nového není.',
    'not_published' => 'Tenhle článek teď není zveřejněný.',

    // OND-204: bloky `now` (Obsah v přípravě), `audit` (audit webu zdarma)
    // a `sidebar_ad` odstraněny — nabídka auditu slibovala výsledek za klienta
    // a stránka měla tři výzvy k akci vedle sebe. Zůstává jedna, `cta` níž.

    'back_to_blog' => '← Zpět na zápisky',

    // OND-130 P2 iter 8 — Article page-mark eyebrow + autor box.
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán per sitewide
    // precedent (PR #78/#80/#82/#83).
    'article' => [
        'page_mark_label' => 'ZÁPISKY',
        'author' => [
            'eyebrow'  => 'O autorovi',
            'name'     => 'Ondřej Kriška',
            'role'     => 'Stavím weby a aplikace na míru. Sám, na vlastním kódu.',
            'bio'      => 'Osmnáct let jsem pracoval v logistice Toyoty. Dnes stavím weby, e-shopy a aplikace na míru pro menší a střední firmy. Cenu říkám předem a jednáte přímo se mnou.',
            'linkedin_label' => 'LinkedIn',
            'linkedin_url'   => 'https://www.linkedin.com/in/ondrejkriska/',
            'contact_cta'    => 'Napsat Ondrovi',
        ],
    ],


    'cta' => [
        'heading' => 'Řešíte web nebo aplikaci?',
        'text'    => 'Napište mi, co potřebujete. Ozvu se nejpozději následující pracovní den a řeknu vám, jestli vám můžu pomoct.',
        'primary' => 'Napsat Ondrovi',
    ],

];
