<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Landing Subdomain
    |--------------------------------------------------------------------------
    |
    | When configured, the landing page is mounted on the root of this domain.
    | Example: weby.itwebtech.cz
    |
    */

    'domain' => env('LANDING_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Preview Path
    |--------------------------------------------------------------------------
    |
    | Local/staging fallback path for the isolated landing page surface when
    | no dedicated subdomain is available yet.
    |
    */

    'preview_path' => trim((string) env('LANDING_PREVIEW_PATH', 'lp/webove-stranky-na-miru'), '/'),

];
