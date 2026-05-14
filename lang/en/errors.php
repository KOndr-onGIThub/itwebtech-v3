<?php

return [

    // OND-136: minimum copy for error pages (404, 500, 503, …).
    // Keeps the site tone — no quirky humour, just a quick return path
    // back to the conversion flow (homepage / contact). Engineer (B2)
    // wires this up in `resources/views/errors/{code}.blade.php`.

    '404' => [
        'meta' => [
            'title'       => 'Page not found (404) — Ondřej Kriška',
            'description' => 'This page no longer exists or has been moved. Head back to the homepage or send me a message — I\'ll help you find what you need.',
        ],
        'eyebrow'      => 'Error 404',
        'heading'      => 'Sorry, that page isn\'t here.',
        'subheading'   => 'The page you\'re looking for has either been moved, deleted, or never existed.',
        'help'         => 'If you got here from a link that should work, let me know — I\'ll fix it.',
        'cta_primary'  => 'Back to homepage',
        'cta_secondary'=> 'Tell me what you were looking for',
    ],

    '500' => [
        'meta' => [
            'title'       => 'Server error (500) — Ondřej Kriška',
            'description' => 'Something broke on our side. Please try again in a moment, or get in touch directly.',
        ],
        'heading'      => 'Something went wrong on our side.',
        'subheading'   => 'The error is on me, not on you. Please try again in a moment.',
        'help'         => 'If the problem persists, get in touch directly — I\'ll sort it out.',
        'cta_primary'  => 'Try again',
        'cta_secondary'=> 'Get in touch',
    ],

    '503' => [
        'meta' => [
            'title'       => 'Site temporarily down — Ondřej Kriška',
            'description' => 'The site is briefly down for maintenance. It will be back shortly.',
        ],
        'heading'      => 'Quick maintenance in progress.',
        'subheading'   => 'The site will be back shortly. If you need something urgently, get in touch directly.',
        'cta_primary'  => 'Send me an email',
    ],

];
