<?php

return [

    // OND-201 (finding 5.7): the page title must not define the business by
    // negating competitors, and "no WordPress" says nothing to someone who
    // does not know what WordPress is (principle 0).
    'meta' => [
        'title'       => 'Custom websites and web applications | ONDRAWEB',
        'description' => 'Custom websites, online shops and web applications for small and mid-sized companies. Custom code, an exact price up front, and you deal with me directly. I am Ondřej Kriška.',
    ],

    'hero' => [
        // OND-127 P0 incident hotfix (2026-05-14) — plagiátor strings removed.
        // Placeholder copy derived from meta description = pre-redesign safe copy.
        // FINAL COPY: Content Writer delivers in OND-136 P3 (SLA 2h from 11:10 UTC).
        'page_mark_label' => 'CUSTOM WEB',
        // OND-145 P0.3: page_mark_index removed — agency-portfolio pagination
        // artefact, itwebtech has no „pages" hierarchy in hero context.
        // OND-198 (finding 5.1): the previous headline promised the client's
        // business result. Replaced with the approved hero copy (CS source of truth).
        // OND-310: EN now follows the rebuilt CS hero (OND-307 / OND-308).
        // The old headline led with "on my own code" — a technical term in
        // the one place where the visitor decides whether to write at all.
        // The custom code does not disappear, it moves down into the subline
        // and into the "What I build" section.
        'upline'          => 'For businesses that are growing.',
        'heading_html'    => 'A website that can <em>carry</em> what is going well for you.',
        'subline'         => 'I am Ondřej Kriška. I build websites and applications on my own code and I do the work myself — from the first conversation to launch you deal with me and nobody else.',
        'note'            => 'I\'ll get back to you within 24 hours on business days. No commitment, we just go through what makes sense.',

        // Backwards compat (fallback render).
        'eyebrow'       => 'Custom websites & web applications',
        'heading'       => 'A website that can carry what is going well for you.',
        // OND-130 + OND-136: single primary CTA in hero, exact wording per spec.
        // OND-308: `cta_secondary` and `phone_label` removed — no reservations
        // since OND-303 and the phone number splits the decision in the hero.
        'cta_primary'   => 'Tell me what you need',
    ],

    'anchors' => [
        'how_i_work' => 'how-i-work',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        // OND-315: `rating_aria` describes the rating alone, so it sits on that
        // one figure — the strip also holds projects, years, reply time and an
        // award. The landmark label for the whole strip is `strip_aria`.
        'rating_aria'  => '5 out of 5 rating',
        'strip_aria'   => 'Numbers about my work',
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

    // OND-308: the `problems` block is gone — the rebuilt homepage does not
    // define itself by negating competitors. In its place sits a paragraph on
    // the client's situation. OND-310 supplies the EN wording, so the section
    // renders on /en/ too. It describes a situation, not a pain: the reader
    // nods along. No scare copy (spec chapter 0.5).
    //
    // OND-320 (variant G): the sentence is a fact about Ondřej — who writes
    // to him — not a claim about the reader. Do not flip it back into the
    // second person ("your website is falling behind"); the reader
    // recognises themselves in it and nothing is put in their mouth.
    // Keep it to one rendered line (`.pd-lead--wide`, 52ch): 70 characters
    // fit, from roughly 74 it wraps.
    'situation' => [
        'text' => 'Most who write to me are doing well — the website stopped keeping up.',
    ],

    'how_i_work' => [
        'heading'   => 'From your first message to a launched website in four steps',
        'cta_intro' => 'Let\'s jump straight to step 1.',
        'steps'   => [
            [
                'heading'      => 'Consultation',
                'time'         => '60 min, within a week',
                'text'         => 'You write to me through the form below and tell me what you are dealing with. I get back to you within 24 hours on business days and we arrange a call or a meeting. You talk to me, not to a salesperson — I want to know who you sell to, how enquiries reach you today and what the website has to do.',
                'quote_text'   => 'He really listened to what I needed and then turned it into something I am completely happy with.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Specification',
                'time'         => '2–5 days',
                'text'         => 'You get it in writing: what will be on the website, how many pages it has and what it will cost. What is in the specification is what is on the invoice. I estimate the delivery date up front, not after the fact.',
                'quote_text'   => 'He analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading.',
                'quote_author' => 'Jan Stybor, Head of Project Department, Toyota',
                'note'         => 'The date is an estimate, not a commitment. Your approvals and your materials are part of the work, and I say so right at the start.',
            ],
            [
                'heading' => 'Build',
                'time'    => '3–10 weeks',
                'text'    => 'I write my own code, so the website follows your company and not a ready-made layout. I send previews as I go and ask you about the decisions worth making together. You are not finding out at the end whether it fits — you know all along.',
            ],
            [
                'heading' => 'Launch and support',
                'time'    => 'by the next business day',
                'text'    => 'Once you approve it, the site usually goes live within one business day. From then on there is nothing to maintain — it has no add-ons that force monthly updates, so in two years no invoice arrives for repairing something that broke on its own. Small changes and questions after launch go through me directly.',
                'note'    => 'Launch within 1 business day of approval.',
            ],
        ],
    ],

    // OND-308: `toyota` is the third section of the page again and carries
    // the video and Pavel Baudyš's quote.
    // OND-310: the EN text follows the rewritten CS. The old wording stated
    // abstract principles ("analysis, design, testing, verification"); the
    // new one states what Ondřej actually did there. This is the section
    // where the visitor checks the craft, so it speaks in his voice.
    // OND-318: `example` is the EN wording of the approved CS example — not
    // a literal translation, it reuses the words the PitArena e-shop case
    // study already uses in English. Rewrite it in OND-318, not here. The
    // item count keeps the English separator `4,551` on purpose.
    'toyota' => [
        'heading'      => '18 years at Toyota. Then I left.',
        'text'         => 'I started as a labourer in logistics and left as a senior specialist in the project team. For eighteen years I looked for where production and assembly lose time, and I wrote an in-house application for it that saved millions of crowns. On a production line you cannot afford for something to go down. That is where I learned that software is either done properly or not at all.',
        'text_2'       => 'I build websites the same way. Before I write the first line I want to know how enquiries reach you and what happens to them next. Only then does the site take shape. You see it in the specification you get before I start working.',
        'example'      => 'At the PitArena online shop I first asked how its customers choose a part. Without the model and year they buy blind and send it back. The catalogue is therefore split across 19 motorcycle models — one click narrows 4,551 items to those that fit.',
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
        'heading_primary'  => 'What I build',
        // OND-310: the mention of custom code moved here out of the hero.
        'subheading'       => 'I write my own code. I do not use a template your competitors already have.',
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
                'description' => 'A website that explains what you do and why someone should pick you. It follows your company, not a ready-made layout.',
                // OND-310 (spec task 3.9): a bullet is now a pair — the client's
                // sentence first, the technical note under it.
                'bullets'     => [
                    ['You will not find the same website one street away.', 'I write my own code and I do not use templates.'],
                    ['The pages follow the order in which your customer actually decides.', 'I design the structure around how enquiries reach you.'],
                    ['In two years no invoice arrives for repairing something that broke on its own.', 'The site does not run on add-ons that force monthly updates.'],
                ],
            ],
            'aplikace' => [
                'title'       => 'Web applications',
                'description' => 'Internal systems, customer portals and tracking tools built around how your operation actually runs.',
                'bullets'     => [
                    ['Before I start writing, we go through how things run at your company today.', 'The process design comes before the first line of code.'],
                    ['The site passes orders on by itself to wherever you already record them. Nobody retypes anything.', 'I connect it to the tools you already use.'],
                    ['The admin area is yours and you do not pay for it every month.', 'No licence fees per user and none per record.'],
                ],
            ],
            'eshop' => [
                'title'       => 'E-shops',
                'description' => 'An e-shop built around your product range and the way you sell it.',
                'bullets'     => [
                    ['The checkout and the catalogue fit what you actually sell.', 'I design them around your range, not around a template.'],
                    ['The site passes every order on by itself to your accounting, your courier and the payment gateway.', 'I sort the connections out while building, not after launch.'],
                    ['Nobody charges you rent for having your own shop online.', 'No monthly fees for a platform or for add-ons.'],
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
                // OND-310: the apologetic sentence is gone (spec task 3.7).
                'desc'     => 'A presentation website of up to five pages for sole traders.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detailed pricing →',
    ],

    // OND-308: only the two screen-reader labels are left — the video and
    // the photo belong to the Toyota section. `heading`, `bio` and the four
    // `advantages` are gone from the rebuilt homepage.
    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — who I am and how I build websites',
        'photo_alt' => 'Ondřej Kriška — web developer',
    ],

    'testimonials' => [
        'heading' => 'What my clients say',
        'note'    => 'Translated from the Czech originals on Google, Firmy.cz and Facebook.',
    ],

    // OND-308: the technical "Under the hood" section is gone. What is left
    // is the measured load time, now in the numbers strip — the only claim
    // a visitor verifies on themselves. Performance API measures it in the
    // visitor's own browser; we never state a number we did not measure.
    'craft' => [
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
        'heading' => 'What you ask me most often',
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
                'answer'   => 'From first message to a launched site typically 4–12 weeks — a week for consultation, 2–5 days for the specification, 3–10 weeks for the build, and launch by the next business day after approval. I put the exact timeline for your project into the specification.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'What if I\'m not happy with the result?',
                'answer'   => 'I work in short iterations and send progress previews — I don\'t wait until the end of the project to find out whether it fits. If something is off, we fix it right away, not after the invoice. What\'s in the specification, I deliver.',
            ],
            [
                'key'      => 'maintenance-free',
                // OND-310: the old question asked what "maintenance-free" means —
                // that is my word, not the client's, and the answer named other
                // people's technology. This is the question the client asks himself.
                'question' => 'Will the website need regular maintenance?',
                'answer'   => 'No. It does not sit on a ready-made platform with add-ons that have to be updated every month, so there is nothing in there to break on its own. When you want to change the content or add a page, you write to me and I do it.',
            ],
            // Archive: additional FAQ items move off the homepage (to /faq or /sluzby — out of OND-121 scope).
        ],
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

    // OND-308: `cta` promised a calendar that no longer exists (OND-303).
    // The link target is unchanged, only the label.
    'sticky' => [
        'cta'    => 'Write an enquiry',
        'mobile' => 'Enquiry',
        'phone'  => 'Call',
    ],

];
