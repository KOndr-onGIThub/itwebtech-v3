<?php

return [

    'meta' => [
        'title'       => 'Contact — Ondřej Kriška, ONDRAWEB',
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
    'email_label'         => 'Email',
    'phone_label'         => 'Phone',
    'hours_label'         => 'Availability',
    'open_hours'          => 'I\'ll get back to you by the next business day. Weekends and public holidays don\'t count, but nothing gets lost.',
    'cta_consultation'    => 'Write to me',

    // OND-448 (B-01): form fields, button, consent line and confirmation live in
    // `home.inline_form` since the forms were unified — /kontakt renders the same `<x-lead-form>`.
    'form_heading'        => 'Contact form',
    // OND-371 — viz lang/cs/contact.php: „free“ jde pryč, termín odpovědi se
    // neopakuje počtvrté, slovník drží krok 3 („scope, timeline, exact price“).
    // Bez členů („with scope, timeline and price“) kvůli délce: plná varianta
    // se lámala na dva řádky se sirotkem „question.“ Takto 1 řádek, rezerva 30 px.
    'form_subheading'     => 'Tell me what you need. I\'ll reply personally and say whether I can help.',
    'required'            => 'This field is required.',
    'enter_valid_email'   => 'Please enter a valid email address.',
    'upload' => [
        'label'            => 'Add attachments',
        'drag_text'        => '— or drag them here',
        'browse'           => 'Choose files',
        'hint'             => 'PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP…',
        'max_files'        => 'Max. 5 files',
        'max_size'         => '20 MB in total',
        'remove'           => 'Remove',
        'error_too_many'   => 'You can attach at most 5 files at once.',
        'error_too_large'  => 'Attachments must not exceed 20 MB in total.',
        // OND-264: server-side attachment validation messages.
        'error_per_file'   => 'A single file can be at most :max MB.',
        'error_mime'       => 'This file type cannot be sent. Allowed: :types.',
        'error_failed'     => 'The attachment could not be saved. Please try again — or send me the file at ok@ondraweb.cz.',
    ],

    'message_success'     => 'Thanks for your message.',
    'message_error'       => 'An error occurred while sending. Please try again — or email me directly at ok@ondraweb.cz.',

    // OND-201 (bod 8): `contact.blade.php` uses hero / next_steps / thank_you
    // and the optional budget field (added in OND-136 for CS only), so the EN
    // page rendered raw translation keys. DE falls back to EN, so both were
    // broken. Copy follows the approved CS version.
    'hero' => [
        'page_mark_label' => 'CONTACT',
        'upline'          => 'You write to me directly.',
        'heading_html'    => 'No call centre,<br>no bot — <em>just Ondřej</em>.',
        'eyebrow'         => 'You write to me directly',
        'heading'         => 'You write straight to me, Ondřej.',
        'subline'         => 'I read your message personally. Write to me today and I\'ll get back to you by :date.',
        'photo_alt'       => 'Ondřej Kriška — author of this site and your contact person',
        'role_label'      => 'Developer, author of this site, your only contact',
    ],

    'next_steps' => [
        'eyebrow' => 'What happens next',
        'heading' => 'Three steps — no marketing funnel.',
        'steps'   => [
            [
                'title' => 'I\'ll get back to you by the next business day',
                'text'  => 'You get an email from me personally, not an automated confirmation. Write on a Friday evening and you will hear from me on Monday.',
            ],
            [
                'title' => 'A short intro call',
                'text'  => 'About 15 minutes on the phone. I find out what you are dealing with and tell you straight whether I can help. No presentation, no sales pressure.',
            ],
            [
                'title' => 'We agree on the next step',
                'text'  => 'If it makes sense, we go through the details and I write a specification with the scope, the timeline and an exact price. What is in the specification is what goes on the invoice.',
            ],
        ],
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
