<?php

return [

    'meta' => [
        'title'       => 'Pricing — Ondřej Kriška',
        'description' => 'Indicative pricing for websites, e-shops and web applications. Get a clear picture of your investment before the first consultation.',
    ],

    'subheading' => 'Indicative pricing',
    'heading'    => 'You know what you\'re getting into before our first meeting.',
    'intro'      => 'Every project is different — you\'ll get the final price after a free consultation. This overview gives you a clear idea of how much it will cost before we even meet.',

    'popular'   => 'Most popular',
    'quotation' => 'Get a quote',

    'price_note' => 'indicative price',

    'tiers' => [
        [
            'name'    => 'Presentation',
            'desc'    => 'For freelancers and small businesses that need a credible online presence.',
            'price'   => 'from €800',
            'popular' => false,
            'features' => [
                'Up to 5 custom pages',
                'Modern responsive design',
                'Contact form',
                'Technical SEO',
                'Page speed optimisation',
                '14 days of post-launch support',
            ],
            'cta' => 'Interested — book a consultation',
        ],
        [
            'name'    => 'Professional',
            'desc'    => 'For businesses that want their website to be their best sales tool.',
            'price'   => 'from €1,800',
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
            'cta' => 'I want this plan — free consultation',
        ],
        [
            'name'    => 'Complex',
            'desc'    => 'For demanding projects without compromise — e-shop, booking system or web application.',
            'price'   => 'from €3,400',
            'popular' => false,
            'features' => [
                'Unlimited project scope',
                'E-shop or booking system',
                'Custom administration interface',
                'Advanced SEO strategy with reporting',
                'External system integrations',
                '3 months of post-launch support',
            ],
            'cta' => 'Free consultation',
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
        'tiers'   => ['Presentation', 'Professional', 'Complex'],
        'groups'  => [
            [
                'label' => 'Project scope',
                'rows'  => [
                    ['label' => 'Number of pages', 'values' => ['up to 5', 'up to 12', 'unlimited']],
                    ['label' => 'Responsive design', 'values' => [true, true, true]],
                    ['label' => 'Contact form', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Website features',
                'rows'  => [
                    ['label' => 'Blog or gallery with editing', 'values' => [false, true, true]],
                    ['label' => 'Multilingual website', 'values' => [false, true, true]],
                    ['label' => 'Booking system', 'values' => [false, 'optional', true]],
                    ['label' => 'E-shop', 'values' => [false, false, true]],
                    ['label' => 'Custom administration', 'values' => [false, false, true]],
                    ['label' => 'External system integrations', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Marketing & performance',
                'rows'  => [
                    ['label' => 'Technical SEO', 'values' => [true, true, true]],
                    ['label' => 'Page speed optimisation', 'values' => [true, true, true]],
                    ['label' => 'Analytics & conversion tracking', 'values' => [false, true, true]],
                    ['label' => 'Advanced SEO strategy', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Service & support',
                'rows'  => [
                    ['label' => 'Free hosting and domain', 'values' => [false, '1 year', '1 year']],
                    ['label' => 'Post-launch support', 'values' => ['14 days', '1 month', '3 months']],
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
