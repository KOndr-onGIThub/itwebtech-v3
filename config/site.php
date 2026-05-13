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
    |
    | Per OND-101: Toyota testimonial (Pavel Baudyš) is held back until the
    | client confirms publication consent. The slot is wired in code; data
    | only renders when SHOW_TOYOTA_TESTIMONIAL=true.
    */
    'features' => [
        'show_portfolio_section'  => env('SHOW_PORTFOLIO_SECTION', false),
        'show_toyota_testimonial' => env('SHOW_TOYOTA_TESTIMONIAL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking widget (Reservanto)
    |--------------------------------------------------------------------------
    | Per OND-102 (A3) / OND-116 (T15): sekundární CTA „Domluvit konzultaci"
    | používá Reservanto widget napojený na existující CEO účet (15-min ZDARMA
    | slot). Vypnutí: nastavit SITE_BOOKING_ENABLED=false.
    */
    'booking' => [
        'enabled'     => env('SITE_BOOKING_ENABLED', true),
        'provider'    => env('SITE_BOOKING_PROVIDER', 'reservanto'),
        'widget_id'   => env('SITE_BOOKING_WIDGET_ID', '20854'),
        'resource_id' => env('SITE_BOOKING_RESOURCE_ID', '32112'),
        'cta_text'    => env('SITE_BOOKING_CTA_TEXT', '15 min. konzultace ZDARMA'),
        'script_url'  => 'https://booking.reservanto.cz/Script/reservanto-script.js?id=20854',
    ],

];
