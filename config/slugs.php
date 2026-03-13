<?php

/*
|--------------------------------------------------------------------------
| Localized URL slugs
|--------------------------------------------------------------------------
| Per-locale slug map used by routes/web.php and the lroute() helper.
| Route names follow the pattern:  {locale}.{page}
| Example: lroute('home')        → /cs/
|          lroute('home', 'en')  → /en/
|          lroute('about')       → /cs/o-nas
|          lroute('about', 'de') → /de/ueber-uns
*/

return [

    'cs' => [
        'home'    => '/',
        // TODO: add your localized slugs:
        // 'about'   => 'o-nas',
        // 'contact' => 'kontakt',
    ],

    'en' => [
        'home'    => '/',
        // 'about'   => 'about-us',
        // 'contact' => 'contact',
    ],

    'de' => [
        'home'    => '/',
        // 'about'   => 'ueber-uns',
        // 'contact' => 'kontakt',
    ],

];
