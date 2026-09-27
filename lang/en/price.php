<?php

return [

    'meta' => [
        'title'       => 'Pricing — Ondřej Kriška, ONDRAWEB',
        'description' => 'Indicative pricing for websites, e-shops and web applications. Get a clear picture of your investment before the first consultation.',
    ],

    'subheading' => 'Indicative pricing',
    'heading'    => 'You know what you\'re getting into before our first meeting.',
    // OND-198 (finding 5.4): the expectation sentence must land before the first number.
    // OND-354: threshold plus range instead of a menu of three packages
    // (Ondřej, 26 Sep 2026 on OND-347); the rejection sentence is gone with it.
    // The CZK floor (20,000) is deliberately NOT converted — see lang/en/home.php.
    'intro'      => 'Most projects land between €3,500 and €8,000. The smallest site I build is a presentation site of up to five pages, from €1,900. What goes on the site and what it costs, you get in writing before I start — and that number is what the invoice says.',

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
    // OND-391: nevykresluje se (plovoucí tlačítko z /cenik odešlo, OND-393).
    'sticky_cta' => [
        'label' => 'Pick a level',
        'cta'   => 'Get a no-obligation quote',
    ],

    'popular'   => 'Most popular',
    // OND-391: `quotation` se nevykresluje (tlačítka u doplňků odešla, OND-393).
    'quotation' => 'Get a quote',

    // OND-359: intro for the case-study link inside a level card. The wording
    // matches `projects.snapshots.subheading` so the destination is no surprise.
    'proof_intro' => 'Case study',

    // OND-354: levels are named after SCOPE, not a price band, and carry no
    // price — the `price` key is gone (and with it `price_note`, which had
    // nothing left to describe). `key` is a technical id for analytics
    // (dimension `pricing_tier_shown`, previously derived from the price
    // digits); it is never rendered. Names and `desc` match the homepage
    // anchor (`home.price_anchor.items`); feature lists are unchanged.
    //
    // OND-359: `proof` is a link to a real case study — the price explained by
    // an example instead of a feature list (brief in the document on [OND-347],
    // section 4.3). `slug` is the language-neutral `portfolio_projects.slug`;
    // none of these three projects has a localised slug, so the address is the
    // same in every language and `detailUrl()` prefixes it per locale
    // (/projekty, /en/projects, /de/projekte). `label` repeats the title the
    // case study itself carries in its heading.
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
            'proof' => [
                'slug'  => 'vanspedition',
                'label' => 'VAN spedition',
            ],
            'cta' => 'Get a no-obligation quote',
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
            'proof' => [
                'slug'  => 'zubni-provazek',
                'label' => 'Zubní Provázek',
            ],
            'cta' => 'Get a no-obligation quote',
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
            'proof' => [
                'slug'  => 'pitarena',
                'label' => 'PitArena',
            ],
            'cta' => 'Get a no-obligation quote',
        ],
    ],

    // OND-354: replaces the apologetic "An exception, not the standard entry
    // point." on the lowest level. It does not turn the person away, it says
    // what the money does not buy.
    'entry_note' => 'For €1,900 I\'ll build you a site of up to five pages. It will be fast, it will work properly on a phone, and no link to your enquiry form will be broken. Don\'t expect it to start bringing in work on its own — that takes more work than the smallest scope allows. But it will be done properly.',

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
                'price' => 'from €490 / mo.',
                'desc'  => 'Keyword analysis, content strategy, performance monitoring. Organic visibility that works even without an advertising budget.',
            ],
            [
                'name'  => 'Social media management',
                'price' => 'from €590 / mo.',
                'desc'  => 'Content creation, scheduling and publishing. Consistent presence that builds customer trust.',
            ],
            [
                'name'  => 'Custom web application',
                'price' => 'custom quote',
                'desc'  => 'Inventory systems, internal tools, customer portals. Price reflects the complexity and scope of the project.',
            ],
            [
                'name'  => 'Graphic design & branding',
                'price' => 'from €890',
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
        // OND-369: `desc` is the sub-label of the same call to action as the
        // button, not prose elsewhere — so "free" goes with the label. Pricing
        // (OND-354) rests on a threshold number; a free meeting next to it
        // promises something different. Non-binding and 30 minutes stay.
        'desc'    => 'The consultation is non-binding. In 30 minutes I will tell you what makes sense for your business — honestly, even if that means we should not work together.',
        'btn'     => 'Write an enquiry',
    ],

];
