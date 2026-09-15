<?php

/*
|--------------------------------------------------------------------------
| Secure Headers
|--------------------------------------------------------------------------
|
| Override defaultů z bepsvpt/secure-headers (vendor/bepsvpt/secure-headers/
| config/secure-headers.php). Provider používá `mergeConfigFrom`, takže stačí
| explicitně uvést jen klíče, které chceme změnit.
|
| Cílová známka v https://securityheaders.com pro produkci: B+.
|
*/

return [

    // X-Frame-Options: zabránit clickjackingu. `sameorigin` je default, držíme.
    'x-frame-options' => 'sameorigin',

    // Referrer-Policy: poslat referer jen na stejný origin.
    'referrer-policy' => 'strict-origin-when-cross-origin',

    /*
     * HSTS: na produkci vynutit HTTPS po dobu 1 roku včetně subdomén.
     * Aktivuje se jen pokud APP_ENV=production, ať lokálně/staging nezpůsobí
     * problémy s nešifrovaným HTTP.
     */
    'hsts' => [
        'enable' => env('SECURE_HEADERS_HSTS', env('APP_ENV') === 'production'),
        'max-age' => 31536000,
        'include-sub-domains' => true,
        'preload' => false,
    ],

    /*
     * Permissions-Policy: zakážeme všechno, co Filament admin nepotřebuje
     * (kamera, mikrofon, geolokace, USB, atd.). Vendor default už pokrývá
     * širokou množinu — přepíšeme ji přímo, ať máme kontrolu.
     */
    'permissions-policy' => [
        'enable' => true,
        'accelerometer' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'autoplay' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'camera' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'fullscreen' => ['none' => false, 'self' => true, '*' => false, 'origins' => []],
        'geolocation' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'gyroscope' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'magnetometer' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'microphone' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'midi' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'payment' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
        'usb' => ['none' => true, 'self' => false, '*' => false, 'origins' => []],
    ],

    /*
     * Content-Security-Policy: nezapínáme zatím (CSP vyžaduje pečlivé ladění
     * proti Vite/Livewire/Alpine — vyšší riziko produkčního breakage než
     * benefit). Možný follow-up.
     */
    'csp' => [
        'enable' => false,
    ],

];
