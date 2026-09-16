<?php

return [

    'meta' => [
        'title'       => 'Contact — Ondřej Kriška',
        'description' => 'Call or text and I will get back to you. Contact form, phone and address.',
    ],

    'subheading'          => 'I will help you',
    'heading'             => 'Call or text and I will get back to you',
    // OND-201 (finding 5.9): full address and company ID instead of just
    // a country — public data, builds trust and local visibility.
    'address_label'       => 'Address',
    'address_name'        => 'Ondřej Kriška',
    'address_street'      => 'Dunajovská 116',
    'address_city'        => '691 81 Březí, Czech Republic',
    'address_registration' => 'Company ID 19231407, not VAT registered',
    'hours_label'         => 'Availability',
    'open_hours'          => 'I reply on business days, usually within two working days. I am not on call at weekends or on public holidays, but nothing gets lost.',
    'cta_consultation'    => 'Write to me',

    'form_heading'        => 'Contact form',
    'form_subheading'     => 'Get a free, non-binding quote or send a query',
    'name'                => 'Full Name',
    'email'               => 'Email Address',
    'tel'                 => 'Phone number',
    'subject'             => 'Subject',
    'message'             => 'Your Message',
    'message_placeholder' => 'You can briefly describe what you need, or just write when I should call you ...',
    'agree'               => 'I agree to the transfer of personal data in accordance with the ',
    'policy'              => 'Privacy Policy',
    'send'                => 'Send Message',
    'sending'             => 'Sending...',
    'required'            => 'This field is required.',
    'enter_valid_email'   => 'Please enter a valid email address.',
    'policy_not_agreed'   => "You didn't agree to the Privacy Policy",
    'message_success'     => 'Thanks for your message.',
    'message_error'       => 'An error occurred while sending. Please try again — or email me directly at ok@ondraweb.cz.',

    // OND-201 (bod 8): `contact.blade.php` uses hero / next_steps / thank_you
    // and the optional budget field (added in OND-136 for CS only), so the EN
    // page rendered raw translation keys. DE falls back to EN, so both were
    // broken. Copy follows the approved CS version.
    'hero' => [
        'page_mark_label' => 'CONTACT',
        'upline'          => 'You write to me directly.',
        'heading_html'    => 'No CRM,<br>no call centre — <em>just Ondřej</em>.',
        'eyebrow'         => 'You write to me directly',
        'heading'         => 'You write straight to me, Ondřej.',
        'subline'         => 'I read your message personally and usually reply by the next business day.',
        'photo_alt'       => 'Ondřej Kriška — author of this site and your contact person',
        'role_label'      => 'Developer, author of this site, your only contact',
    ],

    'next_steps' => [
        'eyebrow' => 'What happens next',
        'heading' => 'Three steps — no marketing funnel.',
        'steps'   => [
            [
                'title' => 'I reply within 24 hours',
                'text'  => 'You get an email from me personally, not an automated confirmation. If I am travelling, I will be back to you by the next business day at the latest.',
            ],
            [
                'title' => 'We arrange a 30-minute call',
                'text'  => 'A short phone or video call to find out whether working together makes sense. No presentation, no slides, no sales pressure.',
            ],
            [
                'title' => 'You get a written proposal',
                'text'  => 'Within a week I send a specification with the scope, the timeline and an exact price. What is in the specification is what goes on the invoice.',
            ],
        ],
    ],

    'thank_you' => [
        'heading'      => 'Done, your message arrived.',
        'subline'      => 'Thank you. I read it personally and will reply by the next business day at the latest.',
        'next'         => 'In the meantime you can look through my projects or read the pricing.',
        'cta_projects' => 'Projects',
        'cta_price'    => 'Pricing',
    ],

    'budget_label'   => 'Indicative budget (optional)',
    'budget_options' => [
        'Up to CZK 25,000',
        'CZK 25,000 to 55,000',
        'CZK 55,000 to 95,000',
        'CZK 95,000 and above',
        'Not sure yet — advise me',
    ],

];
