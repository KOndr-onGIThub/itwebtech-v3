<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact details
    |--------------------------------------------------------------------------
    | Values used across the public site (contact page, footer).
    | Phone is optional — if empty, no phone link is rendered anywhere.
    */

    // E-mail address that receives contact-form submissions.
    'to' => env('CONTACT_TO', 'ok@ondraweb.cz'),

    // Public phone number. Rendered as a clickable tel: link only when set.
    'phone' => env('CONTACT_PHONE', '+420 728 697 712'),

];
