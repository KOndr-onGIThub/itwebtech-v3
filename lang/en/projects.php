<?php

return [

    'meta' => [
        'title'       => 'What I have built | ONDRAWEB',
        'description' => 'Websites, online shops and web applications I have built and that are running today. Each one links to the live version, so you can check it yourself.',
    ],

    'subheading'       => 'Implemented',
    'heading'          => 'PROJECTS',
    // OND-201 (item 5): intro per section 4 of the texty-podstranky document (OND-186).
    'intro'            => 'Here are the websites I have built and that are running today. They are not pictures in a gallery — you can click each one and see how it works live. I would rather show finished work than promises.',

    // OND-135 P2 iter 6 — plan §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-
    // portfolio artefact per CEO PR #78/#80/#82 precedent (home/contact/pricing).
    // OND-201 (finding 5.2, CRITICAL): the hero promised "shipped numbers"
    // and "no screenshots without numbers" — a promise the page broke two
    // paragraphs below, because hard numbers are not available for every
    // project. Reworded to proof that can actually be shown: what the site
    // does, a live link, the scope. No invented numbers.
    'hero' => [
        'page_mark_label' => 'PROJECTS',
        'upline'          => 'Live websites, not pictures in a gallery.',
        'heading_html'    => 'What I have<br><em>built</em>.',
        'subline'         => 'For every project you can read what the site does and the scope I built it in. The link goes to the live version — check it yourself.',
    ],

    'filter_all'       => 'All',
    'filter_websites'  => 'Websites',
    'filter_webapps'   => 'Web apps',
    'filter_other'     => 'Others',
    'filter_aria'      => 'Filter projects by category',
    'count_label'      => 'projects shown',

    'info_client'      => 'Client',
    'info_date'        => 'Date',
    'info_categories'  => 'Categories',
    'info_price'       => 'Indicative Price',

    'why_me' => [
        'subheading' => 'This is how I do it',
        'heading'    => 'I put the following into projects',
        'items'      => [
            ['title' => 'Expertise and practice',  'description' => 'Thanks to 18 years of experience at Toyota, I have unique expertise in process optimisation and web application development.'],
            ['title' => 'Stability and robustness','description' => 'I don\'t build websites from third-party add-ons that break with the next update. I write my own code that holds up.'],
            ['title' => 'Thorough testing',        'description' => 'I leave nothing to chance. I test apps and websites during development and after completion.'],
            ['title' => 'Speed and design',        'description' => 'Fast loading and modern design come first — they ensure a positive first impression and a pleasant user experience.'],
            ['title' => 'Customised solutions',    'description' => 'Every project is unique to me and I always look for the best solution adapted to each client\'s specific needs and goals.'],
            ['title' => 'Emphasis on detail',      'description' => 'I always pay close attention to details that can be decisive for the success of your project.'],
        ],
    ],

    'cta_all' => 'Check out my other projects',

    // OND-201 (finding 5.2, CRITICAL): this section used to show three
    // anonymous "result snapshots" with invented timelines (4/6/7 weeks)
    // and undocumented impact. Replaced with the real case studies from
    // the `pripadovky` document (OND-186) — verified on the live sites,
    // no invented numbers. Ondra confirmed consent for the Toyota TSM saving.
    'snapshots' => [
        'subheading' => 'Case studies',
        'heading'    => 'Four projects up close',
        'desc'       => 'For each one you can read what the client came with, what I built and what the site does. Where the site is public, the link goes to the live version.',
        'live_label' => 'Live site',
        'items'      => [
            [
                'type'     => 'Online shop — motorbikes and spare parts',
                'domain'   => 'shop.pitarena.cz',
                'url'      => 'https://shop.pitarena.cz',
                'title'    => 'PitArena',
                'summary'  => 'The client sells YCF pit bikes and spare parts. They needed to sell online — and with parts, getting the right piece for a given model and year is what matters. I built an online shop with a catalogue of both bikes and parts, sorted by model and part group.',
                'outcomes' => [
                    'Cart and customer account.',
                    'Categories by model (LITE 125, PILOT 125, Factory 190) and by part group — brakes, engines, suspension, electrics.',
                    'Filtering by model and year.',
                    'Favourites and product comparison.',
                    'Clear navigation across a large range.',
                ],
            ],
            [
                'type'     => 'Brochure site — aluminium structures',
                'domain'   => 'barana.cz',
                'url'      => 'https://barana.cz',
                'title'    => 'BARANA',
                'summary'  => 'The client makes custom aluminium pergolas, gates and fences. They needed a site that explains clearly what they do and makes it easy to get in touch. I built a brochure site with services, a gallery of finished work and an enquiry form.',
                'outcomes' => [
                    'Services split out — bioclimatic pergolas, gates and fences, custom design.',
                    'Gallery of finished installations.',
                    'Enquiry form and contact details.',
                    'A "How it works" section.',
                    'Clean, uncluttered design.',
                ],
            ],
            [
                'type'     => 'Brochure site with online booking — dentistry',
                'domain'   => 'zubniprovazek.cz',
                'url'      => 'https://zubniprovazek.cz',
                'title'    => 'Provázek dental practice',
                'summary'  => 'The client runs a dental practice for adults and children. They needed a site with practice information and, above all, easy online booking. I built a brochure site with online booking.',
                'outcomes' => [
                    'Online booking.',
                    'Overview of treatments — prevention, dental hygiene, whitening, restorative care, prosthetics and implants, paediatric dentistry.',
                    'Price list.',
                    'About, contact, opening hours and location.',
                    'Clear, friendly design.',
                ],
            ],
            [
                'type'     => 'Internal application — logistics',
                'domain'   => null,
                'url'      => null,
                'title'    => 'Toyota — the TSM application',
                'summary'  => 'A web application built around real logistics processes, which I programmed during my eighteen years at Toyota. It replaced lengthy manual work and saved the company an amount in the order of millions of Czech koruna.',
                'outcomes' => [
                    'Built around the real logistics process, not a generic tool.',
                    'Replaced lengthy manual work.',
                    'Savings in the order of millions of Czech koruna.',
                    'An internal system — not publicly available, hence no link.',
                ],
            ],
        ],
    ],

    'fit' => [
        'subheading'    => 'Quick qualification',
        'heading'       => 'Does it make sense to solve now?',
        'items'         => [
            'Your website has traffic but inquiries are inconsistent.',
            'Your service offer is unclear or buried in content.',
            'There is no clear next step after inquiry submission.',
            // OND-201 (finding 5.1): "a business tool" implied a result I
            // cannot deliver on the client's behalf.
            'You do not want another "nice website" but something built around how your company actually works.',
        ],
        'cta_heading'   => 'If 2+ points match, this is worth solving now.',
        'cta_text'      => 'In the intro call, we define the shortest path to a working solution without unnecessary extras.',
        'cta_primary'   => 'Book consultation',
        'cta_secondary' => 'See pricing first',
    ],

    'empty'            => 'No projects are currently available.',
    'view_project'     => 'View project',

    // OND-265: alt for project thumbnails on the listing and in "More projects".
    'card' => [
        'thumbnail_alt' => 'Preview of the :project project',
    ],
    'back_to_projects' => '← Back to projects',

    'before_after' => 'Before & After comparison',
    'before'       => 'Before',
    'after'        => 'After',
    'screenshots'  => 'Project screenshots',

    'detail' => [
        'challenge'       => 'Challenge',
        'solution'        => 'Solution',
        'result'          => 'Result',
        'no_content'      => 'A detailed write-up for this project is not available yet.',
        'related_heading' => 'More projects',
        'visit_live'      => 'Visit live site',
        'meta'            => [
            'client'   => 'Client',
            'year'     => 'Year',
            'duration' => 'Duration',
            'category' => 'Category',
            'live_url' => 'Live site',
            'tags'     => 'Tech stack',
        ],
        'category_label'  => [
            'website'     => 'Website',
            'application' => 'Web application',
            'other'       => 'Other',
        ],
    ],

    'cta' => [
        'heading' => 'Want a similar result for your business?',
        'primary' => 'Book a consultation',
    ],

];
