<?php

return [

    'meta' => [
        'title'       => 'Pricing — Ondřej Kriška',
        'description' => 'Indicative price bands for websites, e-shops and custom web applications.',
    ],

    'subheading' => 'Indicative pricing',
    'heading'    => 'Clear pricing for every project',
    'intro'      => 'Every project is different — the final price is agreed upfront. This overview gives you an idea of price bands. I am not the cheapest and do not aim to be. If you are looking for a website under a certain budget, I will tell you straight away that I am probably not the right fit.',

    'popular'   => 'Most popular',
    'quotation' => 'Non-binding enquiry',

    'price_note' => 'indicative price',

    'tiers' => [
        [
            'name'    => 'Business website',
            'desc'    => 'For sole traders and small businesses that need a credible online presence.',
            'price'   => '50,000–90,000 CZK',
            'popular' => false,
            'features' => [
                'Custom website, usually up to 5 pages',
                'Design tailored to your business',
                'Looks great on mobile and desktop',
                'Contact form',
                'Technical SEO foundations',
                'Fast loading',
                '14 days of post-launch support',
            ],
            'cta' => 'Request a project',
        ],
        [
            'name'    => 'Website with CMS',
            'desc'    => 'For businesses that want to manage content themselves or need a multilingual website.',
            'price'   => '90,000–150,000 CZK',
            'popular' => true,
            'features' => [
                'Larger custom website',
                'Simple content management (texts, photos, products)',
                'Blog or gallery',
                'Multilingual website',
                'Analytics integration',
                'Hosting and domain for 1 year free',
                '1 month of post-launch support',
            ],
            'cta' => 'Choose this plan',
        ],
        [
            'name'    => 'E-shop / application',
            'desc'    => 'For more demanding projects — e-shop, booking system or custom internal application.',
            'price'   => 'from 150,000 CZK',
            'popular' => false,
            'features' => [
                'Scope according to project requirements',
                'E-shop or booking system',
                'Custom administration interface',
                'Custom internal application tailored to your operations',
                'Integration with other systems you use',
                '3 months of post-launch support',
            ],
            'cta' => 'Request a project',
        ],
    ],

    'note' => 'Not VAT registered. Prices are final.',

    'guarantees' => [
        'heading' => 'What is included in every project',
        'items'   => [
            [
                'title' => 'Maintenance-free websites',
                'text'  => 'No WordPress, no third-party plugins. No costs for regular updates and security patches.',
            ],
            [
                'title' => 'Fixed price, no surprises',
                'text'  => 'You receive an exact quote before work begins. What is in the quote is on the invoice — no extra costs without your consent.',
            ],
            [
                'title' => 'Direct communication',
                'text'  => 'You talk directly to me — no salespeople, no project managers, no coordinators. One point of contact, one point of responsibility.',
            ],
            [
                'title' => 'Support after launch',
                'text'  => 'I respond on business days, usually within two working days, even weeks and months after project delivery. Minor adjustments and technical questions are always welcome.',
            ],
        ],
    ],

    'addons' => [
        'heading' => 'Additional services',
        'desc'    => 'Digital support even after your project launches.',
        'items'   => [
            [
                'name'  => 'SEO & content',
                'price' => 'from 4,500 CZK / mo.',
                'desc'  => 'Keeping the website performing well in search engines, and regular content.',
            ],
            [
                'name'  => 'Social media management',
                'price' => 'from 9,900 CZK / mo.',
                'desc'  => 'Content creation, scheduling and publishing so the company presents itself consistently beyond the website.',
            ],
            [
                'name'  => 'Custom web application',
                'price' => 'individual quote',
                'desc'  => 'Inventory management, internal systems, customer portals. Price reflects the scope of the project.',
            ],
            [
                'name'  => 'Graphics & branding',
                'price' => 'from 4,800 CZK',
                'desc'  => 'Logo, visual identity, banners. For a cohesive and memorable brand presentation.',
            ],
        ],
    ],

    'compare' => [
        'heading' => 'What exactly you get',
        'tiers'   => ['Business website', 'Website with CMS', 'E-shop / application'],
        'groups'  => [
            [
                'label' => 'Project scope',
                'rows'  => [
                    ['label' => 'Number of pages', 'values' => ['up to 5', 'more', 'unlimited']],
                    ['label' => 'Custom design', 'values' => [true, true, true]],
                    ['label' => 'Contact form', 'values' => [true, true, true]],
                ],
            ],
            [
                'label' => 'Website features',
                'rows'  => [
                    ['label' => 'Content management (blog, gallery)', 'values' => [false, true, true]],
                    ['label' => 'Multilingual website', 'values' => [false, true, true]],
                    ['label' => 'Booking system', 'values' => [false, 'optional', true]],
                    ['label' => 'E-shop', 'values' => [false, false, true]],
                    ['label' => 'Custom administration', 'values' => [false, false, true]],
                    ['label' => 'Integration with other systems', 'values' => [false, false, true]],
                ],
            ],
            [
                'label' => 'Search engines & performance',
                'rows'  => [
                    ['label' => 'Technical SEO foundations', 'values' => [true, true, true]],
                    ['label' => 'Fast loading', 'values' => [true, true, true]],
                    ['label' => 'Analytics tracking', 'values' => [false, true, true]],
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
        'heading' => 'Not sure exactly what you need?',
        'desc'    => 'Write to me and tell me what you are dealing with. I will get back to you and tell you honestly what makes sense for your business — including whether working together makes sense at all.',
        'btn'     => 'Send a message',
    ],

];
