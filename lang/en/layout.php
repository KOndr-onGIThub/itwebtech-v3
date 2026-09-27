<?php

return [

    'nav' => [
        'home'     => 'Home',
        'contact'  => 'Contact',
        'price'    => 'Pricing',
        'projects' => 'Projects',
        'blog'     => 'Notes',
        'about'    => 'About',
        'lang_switcher' => 'Language switcher',
    ],

    // OND-369: leftover from OND-307 — this key is not rendered anywhere
    // (header and drawer both use `home.sticky.cta`). Wording aligned with
    // the rest of the site so the old promise cannot ship by accident.
    'cta' => [
        'contact' => 'Write an enquiry',
    ],

    'footer' => [
        'rights'    => 'All rights reserved.',
        'developer' => 'Website by',
    ],

    'prefooter' => [
        'tagline'   => 'Custom websites and applications. Direct.',
        // OND-369: the pre-footer invited to a "free consultation" while the
        // rest of the site says "Write an enquiry" since OND-307. "Free" is
        // dropped — pricing (OND-354) rests on a threshold number.
        'cta'       => 'Write an enquiry',
        'nav_label' => 'Footer navigation',
    ],

    'modal' => [
        'close' => 'Close',
    ],

    // OND-167 — Cookie consent modal.
    'cookies' => [
        'title'       => 'Mind a few cookies?',
        'body'        => 'I only measure a few numbers about what works on the site. No data selling.',
        'policy_link' => 'See the cookie policy',
        'accept'      => 'Accept all',
        'reject'      => 'Decline',
        'close'       => 'Close',
    ],

    'gdpr_form_note' => 'By submitting you agree to our',
    'gdpr_form_link' => 'Privacy Policy',
    'footer_privacy_link' => 'Privacy Policy',
    'cookies_link'   => 'Cookies',

    'meta' => [
        'description' => 'Website and web application development. I help entrepreneurs succeed in the online world.',
    ],

];
