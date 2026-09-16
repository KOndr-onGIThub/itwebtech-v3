<?php

return [

    // OND-204 (OND-197 item 11b): blog rewritten in Ondřej's own voice.
    // The old "How to" framing promised generic tutorials — exactly the
    // content that is being removed. URL slug stays unchanged (SEO).
    'meta' => [
        'title'       => 'Notes — Ondřej Kriška',
        'description' => 'I write about what I actually deal with when building websites and applications. Pricing, briefs, redesigns, custom apps.',
    ],

    'subheading'    => 'Notes',
    'heading'       => 'I write about what I do myself.',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero (Plex Sans display + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-portfolio
    // artefact per CEO PR #78/#80/#82/#83 precedent (home/contact/pricing/projects).
    'hero' => [
        'page_mark_label' => 'NOTES',
        'upline'          => 'What I actually deal with at work.',
        'heading_html'    => 'I write about<br>what I do <em>myself</em>.',
        'subline'         => 'No generic advice. Only things I run into on real projects: what it costs, how to write a brief, when a redesign makes sense.',
    ],

    'read_more'     => 'Read',
    'updated'       => 'updated',
    'share'         => 'Pass the article on',
    'more_articles' => 'More articles',
    'empty'         => 'Nothing new here yet.',
    'not_published' => 'This article is not published right now.',

    // OND-204: blocks `now` (content in progress), `audit` (free website audit)
    // and `sidebar_ad` removed — the audit offer promised results on the
    // client's behalf and the page carried three CTAs side by side.

    'back_to_blog' => '← Back to blog',

    // OND-130 P2 iter 8 — Article page-mark eyebrow + author box.
    // OND-135 cleanup (2026-05-14): page_mark_index removed per sitewide
    // precedent (PR #78/#80/#82/#83).
    'article' => [
        'page_mark_label' => 'NOTES',
        'author' => [
            'eyebrow'  => 'About the author',
            'name'     => 'Ondřej Kriška',
            'role'     => 'I build custom websites and applications. On my own, on my own code.',
            'bio'      => 'I spent eighteen years in Toyota logistics. Today I build websites, e-shops and custom applications for small and mid-sized companies. I quote the price up front and you deal with me directly.',
            'linkedin_label' => 'LinkedIn',
            'linkedin_url'   => 'https://www.linkedin.com/in/ondrejkriska/',
            'contact_cta'    => 'Write to Ondřej',
        ],
    ],


    'cta' => [
        'heading' => 'Dealing with a website or an application?',
        'text'    => 'Tell me what you need. I will get back to you within two working days and tell you whether I can help.',
        'primary' => 'Write to Ondřej',
    ],

];
