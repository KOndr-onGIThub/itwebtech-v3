<?php

return [

    'meta' => [
        'title'       => 'Pricing — Ondřej Kriška',
        'description' => 'Indicative pricing for websites, e-shops and web applications. Get a clear picture of your investment before the first consultation.',
    ],

    'subheading' => 'Indicative pricing',
    'heading'    => 'You know what you\'re getting into before our first meeting.',
    // OND-198 (finding 5.4): the expectation sentence must land before the first number.
    // OND-354: threshold plus range instead of a menu of three packages
    // (Ondřej, 26 Sep 2026 on OND-347); the rejection sentence is gone with it.
    // The CZK floor (20,000) is deliberately NOT converted — see lang/en/home.php.
    'intro'      => 'Most projects land between €2,200 and €6,000. The smallest site I build is a presentation site of up to five pages. What goes on the site and what it costs, you get in writing before I start — and that number is what the invoice says.',

    // OND-135 P2 iter 5 — plan §3.1 hero (page-mark + amber accent).
    // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-
    // portfolio artefact per CEO PR #78 precedent (home / contact).
    'hero' => [
        'page_mark_label' => 'PRICING',
        'upline'          => 'No "request a quote" mystery.',
        // OND-354: "Three levels, one clear price" stopped being true — after
        // this change there are no price bands.
        'heading_html'    => 'What a custom<br><em>website costs</em>.',
        'subline'         => 'The invoice matches the spec. No extra costs without your agreement.',
    ],

    // Sticky CTA — always-visible while scrolling, "price never disappears".
    'sticky_cta' => [
        'label' => 'Pick a level',
        'cta'   => 'Get a free quote',
    ],

    'popular'   => 'Most popular',
    'quotation' => 'Get a quote',

    // OND-354: levels are named after SCOPE, not a price band, and carry no
    // price — the `price` key is gone (and with it `price_note`, which had
    // nothing left to describe). `key` is a technical id for analytics
    // (dimension `pricing_tier_shown`, previously derived from the price
    // digits); it is never rendered. Names and `desc` match the homepage
    // anchor (`home.price_anchor.items`); feature lists are unchanged.
    'tiers' => [
        [
            'key'     => 'presentation',
            'name'    => 'Presentation site',
            'scope'   => 'up to 5 pages',
            'desc'    => 'A credible online presence for sole traders and small businesses.',
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
        [
            'key'     => 'business',
            'name'    => 'Business site',
            'scope'   => 'up to 12 pages',
            'desc'    => 'A multilingual site with a blog, conversion tracking and a booking system.',
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
            'key'     => 'custom',
            'name'    => 'Custom',
            'scope'   => 'no scope limit',
            'desc'    => 'An e-shop, a web application or a complex portal.',
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
    ],

    // OND-354: replaces the apologetic "An exception, not the standard entry
    // point." on the lowest level. It does not turn the person away, it says
    // what the money does not buy.
    'entry_note' => 'The smallest site I build has up to five pages. It will be fast, it will work properly on a phone, and no link to your enquiry form will be broken. Don\'t expect it to start bringing in work on its own — that takes more work than the smallest scope allows. But it will be done properly.',

    'note' => 'I am not registered for VAT — the prices above are final, no VAT is added.',

    'guarantees' => [
        'heading' => 'What is included in every project',
        'items'   => [
            [
                'title' => 'Maintenance-free websites',
                'text'  => 'No WordPress, no third-party plugins. Save hundreds of euros a year compared to WordPress — no monthly updates and no security patching bills.',
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
                'text'  => 'Even weeks or months after your project is delivered, I\'ll still get back to you by the next business day. Minor adjustments and technical questions are always welcome.',
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

    // OND-354: the table axis changes — it used to compare three named price
    // bands, and those are gone. See lang/cs/price.php for the reasoning.
    'compare' => [
        'heading' => 'What raises the price and what lowers it',
        'up'   => [
            'label' => 'Raises the price',
            'items' => [
                'More than five pages',
                'A second and further languages',
                'An e-shop or a booking system',
                'Your own content administration',
                'Integration with systems you already use',
                'Copy and photos that need to be created',
            ],
        ],
        'down' => [
            'label' => 'Lowers the price',
            'items' => [
                'Your copy and photos are ready',
                'Fewer pages',
                'One language',
                'You fill in the content yourself after training',
            ],
        ],
    ],

    'cta' => [
        'heading' => 'Not sure what you need?',
        'desc'    => 'The consultation is free and non-binding. In 30 minutes I will tell you what makes sense for your business — honestly, even if that means we should not work together.',
        'btn'     => 'Book a free consultation',
    ],

];
