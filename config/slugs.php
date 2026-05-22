<?php

/*
|--------------------------------------------------------------------------
| Localized URL slugs
|--------------------------------------------------------------------------
| Per-locale slug map used by routes/web.php and the lroute() helper.
| Route names follow the pattern:  {locale}.{page}
| Example: lroute('home')          → /
|          lroute('home', 'en')    → /en/
|          lroute('contact')       → /kontakt
|          lroute('contact', 'en') → /en/contact
|
| NOTE: 'home' always maps to '/' for all locales.
| Dynamic routes (project detail, article) are NOT listed here —
| they use route() with parameters directly.
*/

return [

    'cs' => [
        'home'     => '/',
        'contact'  => 'kontakt',
        'price'    => 'cenik',
        'privacy'  => 'zasady-ochrany-osobnich-udaju',
        'cookies'  => 'cookies',
        'projects' => 'projekty',
        'blog'     => 'jak-na-to',
    ],

    'en' => [
        'home'     => '/',
        'contact'  => 'contact',
        'price'    => 'price',
        'privacy'  => 'privacy-policy',
        'cookies'  => 'cookies',
        'projects' => 'projects',
        'blog'     => 'blog',
    ],

    'de' => [
        'home'     => '/',
        'contact'  => 'kontakt',
        'price'    => 'preisliste',
        'privacy'  => 'datenschutz',
        'cookies'  => 'cookies',
        'projects' => 'projekte',
        'blog'     => 'blog',
    ],

];
