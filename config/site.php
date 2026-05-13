<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Phone number
    |--------------------------------------------------------------------------
    | Public phone displayed in sticky CTA and mobile bottom bar.
    | Set SITE_PHONE in env to enable. Empty/null hides the phone CTA.
    | Format: international (+420...).
    */
    'phone' => env('SITE_PHONE'),

    /*
    |--------------------------------------------------------------------------
    | Feature flags
    |--------------------------------------------------------------------------
    | Per OND-100: portfolio section on homepage stays hidden until 3 real
    | project screenshots are ready (T23, CEO). Toggle with env when assets
    | are in place.
    */
    'features' => [
        'show_portfolio_section' => env('SHOW_PORTFOLIO_SECTION', false),
    ],

];
