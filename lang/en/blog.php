<?php

return [

    'meta' => [
        'title'       => 'Blog — How to | Ondřej Kriška',
        'description' => 'Tips for better websites and applications. Practical advice for entrepreneurs in the online world.',
    ],

    'subheading'    => 'How to',
    'heading'       => 'Tips for better websites and applications.',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero (Plex Sans display + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-portfolio
    // artefact per CEO PR #78/#80/#82/#83 precedent (home/contact/pricing/projects).
    'hero' => [
        'page_mark_label' => 'HOW TO',
        'upline'          => 'Practical advice, not theory.',
        'heading_html'    => 'What actually <em>works</em><br>on your website.',
        'subline'         => 'Conversion, SEO, UX — no marketing fluff. Real steps that bring inquiries.',
    ],

    'read_more'     => 'I want to know how',
    'updated'       => 'updated',
    'share'         => 'Please share the article',
    'more_articles' => 'More articles',
    'empty'         => 'There are currently no published articles.',
    'not_published' => 'Sorry, this article is not currently published.',

    'sidebar_ad' => [
        'subheading' => 'Don\'t struggle with your website alone',
        'heading'    => 'Move your website where it belongs',
        'text'       => 'Instead of experimenting, have your website built right the first time.',
        'cta_price'  => 'Pricing',
        'cta_contact'=> 'Contact',
    ],

    'now' => [
        'subheading' => 'Content in progress',
        'heading'    => 'Do not wait for the next article, start now',
        'desc'       => 'Instead of generic tips, focus on actions with the highest impact on inquiries.',
        'items'      => [
            'Summarize your main offer in one sentence every new visitor understands.',
            'Give each key page one clear conversion action.',
            'Remove dead-end sections without a path to contact or order.',
            'Add trust proof: testimonials, process, and guarantees.',
        ],
    ],

    'back_to_blog' => '← Back to blog',

    // OND-130 P2 iter 8 — Article page-mark eyebrow + author box.
    // OND-135 cleanup (2026-05-14): page_mark_index removed per sitewide
    // precedent (PR #78/#80/#82/#83).
    'article' => [
        'page_mark_label' => 'HOW TO',
        'author' => [
            'eyebrow'  => 'About the author',
            'name'     => 'Ondřej Kriška',
            'role'     => 'Web developer · custom websites for B2B services',
            'bio'      => 'I build websites that open business conversations. Custom code, fixed price up front, direct contact.',
            'linkedin_label' => 'LinkedIn',
            'linkedin_url'   => 'https://www.linkedin.com/in/ondrejkriska/',
            'contact_cta'    => 'Get a no-strings quote',
        ],
    ],


    'cta' => [
        'heading' => 'Need help with your website?',
        'text'    => 'Let\'s talk about how your website can bring more inquiries.',
        'primary' => 'Book a consultation',
    ],

    'audit' => [
        'subheading'    => 'Quick conversion fix',
        'heading'       => 'Get a concise audit of your website',
        'items'         => [
            'Top 3 issues that are currently losing you inquiries.',
            'Specific recommendations on what to fix first.',
            'Priority roadmap without cosmetic extras.',
        ],
        'cta_primary'   => 'I want a free quick audit',
        'cta_secondary' => 'See pricing first',
    ],

];
