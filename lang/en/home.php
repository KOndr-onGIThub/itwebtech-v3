<?php

return [

    // OND-201 (finding 5.7): the page title must not define the business by
    // negating competitors, and "no WordPress" says nothing to someone who
    // does not know what WordPress is (principle 0).
    'meta' => [
        'title'       => 'Custom websites and web applications | ONDRAWEB',
        'description' => 'Custom websites, online shops and web applications for small and mid-sized companies. Custom code, an exact price up front, and you deal with me directly. I am Ondřej Kriška.',
    ],

    // TODO (OND-136 P3): final EN tone polish — Content Writer scope.
    'hero' => [
        // OND-127 P0 incident hotfix (2026-05-14) — plagiátor strings removed.
        // Placeholder copy derived from meta description = pre-redesign safe copy.
        // FINAL COPY: Content Writer delivers in OND-136 P3 (SLA 2h from 11:10 UTC).
        'page_mark_label' => 'CUSTOM WEB',
        // OND-145 P0.3: page_mark_index removed — agency-portfolio pagination
        // artefact, itwebtech has no „pages" hierarchy in hero context.
        // OND-198 (finding 5.1): the previous headline promised the client's
        // business result. Replaced with the approved hero copy (CS source of truth).
        'upline'          => 'For businesses that know the difference.',
        'heading_html'    => 'Websites and applications <em>built to fit</em>.<br>I build them myself, on my own code.',
        'subline'         => 'I am Ondřej Kriška, an experienced developer. You work with me directly — no agency, no middlemen. I build websites to run for years without tying you up in maintenance.',
        'note'            => 'I\'ll get back to you within 24 hours on business days. No commitment, we just go through what makes sense.',

        // Backwards compat (fallback render).
        'eyebrow'       => 'Custom websites & web applications',
        'heading'       => 'Websites and applications built to fit. I build them myself, on my own code.',
        // OND-130 + OND-136: single primary CTA in hero, exact wording per spec.
        // cta_secondary kept for backwards compat — not shown in hero.
        'cta_primary'   => 'Tell me what you need',
        'cta_secondary' => 'Book a 30-min consultation',
        'phone_label'   => 'or call:',
    ],

    'anchors' => [
        'how_i_work' => 'how-i-work',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        'rating_aria'  => '5 out of 5 rating',
        'clients_aria' => 'Clients',
        'rating_value' => '5.0',
        'reviews'      => '(21 reviews on Google + Firmy.cz)',
        'projects'     => '23+ delivered projects',
        'experience'   => '18 years of experience',
        'response'     => 'Reply within 24 hours on business days',
        // OND-201 (finding 5.11): TOP firma 2025 award from Firmy.cz —
        // verifiable third-party proof that was missing on staging.
        'award'        => 'TOP firma 2025 on Firmy.cz',
        'brands' => [
            ['name' => 'MAKOplast',             'image' => null],
            ['name' => 'Yolk Studio',           'image' => 'yolk_studio.png'],
            ['name' => 'BARANA s.r.o.',         'image' => null],
            ['name' => 'Nové Interiéry s.r.o.', 'image' => null],
            ['name' => 'Zubní Provázek',        'image' => null],
            ['name' => 'Autokemp Veselka',      'image' => null],
            ['name' => 'PitAréna',              'image' => 'pitarena.png'],
        ],
    ],

    // OND-202: work samples as the primary visual material (live client sites).
    // OND-269 (audit OND-254, finding 7): the `showcase` block ("Websites
    // running in the real world" — three tiles linking to the live site) is
    // gone. Two project sections said the same thing and BARANA and PitArena
    // were in both. Merged into the single `portfolio` section below, which
    // took over the heading from here and gained a link to the live site
    // (`live_cta` / `live_aria`).

    // OND-201 (finding 5.7): the section used to define the business by
    // negating competitors and two of three items said the same thing.
    // What I do now leads (`lead`), the delimitation is short, and the
    // duplicate items are merged into one.
    'problems' => [
        'heading'            => 'How I build websites',
        'lead'               => 'Every project starts with understanding your business. I write custom code from the ground up, so the site follows how your company actually works. You speak directly with me from the first message through launch and beyond.',
        'transition_heading' => 'What that spares you',
        'transition_text'    => 'The two things I see most often on web projects.',
        'items' => [
            [
                'heading'      => 'A template sold as a custom solution',
                'text'         => 'A supplier reuses a layout they have already used five times and drops in your text and logo. The result looks professional — until you open a competitor\'s website and find the same sections and the same words. On top of that, the platform keeps you on a monthly subscription you cannot take with you.',
                'quote_text'   => 'Unlike the would-be web designers who just pour your content into a template and charge outrageous fees.',
                'quote_author' => 'Petr Kroulík, Nové Interiéry s.r.o.',
            ],
            [
                'heading' => 'You never speak with the person who builds the site',
                'text'    => 'The person selling you the website isn\'t building it. The people building it aren\'t talking to you. Context and intent get lost in the middle — and the result doesn\'t match what you wanted.',
            ],
        ],
        // OND-269 (audit OND-254, finding 7): what is left of the retired
        // "Generator versus your business" section. Same argument as the first
        // item above ("a template sold as a custom build"), so it belongs here
        // rather than in a section of its own four screens further down.
        'ai_heading' => 'A template is quick to build. Leads aren\'t.',
        'ai_text'    => 'A generator can click a layout together and drop in text and images — what it cannot do is work out who you sell to, why a customer should choose you, or where prospects drop off. I use AI as a tool; the decisions about what the site should say and in what order are not something it can make for you.',
    ],

    'how_i_work' => [
        'heading'   => 'From first message to a launched website — 4 clear steps.',
        'cta_intro' => 'Let\'s jump straight to step 1.',
        'cta_label' => 'Book a consultation',
        'steps'   => [
            [
                'heading'      => 'Consultation',
                'time'         => '60 min, within a week',
                'text'         => 'I start with a consultation, not a form. I need to understand your business, your customers and what the website should actually do — bring contacts, sell a product or build trust.',
                'quote_text'   => 'He really listened to what I needed and then turned it into something I am completely happy with.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Specification',
                'time'         => '2–5 days',
                'text'         => 'Before I start working, you\'ll receive a written specification: what will be on the website, how many pages, what technology and how much it will cost. No surprises on the invoice. I\'ll estimate the delivery date realistically — always upfront, never retrospectively.',
                'quote_text'   => 'He analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading.',
                'quote_author' => 'Jan Stybor, Head of Project Department, Toyota',
                'note'         => 'Note on timelines: a website doesn\'t only depend on my side. Approvals, materials from the client and feedback are part of the process. The timeline is always an estimate, not a binding commitment — and I say that openly from the start.',
            ],
            [
                'heading' => 'Build',
                'time'    => '3–10 weeks',
                'text'    => 'I keep you informed about progress and involve you in key decisions. The result reflects what you wanted — because I don\'t wait until the end of the project to find out.',
            ],
            [
                'heading' => 'Launch and support',
                'time'    => 'by the next business day',
                'text'    => 'Once you approve deployment, the site usually goes live within one business day. After launch I stay available — small tweaks, technical questions and analytics help go through me directly, with no ticket and no waiting.',
                'note'    => 'Launch within 1 business day of approval.',
            ],
        ],
    ],

    // OND-269 (audit OND-254, finding 7): the "Generator versus your business"
    // section (two columns, eight bullets) is gone — the board approved the
    // cut on 22 Sept. Two sentences survive in `problems.ai_heading` /
    // `problems.ai_text`, where the same argument already stood.

    // OND-269: `toyota` is no longer a section of its own — it renders inside
    // "Why work with me", under the video and bio. The "18 years at Toyota"
    // headline used to appear twice (here and in `why_me.bio`), so Toyota is
    // out of the bio.
    'toyota' => [
        'heading'      => '18 years at Toyota. Then I left.',
        'text'         => 'The automotive industry taught me one thing: behind every top result there are always the same steps. Analysis, design, testing, verification — and then again. No shortcuts, no guesses. Principles that work regardless of the industry.',
        'text_2'       => 'I now apply these principles to every web project. You\'ll notice it at the first consultation, in the specification you receive before work begins — and in the result.',
        'quote_text'   => 'One of Ondřej\'s greatest strengths is his strong desire to develop — not just meeting customer needs, but exceeding their expectations.',
        'quote_author' => 'Pavel Baudyš, Director of Manufacturing, Assembly & Logistics, Toyota Motor Manufacturing Czech Republic (2024)',
    ],

    // OND-269: the only projects section on the homepage (formerly `showcase`
    // + `portfolio`). Heading and intro come from the retired `showcase` —
    // they talk about live websites, which is more concrete for a visitor.
    'portfolio' => [
        'heading'    => 'Websites running in the real world',
        'intro'      => 'These are live projects you can open right now. Each one also says what it did for the client.',
        'cta'        => 'All projects →',
        'detail_cta' => 'See the project',
        'live_cta'   => 'Open the live site',
        'live_aria'  => 'Open the :client website in a new window',
        'cards' => [
            'pitarena' => [
                'client'  => 'PitArena',
                'outcome' => 'Training slots are booked months ahead — bookings, vouchers and event sign-ups all run through the web without manual handling.',
            ],
            'barana' => [
                'client'  => 'BARANA',
                // OND-198 (finding 5.5): ad-platform jargon rewritten in client language.
                'outcome' => 'A standalone page built for paid advertising — visitors grasp the offer without picking up the phone.',
            ],
            'nove-interiery' => [
                'client'  => 'Nové interiéry',
                'outcome' => 'The site pre-filters irrelevant enquiries and acts as the first sales meeting — the client reports noticeably stronger brand credibility.',
            ],
        ],
    ],

    'services' => [
        'heading_primary'  => 'What I build — custom websites, apps and e-shops',
        'heading_other'    => 'Additional services',
        'secondary_inline' => 'I also handle SEO, graphic design and social media management — :pricing_link or :contact_link.',
        'secondary_inline_pricing' => 'see the pricing',
        'secondary_inline_contact' => 'get in touch',
        // OND-136: aligned with CS taxonomy 25/55/95 thousand CZK → EUR conversion (CEO-confirmed 1:25 anchor).
        'primary' => [
            'weby' => [
                'title'       => 'Custom websites',
                // OND-198 (finding 5.1): "starts bringing in customers" promised
                // the client's business result — replaced with what I deliver.
                'description' => 'A presentation website that sets you apart from template-driven competition and clearly explains what you do and how you differ.',
                'bullets'     => [
                    'Custom code — no WordPress, no templates',
                    'Conversion-focused structure built around your business',
                    'Maintenance-free with fast load times',
                ],
            ],
            'aplikace' => [
                'title'       => 'Web applications',
                'description' => 'Internal systems, customer portals and tracking tools built around how your operation actually runs.',
                'bullets'     => [
                    'Process design before a single line of code',
                    'Integrations with your existing tools',
                    'Custom admin without monthly licence fees',
                ],
            ],
            'eshop' => [
                'title'       => 'E-shops',
                'description' => 'An e-shop built around your product — without paying for plugins and themes every month.',
                'bullets'     => [
                    'Checkout and catalogue designed for your range',
                    'Integrations with accounting, couriers and payment gateways',
                    'No monthly platform fees',
                ],
            ],
        ],
        'seo' => [
            'title'       => 'Customers from Google — without paying per click',
            'description' => 'Paid ads only work while you\'re paying. SEO works for you long-term. I\'ll help so customers find you in Google for free — even when you don\'t have a budget for ads.',
        ],
        'design' => [
            'title'       => 'Visual identity customers notice',
            'description' => 'A logo and brand identity your customers recognise at first glance. I\'ll design a visual identity that fits your industry — one that sets you apart from generic competitors.',
        ],
        'social' => [
            'title'       => 'Social media that builds trust',
            'description' => 'Customers check your social media before they order. An active, consistent presence builds trust. I\'ll prepare content and a strategy that brings you closer to your target audience.',
        ],
    ],

    'price_anchor' => [
        'heading' => 'What will it cost?',
        // OND-198 (finding 5.4): expectation sentence before the first number.
        // OND-198 (finding 5.5): "tiers" → "three levels".
        'intro'   => 'Most projects I build land between €2,200 and €6,000. If you are looking for a website under €800, I am not the right supplier for you and I will tell you so straight away. Below are indicative entry prices for three levels — you receive an exact written quote after a short consultation.',
        // OND-136: 25 / 55 / 95 thousand CZK → EUR conversion (CEO-confirmed 1:25 anchor). One source of truth across the site.
        // OND-198 (finding 5.4): order Standard → Custom → Starter; the cheapest
        // band is last and framed as an exception. Standard is highlighted.
        'featured_label' => 'Most common choice',
        'items'   => [
            [
                'title'    => 'Standard',
                'price'    => '€2,200',
                'desc'     => 'Multilingual site with blog, conversion tracking and a booking system.',
                'featured' => true,
            ],
            [
                'title'    => 'Custom',
                'price'    => 'from €3,800',
                'desc'     => 'E-shop, web application or a complex custom portal.',
                'featured' => false,
            ],
            [
                'title'    => 'Starter',
                'price'    => '€1,000',
                'desc'     => 'An exception, not the standard entry point: up to 5-page presentation site for sole traders. I take it on only where a larger scope makes no sense.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detailed pricing →',
    ],

    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — who I am and how I build websites',
        'heading'   => 'Why work with me',
        'photo_alt' => 'Ondřej Kriška — web developer',
        // OND-269: the opening sentence ("For 18 years I ran projects at
        // Toyota…") moved out — the Toyota story is told in full right below
        // this paragraph, in the `toyota` block.
        'bio'       => 'I work solo. You talk directly to me from the first consultation through launch and beyond — the same exact specification, analysis and verification on every project.',
        'advantages' => [
            [
                'heading' => 'Custom code, no templates',
                'text'    => 'I build to your business — not from a template your competitors have already used.',
            ],
            [
                'heading' => 'Price upfront',
                'text'    => 'You receive a specification with an exact price before work begins. What\'s in the specification is on the invoice.',
            ],
            [
                'heading' => 'Direct contact',
                'text'    => 'You communicate with me directly — no salesperson, no coordinator, no ticket system.',
            ],
            [
                'heading' => 'Built to last',
                'text'    => 'Maintenance-free operation without WordPress updates and plugins — no monthly security patching.',
            ],
        ],
    ],

    'testimonials' => [
        'heading' => 'What my clients say about working together.',
        'note'    => 'Translated from the Czech originals on Google, Firmy.cz and Facebook.',
    ],

    // OND-269 (audit OND-254, finding 7): the "Two things you can count on."
    // section is gone — the board approved the cut on 22 Sept. Both promises
    // ("Price up front", "Direct contact") appeared verbatim a second time;
    // the single remaining wording lives in `why_me.advantages` 02 and 03.

    // OND-229 (F2 — proof layer): "Under the hood" section + live design
    // token demo. Every claim is verifiable in the repo; load time is
    // measured by the Performance API in the visitor's own browser.
    'craft' => [
        'heading' => 'Under the hood',
        'intro'   => 'The website I build for you looks like this on the inside too. These aren\'t marketing lines — everything below can be verified right on the page you\'re on.',
        'facts'   => [
            [
                'heading' => 'Custom code',
                'text'    => 'No WordPress, no page builder, no platform. The page is written from scratch and runs without plugins that would need monthly updates.',
            ],
            [
                'heading' => 'Images tailored to your screen',
                'text'    => 'Every image here exists in seven sizes and the efficient AVIF format. Your browser downloaded only the one your display actually needs.',
            ],
            [
                'heading' => 'Design held together by a system',
                'text'    => 'Colours, type and spacing aren\'t governed by a template but by a custom system of variables. That\'s why nothing sticks out — and why you can play with it below.',
            ],
        ],
        'perf_prefix' => 'This page loaded for you in',
        'perf_suffix' => '— measured just now, in your browser.',
    ],

    // OND-235: live demo (OND-229) removed — the site owner couldn't
    // articulate the visitor benefit himself, and on mobile the control
    // effect scrolled out of view. Keys and CSS (.pd-demo*) removed.

    // OND-201 (finding 5.8): the end of the homepage was three calls to
    // action in a row. The `cta` and `final_cta` blocks are removed; one
    // call with one form remains in `inline_form` below, and the client
    // quote moved next to it.

    'faq' => [
        'heading' => 'Frequently asked questions',
        // `key` is a stable slug for analytics (data-faq-key) and JSON-LD; do not localize.
        'items'   => [
            // OND-222 (chapter 6.3, objection 1): the most serious objection on
            // a 150k project. Withdrawn on 2026-09-16 (facts unconfirmed),
            // confirmed by Ondra on 2026-09-17 with two corrections:
            //  - the code belongs to the client, but Ondra holds it until the
            //    final payment; NOT "yours from day one and you hold it";
            //  - no mention of Laravel — it means nothing to the client;
            //  - documentation is not standard, only on request.
            // Still in force: no promise of round-the-clock availability.
            [
                'key'      => 'single-person',
                'question' => 'You are one person. What if you get ill or quit?',
                'answer'   => 'A fair concern — on a project this size it is the most important question. The site does not run on a platform you could not leave: it is custom code on ordinary web hosting. You can hold the hosting admin and FTP credentials the whole time — just ask for them. Once the project is paid in full the code is yours; I hand it over whenever you ask, and any developer can carry on with it — if documentation is needed for the handover, I will write it. I do not keep round-the-clock availability and I will not claim otherwise. What I do guarantee is that nothing stays locked up with me.',
            ],
            // OND-269 (audit OND-254, finding 7): the "What will it cost?"
            // question is gone from here — the same heading and the same
            // numbers stand four sections above in the pricing anchor
            // (`price_anchor`) and in the price list.
            [
                'key'      => 'duration',
                'question' => 'How long does it take?',
                'answer'   => 'From first message to a launched site typically 4–12 weeks — a week for consultation, 2–5 days for the specification, 3–10 weeks for the build, and launch by the next business day after approval. The detailed timeline for your project goes into the specification.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'What if I\'m not happy with the result?',
                'answer'   => 'I work in short iterations and send progress previews — I don\'t wait until the end of the project to find out whether it fits. If something is off, we fix it right away, not after the invoice. What\'s in the specification, I deliver.',
            ],
            [
                'key'      => 'maintenance-free',
                'question' => 'What does "maintenance-free" mean?',
                'answer'   => 'No WordPress, no plugins, no monthly security updates. The site runs on custom code — it just works, doesn\'t need regular patching and doesn\'t break from template collisions. Small content changes go through me directly, with no ticket.',
            ],
            // Archive: additional FAQ items move off the homepage (to /faq or /sluzby — out of OND-121 scope).
        ],
    ],

    'faq_form' => [
        'eyebrow'     => 'Got a different question?',
        'heading'     => 'Send it over.',
        'description' => 'I pick it up and get back to you within 24 hours on business days. No sales pressure.',
        'name'        => 'Name',
        'email'       => 'Email',
        'message'     => 'Your question',
        'placeholders' => [
            'name'    => 'John Smith',
            'email'   => 'john@company.com',
            'message' => 'E.g. Can you deliver before the end of the quarter?',
        ],
        'submit'      => 'Send question',
        'submitting'  => 'Sending…',
        'success'     => 'Thanks, the question has arrived. I\'ll get back to you as soon as possible.',
    ],

    // OND-201 (finding 5.8): the single closing call to action of the homepage.
    'inline_form' => [
        'eyebrow'         => 'Enquiry',
        'heading'         => 'Tell me what you need',
        'description'     => 'Describe briefly what you are dealing with. I\'ll get back to you within 24 hours on business days and we will go through what makes sense, with no obligation. If we turn out not to be a fit, I will tell you straight.',
        'quote_text'      => 'Thanks to the individual approach, flexibility and professionalism, the result matches our expectations.',
        'quote_author'    => 'Hana Jaskmanická, Executive Director, VP Industry',
        'name'            => 'Full name',
        'email'           => 'Email',
        'phone'           => 'Phone (optional)',
        'phone_hint'      => 'Leave a number and I can get back to you faster.',
        'message'         => 'What do you need solved?',
        'placeholders'    => [
            'name'    => 'John Smith',
            'email'   => 'john@company.com',
            'phone'   => '+420 000 000 000',
            'message' => 'E.g. a new website for a manufacturing company, 5–10 pages',
        ],
        'submit'          => 'Send enquiry',
        'submitting'      => 'Sending…',
        'note'            => 'Or email me at ok@ondraweb.cz. I reply personally, not through a form robot.',
        'privacy_prefix'  => 'By submitting you agree to processing of personal data in line with the ',
        'privacy_link'    => 'privacy policy',
        'success'         => 'Thanks, the enquiry has arrived. I\'ll get back to you as soon as possible.',
        'error'           => 'The enquiry could not be saved right now. Please try again.',
    ],

    // TODO: review pro EN — copy podle CS varianty A (OND-100)
    'sticky' => [
        'cta'    => 'Book a consultation',
        'mobile' => 'Enquiry',
        'phone'  => 'Call',
    ],

];
