<?php

return [

    // OND-353: uvozovky kolem citací sází šablona, ne texty — aby se do
    // `lang` nedostaly rovné ASCII " (opakovaná vada, viz lang/*/cookies.php
    // jako zdroj pravdy). CS/DE mají „…“ (U+201E + U+201C), EN “…” (U+201C + U+201D).
    'quote_marks' => ['open' => '“', 'close' => '”'],

    // OND-201 (finding 5.7): the page title must not define the business by
    // negating competitors, and "no WordPress" says nothing to someone who
    // does not know what WordPress is (principle 0).
    'meta' => [
        'title'       => 'Custom websites: I quote, not estimate | ONDRAWEB',
        'description' => 'Custom websites, online shops and web applications. I quote the price up front and build everything myself, from scratch. Rated 5.0 out of 5.',
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
        // OND-333: EN follows the CS headline picked on OND-330 (path B) —
        // three lines, each one a full verb antithesis in the first person,
        // rendered in the smaller heading (`.pd-heading--hp` in podpis.css).
        // Hard line breaks (`<br>`), because the three lines are three
        // separate statements, not one sentence wrapped by the viewport.
        // No `<em>` here — three antitheses have no single key word.
        // Wording and per-line measurements come from OND-332.
        'upline'          => 'For businesses that are growing.',
        'heading_html'    => 'I ask, not guess.<br>I quote, not estimate.<br>I deliver, not promise.',
        // OND-341: the semicolon is gone. It only ever stood here because of
        // geometry — spelling it out adds a fifth line on mobile (+28.8 px at
        // 390×844) and at OND-332 the hero had no room for it. Since the hero
        // geometry was fixed the CTA clears the bottom bar by 61 px even with
        // the extra line, so the rule against semicolons inside a sentence
        // (spec rule 5) wins. Re-measure before growing this sentence again.
        'subline'         => 'I am Ondřej Kriška. I build websites and applications from scratch and work on them alone. I price the job before we start, and I\'m rated 5.0 out of 5 on Google and Firmy.cz.',
        'signature'       => 'Ondřej Kriška',
        // OND-437 (proposal 1 of OND-429): a concrete day replaces "the next
        // business day". `:date` is filled in by App\Support\ReplyDate.
        'note'            => 'Write to me today and I\'ll get back to you by :date. No commitment, we just go through what makes sense.',

        // Backwards compat (fallback render).
        'eyebrow'       => 'Custom websites & web applications',
        'heading'       => 'I ask, not guess. I quote, not estimate. I deliver, not promise.',
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
        // one figure — the strip also holds projects, years and an award
        // (reply time left the strip in OND-437). The landmark label for the whole strip is `strip_aria`.
        'rating_aria'  => 'Rated 5.0',
        'strip_aria'   => 'Numbers about my work',
        'clients_aria' => 'Clients',
        'rating_value' => '5.0',
        'reviews'      => 'out of 5 on Google and Firmy.cz',
        'experience'   => '18 years at Toyota',
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
    // (`live_cta` / `live_aria`; OND-449 B-05: zrušeno).

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
    //
    // OND-457 (B-11 from OND-441): three real portfolio examples below the
    // sentence. Wording by the Content Writer (document `texty` on OND-456);
    // each line describes only the "before" state from the case study's
    // Challenge section. `slug` points at the project in the DB, the URL comes
    // from `detailUrl()`; an unpublished project is skipped and with fewer than
    // two examples the whole list disappears (PageController::home).
    // `name` is not translated. Key shape is identical in all three locales.
    'situation' => [
        'text'  => 'Most who write to me are doing well — the website stopped keeping up.',
        'cases' => [
            ['slug' => 'cyklocentrum', 'name' => 'Cyklocentrum Březí', 'field' => 'bike rental and service',         'text' => 'Their previous developer spent a year on the site and never finished it.'],
            ['slug' => 'zubni-provazek', 'name' => 'Zubní Provázek',  'field' => 'dental practice',                 'text' => 'Patients had to phone to ask about prices; the old site didn\'t list them.'],
            ['slug' => 'kemp-veselka', 'name' => 'Autokemp Veselka',   'field' => 'family campsite',                 'text' => 'Guests decide on the go, and the old site wasn\'t mobile-ready.'],
        ],
        'cases_link'      => 'How it turned out →',
        'cases_link_aria' => 'How it turned out — :name case study',
    ],

    'how_i_work' => [
        'heading'   => 'From your first message to a launched website in four steps',
        'cta_intro' => 'Let\'s jump straight to step 1.',
        // OND-411: continues `cta_intro`; slug is the English article slug.
        'cta_more'         => 'Or, if you\'d rather get ready first: :article_link.',
        'cta_more_article' => 'what to prepare before you contact a web developer',
        'cta_more_slug'    => 'how-to-prepare-for-a-new-website',
        'steps'   => [
            [
                'heading'      => 'Intro call',
                'time'         => 'about 15 min',
                'text'         => 'You write to me through the form below and tell me what you are dealing with. I get back to you by the next business day and give you a call. You talk to me, not to a salesperson. I find out what the website has to do and tell you straight whether I can help.',
                'quote_text'   => 'He really listened to what I needed and then turned it into something I am completely happy with.',
                'quote_ref'    => 'magda-pernicova',
            ],
            [
                'heading'      => 'Specification',
                'time'         => '2–5 days',
                'text'         => 'If it makes sense, we sit down over the details: who you sell to, how enquiries reach you, what the website has to do. Then you get it in writing: what will be on the website and what it will cost. What is in the specification is what is on the invoice. I estimate the delivery date up front, not after the fact.',
                'quote_text'   => 'He analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading.',
                'quote_ref'    => 'jan-stybor',
                'note'         => 'The date is an estimate, not a commitment. Your approvals and your materials are part of the work, and I say so right at the start.',
            ],
            [
                'heading' => 'Build',
                'time'    => '3–10 weeks',
                'text'    => 'I build every site from scratch, so it follows your company and not a ready-made layout. I send previews as I go and ask you about the decisions worth making together. You are not finding out at the end whether it fits — you know all along.',
            ],
            [
                'heading' => 'Launch and support',
                'time'    => 'by the next business day',
                'text'    => 'Once you approve it, the site usually goes live within one business day. From then on there is nothing to maintain — it has no add-ons that force monthly updates, so in two years no invoice arrives for repairing something that broke on its own. Small changes and questions after launch go through me directly.',
                'note'    => 'Launch within 1 business day of approval.',
            ],
        ],
    ],

    // OND-308: `toyota` is its own section and carries Pavel Baudyš's quote.
    // OND-314: the video moved from here to "How it works".
    // OND-310: the EN text follows the rewritten CS. The old wording stated
    // abstract principles ("analysis, design, testing, verification"); the
    // new one states what Ondřej actually did there. This is the section
    // where the visitor checks the craft, so it speaks in his voice.
    // OND-344: the section now sits sixth, after "What I build" — and the
    // `example` key (the PitArena e-shop sample added in OND-318) is gone.
    // After the move it put a slice of the offer in the middle of a personal
    // story. Do not reintroduce it without a decision on OND-344.
    'toyota' => [
        'heading'      => '18 years at Toyota.',
        // Uppercase is done by CSS (`text-transform`), not by this string.
        'employer_label' => 'Former employer',
        // U+2060 (word joiner) after the dash keeps the year range on one line.
        'text'         => 'I started as a labourer in logistics and left as a senior specialist in the project team. For eighteen years (2005–⁠2023) I looked for where production and assembly lose time, and I wrote an in-house application for it that saved millions of crowns. On a production line you cannot afford for something to go down. That is where I learned that software is either done properly or not at all.',
        'text_2'       => 'I build websites the same way. Before I write the first line I want to know how enquiries reach you and what happens to them next. Only then does the site take shape. You see it in the specification you get before I start working.',
        'quote_text'   => 'One of Ondřej\'s greatest strengths is his strong desire to develop — not just meeting customer needs, but exceeding their expectations.',
        'quote_ref'    => 'pavel-baudys',
        // OND-411: quiet sentence closing the section, same pattern as `services.secondary_inline`.
        'more_inline'       => 'The full story, from the factory floor to websites, is :about_link.',
        'more_inline_about' => 'on my About page',
    ],

    // OND-269: the only projects section on the homepage (formerly `showcase`
    // + `portfolio`). Heading and intro come from the retired `showcase` —
    // they talk about live websites, which is more concrete for a visitor.
    'portfolio' => [
        'heading'    => 'Websites running in the real world',
        'intro'      => 'These are live projects you can open right now. Each one also says what it did for the client.',
        'cta'        => 'All projects →',
        'detail_cta' => 'See the project',
        // OND-440: bar of the live-site recording frame. The date belongs to
        // the project (config site.live_recordings); only its format lives here.
        'live' => [
            'kind'        => 'live site',
            'recorded'    => 'recorded :date',
            'date_format' => 'j M Y',
            'pause'       => 'Pause recording',
            'play'        => 'Play recording',
        ],
        'cards' => [
            'pitarena' => [
                'client'  => 'PitArena',
                'outcome' => 'Over 15,000 visits from Google in 16 months and its own race registration with online payment.',
            ],
            'barana' => [
                'client'  => 'BARANA',
                // OND-198 (finding 5.5): ad-platform jargon rewritten in client language.
                'outcome' => 'A website that explains an expensive pergola without long text: visitors tilt the louvres themselves and see the terrace from morning to winter.',
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
        'subheading'       => 'I build every site from scratch. I do not use a template your competitors already have.',
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
                    ['You will not find the same website one street away.', 'I build from scratch, without templates.'],
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
        // OND-354: the price is a threshold plus a range, not a menu of three
        // packages (Ondřej, 26 Sep 2026 on OND-347). The rejection sentence is
        // gone with it. The CZK floor of 20,000 is DELIBERATELY not converted
        // here: €800 buys a landing page in the German-speaking market, not a
        // website, so EN/DE carry the range and "smaller scopes welcome" only.
        'intro'   => 'Most projects I build land between €3,500 and €8,000. The smallest thing I take on is a presentation site, from €1,900 — a smaller scope, not a lower standard. You get the exact price in writing in the specification.',
        // OND-354: cards carry SCOPE, not price, and are named after what gets
        // built. Order is by growing scope, the middle one is highlighted.
        'featured_label' => 'Most common choice',
        'items'   => [
            [
                'title'    => 'Presentation site',
                'scope'    => 'So customers can check you out',
                'desc'     => 'Who you are, what you do, how to reach you',
                'featured' => false,
            ],
            [
                'title'    => 'Business site',
                'scope'    => 'So customers see why it should be you',
                'desc'     => 'More services, more languages, references and a blog',
                'featured' => true,
            ],
            [
                'title'    => 'E-shops and applications',
                'scope'    => 'So the system does the work for you',
                'desc'     => 'E-shop, bookings, integration with your systems',
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
        // OND-411: quiet sentence after the questions, links to the Notes listing.
        'more_inline'      => 'Whatever didn\'t fit here, I answer :blog_link.',
        'more_inline_blog' => 'in my Notes',
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
                'answer'   => 'A fair question, whether the project is big or small. The site does not run on a platform you could not leave: it is custom-built and runs on ordinary web hosting. You can hold the hosting admin and FTP credentials the whole time — just ask for them. Once the project is paid in full the code is yours; I hand it over whenever you ask, and any developer can carry on with it — if documentation is needed for the handover, I will write it. I do not keep round-the-clock availability and I will not claim otherwise. What I do guarantee is that nothing stays locked up with me.',
            ],
            // OND-269 (audit OND-254, finding 7): the "What will it cost?"
            // question is gone from here — the same heading and the same
            // numbers stand four sections above in the pricing anchor
            // (`price_anchor`) and in the price list.
            [
                'key'      => 'duration',
                'question' => 'How long does it take?',
                'answer'   => 'From first message to a launched site typically 4–12 weeks — an intro call within a few days, roughly a week for a detailed meeting and the specification, 3–10 weeks for the build, and launch by the next business day after approval. I put the exact timeline for your project into the specification.',
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
    // OND-309 opravila duplicitu jen v češtině: citace u formuláře byla
    // Jaskmanická, jejíž recenze stojí o obrazovku výš v sekci „Co říkají
    // klienti". EN/DE zůstaly pozadu. OND-353 přidává k citacím tvář, takže
    // by se tu její portrét objevil dvakrát na jedné stránce — sjednoceno
    // se `lang/cs` na Štěpánka. Věty jsou doslovně z `en/testimonials.php`,
    // nejde o nový překlad.
    'inline_form' => [
        'eyebrow'         => 'Enquiry',
        'heading'         => 'Tell me what you need',
        'description'     => 'Describe briefly what you are dealing with. If you send it today, I\'ll get back to you by :date and we will go through what makes sense, with no obligation. If we turn out not to be a fit, I will tell you straight.',
        'quote_text'      => 'He acts fast and efficiently. For me it was a big difference compared with my previous IT supplier.',
        'quote_ref'       => 'ivo-stepanek',
        'name'            => 'Full name',
        'email'           => 'Email',
        'phone'           => 'Phone (optional)',
        'phone_hint'      => 'Leave a number and I can get back to you faster.',
        // Attachments are collapsed behind this text button (OND-448, B-01);
        // `<x-lead-form>` renders the leading `+`.
        'attach_toggle'   => 'Add attachments (optional)',
        'message'         => 'What do you need solved?',
        'placeholders'    => [
            'name'    => 'John Smith',
            'email'   => 'john@company.com',
            'phone'   => '+420 000 000 000',
            'message' => 'E.g. a new website for a manufacturing company, 5–10 pages — or just write when I should call you',
        ],
        'submit'          => 'Send enquiry',
        'submitting'      => 'Sending…',
        'note'            => 'Or email me at ok@ondraweb.cz. I reply personally, not through a form robot.',
        'privacy_prefix'  => 'By submitting you agree to processing of personal data in line with the ',
        'privacy_link'    => 'privacy policy',
        // OND-437 (proposal 2 of OND-429): confirmation replaces the form after
        // submit. `:received` = time the enquiry was stored, `:date` = ReplyDate,
        // `:email` from the form. /contact reads `reply`, `more` and `more_article` too.
        'confirmation' => [
            'signature'    => 'Ondřej Kriška',
            'stamp'        => 'Enquiry received · :received',
            'heading'      => 'Thank you. Your enquiry is with me.',
            'reply'        => 'I\'ll get back to you personally at :email by :date. There is nothing else you need to do now.',
            'steps_aria'   => 'What happens next',
            'steps'        => [
                ['label' => 'Intro call', 'text' => 'I call you, for about 15 minutes. I find out what you are dealing with and tell you straight whether I can help.'],
                ['label' => 'Specification', 'text' => 'If it makes sense, we go through the details together and you get it in writing: what will be on the website and what it will cost.'],
                ['label' => 'Decision', 'text' => 'You decide on the build only once the finished specification is in front of you.'],
            ],
            'more'         => 'Before I get back to you, you can read :article_link.',
            'more_article' => 'how to prepare for a new website',
        ],
    ],

    // OND-308: `cta` promised a calendar that no longer exists (OND-303).
    // The link target is unchanged, only the label.
    'sticky' => [
        'cta'    => 'Write an enquiry',
        'mobile' => 'Enquiry',
        'phone'  => 'Call',
    ],

    // OND-437: how the reply day (`:date`) and time received (`:received`) are
    // written. Filled in by App\Support\ReplyDate in Europe/Prague. Keys 1–5 =
    // Monday–Friday, a weekend never comes out. Spaces inside the date are U+00A0.
    'reply_date' => [
        'weekdays' => [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
        ],
        'date'     => ':weekday :day :month_name',
        'received' => ':day :month_name, :time Prague time',
    ],
];
