<?php

return [

    'meta' => [
        'title'       => 'Pricing — Ondřej Kriška',
        'description' => 'Indicative pricing for websites, e-shops and web applications. Get a clear picture of your investment before the first consultation.',
    ],

    'subheading' => 'Indicative pricing',
    'heading'    => 'You know what you\'re getting into before our first meeting.',
    // OND-198 (finding 5.4): the expectation sentence must land before the first number.
    'intro'      => 'Most projects I build land between €2,200 and €6,000. If you are looking for a website under €800, I am not the right supplier for you and I will tell you so straight away. Every project is different — you\'ll get the final price after a free consultation. This overview gives you a clear idea before we even meet.',

    // OND-135 P2 iter 5 — plan §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-
    // portfolio artefact per CEO PR #78 precedent (home / contact).
    'hero' => [
        'page_mark_label' => 'PRICING',
        'upline'          => 'No "request a quote" mystery.',
        // OND-198 (finding 5.4): Standard leads the subline, not the cheapest band.
        'heading_html'    => 'Three levels,<br>one <em>clear price</em>.',
        'subline'         => 'The invoice matches the spec. No extra costs without your agreement.',
    ],

    // Sticky CTA — always-visible while scrolling, "price never disappears".
    'sticky_cta' => [
        'label' => 'Pick a level',
        'cta'   => 'Get a free quote',
    ],

    'popular'   => 'Most popular',
    'quotation' => 'Get a quote',

    'price_note' => 'indicative price',

    // OND-136: tier names and prices aligned with CS taxonomy
    // Startovní/Standard/Custom = 25/55/95 thousand CZK → EUR conversion (CEO-confirmed 1:25 anchor).
    'tiers' => [
        [
            'name'    => 'Standard',
            'desc'    => 'For businesses that want their website to be their best sales tool.',
            'price'   => '€2,200',
            'popular' => true,
            'features' => [
                'Up to 12 custom pages',
                'Conversion-focused design',
                'Blog or gallery with content editing',
                'Multilingual website',
                'Analytics and conversion tracking',
                'Hosting and domain for 1 year free',
                '1 month of post-launch support',
            ],
            'cta' => 'Get a free quote',
        ],
        [
            'name'    => 'Custom',
            'desc'    => 'For demanding projects without compromise — e-shop, booking system or web application.',
            'price'   => 'from €3,800',
            'popular' => false,
            'features' => [
                'Unlimited project scope',
                'E-shop or booking system',
                'Custom administration interface',
                'Advanced SEO strategy with reporting',
                'External system integrations',
                '3 months of post-launch support',
            ],
            'cta' => 'Get a free quote',
        ],
        [
            'name'    => 'Starter',
            // OND-198 (finding 5.4): cheapest band is last and framed as an exception.
            'desc'    => 'An exception, not the standard entry point. For sole traders where a larger scope makes no sense — a credible online presence up to 5 pages.',
            'price'   => '€1,000',
            'popular' => false,
            'features' => [
                'Up to 5 custom pages',
                'Modern responsive design',
                'Contact form',
                'Technical SEO',
                'Page speed optimisation',
                '14 days of post-launch support',
            ],
            'cta' => 'Get a free quote',
        ],
    ],

    'note' => 'Not VAT-registered — these prices are final, nothing is added.',

    'guarantees' => [
        'heading' => 'What is included in every project',
        'items'   => [
            [
                'title' => 'Maintenance-free websites',
                'text'  => 'No WordPress, no third-party plugins. Save thousands per year compared to WordPress — no monthly updates and no security patching costs.',
            ],
            [
                'title' => 'Fixed price, no surprises',
                'text'  => 'You receive an exact quote before work begins. What is in the quote is on the invoice — no extra costs without your consent.',
            ],
            [
                'title' => 'Direct communication',
                'text'  => 'You talk directly to me — no account managers, no project coordinators. One point of contact, one point of responsibility.',
            ],
            [
                'title' => 'Support after launch',
                'text'  => 'I respond within 24 hours, even weeks and months after project delivery. Minor adjustments and technical questions are always welcome.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Additional services',
        'desc'    => 'Comprehensive digital support even after your project launches.',
        'items'   => [
            [
                'name'  => 'SEO & content marketing',
                'price' => 'from €180 / mo.',
                'desc'  => 'Keyword analysis, content strategy, performance monitoring. Organic visibility that works even without an advertising budget.',
            ],
            [
                'name'  => 'Social media management',
                'price' => 'from €400 / mo.',
                'desc'  => 'Content creation, scheduling and publishing. Consistent presence that builds customer trust.',
            ],
            [
                'name'  => 'Custom web application',
                'price' => 'custom quote',
                'desc'  => 'Inventory systems, internal tools, customer portals. Price reflects the complexity and scope of the project.',
            ],
            [
                'name'  => 'Graphic design & branding',
                'price' => 'from €190',
                'desc'  => 'Logo, visual identity, banners. Everything you need for a consistent and memorable brand presentation.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'What exactly you get',
        'tiers'   => ['Standard', 'Custom', 'Starter'],
        'tabs_aria'     => 'Select pricing level',
        'included'      => 'Included',
        'not_included'  => 'Not included',
        'groups'  => [
            [
                'label' => 'Project scope',
                'rows'  => [
                    ['label' => 'Number of pages', 'values' => ['up to 12', 'unlimited', 'up to 5']],
                    ['label' => 'Responsive design', 'values' => [true, true, true]],
                    ['label' => 'Contact form', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Website features',
                'rows'  => [
                    ['label' => 'Blog or gallery with editing', 'values' => [true, true, false]],
                    ['label' => 'Multilingual website', 'values' => [true, true, false]],
                    ['label' => 'Booking system', 'values' => ['optional', true, false]],
                    ['label' => 'E-shop', 'values' => [false, true, false]],
                    ['label' => 'Custom administration', 'values' => [false, true, false]],
                    ['label' => 'External system integrations', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Marketing & performance',
                'rows'  => [
                    ['label' => 'Technical SEO', 'values' => [true, true, true]],
                    ['label' => 'Page speed optimisation', 'values' => [true, true, true]],
                    ['label' => 'Analytics & conversion tracking', 'values' => [true, true, false]],
                    ['label' => 'Advanced SEO strategy', 'values' => [false, true, false]],
                ],
            ],
            [
                'label' => 'Service & support',
                'rows'  => [
                    ['label' => 'Free hosting and domain', 'values' => ['1 year', '1 year', false]],
                    ['label' => 'Post-launch support', 'values' => ['1 month', '3 months', '14 days']],
                    ['label' => 'Maintenance-free operation', 'values' => [true, true, true]],
                ],
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Not sure what you need?',
        'desc'    => 'The consultation is free and non-binding. In 30 minutes I will tell you what makes sense for your business — honestly, even if that means we should not work together.',
        'btn'     => 'Book a free consultation',
    ],

];
