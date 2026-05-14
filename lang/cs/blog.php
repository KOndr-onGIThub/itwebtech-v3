<?php

return [

    'meta' => [
        'title'       => 'Blog — Jak na to | Ondřej Kriška',
        'description' => 'Tipy pro lepší webové stránky a aplikace. Praktické rady pro podnikatele v online světě.',
    ],

    'subheading'    => 'Jak na to',
    'heading'       => 'Tipy pro lepší webové stránky a aplikace.',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero (Fraunces italic + amber accent).
    // Index 06 / 09 v sitewide page-mark schématu (home–kontakt–cenik–realizace–
    // realizace/{slug}–jak-na-to–jak-na-to/{slug}–cookies–zasady).
    'hero' => [
        'page_mark_label' => 'JAK NA TO',
        'page_mark_index' => '06 / 09',
        'upline'          => 'Praktické rady, ne teorie.',
        'heading_html'    => 'Co opravdu <em>funguje</em><br>na vašem webu.',
        'subline'         => 'Konverze, SEO, UX — bez marketingových frází. Reálné kroky, které přinášejí poptávky.',
    ],

    'read_more'     => 'Chci vědět jak na to',
    'updated'       => 'aktualizováno',
    'share'         => 'Sdílejte prosím článek',
    'more_articles' => 'Další články',
    'empty'         => 'Momentálně zde není žádný publikovaný článek.',
    'not_published' => 'Omlouvám se, tento článek není momentálně publikován.',

    'sidebar_ad' => [
        'subheading' => 'Netrapte se s webem sami',
        'heading'    => 'Posuňte web tam, kam patří',
        'text'       => 'Místo experimentování si nechte web udělat napoprvé správně.',
        'cta_price'  => 'Ceník',
        'cta_contact'=> 'Kontakt',
    ],

    'now' => [
        'subheading' => 'Obsah v přípravě',
        'heading'    => 'Nečekejte na další článek, začněte teď',
        'desc'       => 'Místo obecných rad se zaměřte na kroky, které mají největší dopad na poptávky z webu.',
        'items'      => [
            'Sjednoťte hlavní nabídku do jedné věty, které rozumí i nový návštěvník.',
            'Každé hlavní stránce dejte jeden jasný konverzní krok.',
            'Odstraňte slepé sekce bez návaznosti na kontakt nebo objednávku.',
            'Uveďte důkaz důvěryhodnosti: reference, proces spolupráce, garance.',
        ],
    ],

    'back_to_blog' => '← Zpět na blog',

    // OND-130 P2 iter 8 — Article page-mark eyebrow + autor box.
    // Article index 07/09 v sitewide schématu.
    'article' => [
        'page_mark_label' => 'JAK NA TO',
        'page_mark_index' => '07 / 09',
        'author' => [
            'eyebrow'  => 'O autorovi',
            'name'     => 'Ondřej Kriška',
            'role'     => 'Web developer · weby na míru pro B2B služby',
            'bio'      => 'Stavím weby, které firmám otevírají obchodní hovor. Vlastní kód, přesná cena předem, přímý kontakt.',
            'linkedin_label' => 'LinkedIn',
            'linkedin_url'   => 'https://www.linkedin.com/in/ondrejkriska/',
            'contact_cta'    => 'Chci nezávaznou nabídku',
        ],
    ],


    'cta' => [
        'heading' => 'Potřebujete pomoc s vaším webem?',
        'text'    => 'Pojďme se pobavit o tom, jak váš web může přinést více poptávek.',
        'primary' => 'Domluvit konzultaci',
    ],

    'audit' => [
        'subheading'    => 'Rychlá konverzní úprava',
        'heading'       => 'Získejte stručný audit vašeho webu',
        'items'         => [
            '3 největší konverzní brzdy, které teď zbytečně ztrácí poptávky.',
            'Konkrétní doporučení, co upravit jako první.',
            'Návrh priorit bez kosmetických vylepšení.',
        ],
        'cta_primary'   => 'Chci stručný audit zdarma',
        'cta_secondary' => 'Nejdřív ceník',
    ],

];
