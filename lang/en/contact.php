<?php

return [

    'meta' => [
        'title'       => 'Contact — Ondřej Kriška',
        'description' => 'Send me a message and I\'ll get back to you. Contact form and address.',
    ],

    'subheading'          => 'I\'ll help you',
    'heading'             => 'Send me a message and I\'ll reply within 24 hours',
    'address_label'       => 'Address',
    'address_country'     => 'Czech Republic',
    'hours_label'         => 'Availability',
    'open_hours'          => 'Mon–Fri: 9:00–19:00<br>Sat–Sun: 12:00–17:00',
    'cta_consultation'    => 'Schedule an online meeting',

    'form_heading'        => 'Contact form',
    'form_subheading'     => 'Get a free, non-binding quote — or send any question.',
    'name'                => 'Full name',
    'email'               => 'Email',
    'tel'                 => 'Phone number',
    'subject'             => 'Subject',
    'message'             => 'Your message',
    'message_placeholder' => 'Briefly describe what you need — or just write when I should call you…',
    'agree'               => 'I agree to the processing of personal data in accordance with the ',
    'policy'              => 'Privacy Policy',
    'send'                => 'Send message',
    'sending'             => 'Sending...',
    'required'            => 'Please fill in this field.',
    'enter_valid_email'   => 'Please enter a valid email address.',
    'policy_not_agreed'   => 'To send the message we need your consent to data processing.',
    'message_success'     => 'Thanks for your message.',
    'message_error'       => 'Something went wrong. Please try again — or write to me directly at ok@itwebtech.cz.',

    // OND-136: net-new copy blocks for /contact redesign (plan §1).
    // Engineer (B2) wires these keys into `resources/views/pages/contact.blade.php`.

    // Trust signal hero — "it's-an-actual-person".
    'hero' => [
        // Plan §3.1 hero — page-mark, upline, italic display heading, subline.
        // OND-135 cleanup (2026-05-14): page_mark_index removed — agency-
        // portfolio artefact per CEO PR #78 precedent (home).
        'page_mark_label' => 'CONTACT',
        'upline'          => 'You\'re writing directly to me.',
        'heading_html'    => 'No CRM,<br>no call centre — <em>just Ondřej</em>.',
        'eyebrow'      => 'You\'re writing to me',
        'heading'      => 'You\'re writing directly to me, Ondřej.',
        'subline'      => 'I read your message personally and reply usually by the next business day.',
        'photo_alt'    => 'Ondřej Kriška — author and contact person',
        'role_label'   => 'Developer, author of this site, your only point of contact',
    ],

    // 3-step "what happens next" — lowers form-submit anxiety.
    'next_steps' => [
        'eyebrow' => 'What happens next',
        'heading' => 'Three steps — no marketing funnel.',
        'steps'   => [
            [
                'title' => 'I reply within 24 hours',
                'text'  => 'You get an email from me personally, not an automated confirmation. If I\'m travelling, I\'ll be back to you by the next business day at the latest.',
            ],
            [
                'title' => 'We schedule a 30-minute call',
                'text'  => 'A short phone or video call — we work out whether it makes sense to work together. No deck, no slides, no sales pressure.',
            ],
            [
                'title' => 'You receive a written quote',
                'text'  => 'Within a week I send a specification with scope, timeline and the exact price. What\'s in the specification is what\'s on the invoice.',
            ],
        ],
    ],

    // Thank-you state — replaces the form once the message has been sent.
    'thank_you' => [
        'heading'  => 'Done — your message is in.',
        'subline'  => 'Thank you. I\'ll read it personally and reply by the next business day at the latest.',
        'next'     => 'In the meantime, take a look at past projects or check the pricing.',
        'cta_projects' => 'Past projects',
        'cta_price'    => 'Pricing',
    ],

    // Optional budget field (aligned with home.inline_form and landing budgets).
    'budget_label'   => 'Indicative budget (optional)',
    'budget_options' => [
        'Up to €1,000',
        '€1,000 – €2,200',
        '€2,200 – €3,800',
        '€3,800 and more',
        'Not sure yet — please advise',
    ],

];
