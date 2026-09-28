<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generuje robots.txt s odkazem na sitemap.xml na aktuální doméně.
     *
     * Důvod dynamického generování: hardcodovaný hostname v public/robots.txt
     * způsobil, že staging (itwebtech.ondrejkriska.cz) inzeroval sitemap na
     * produkční doméně (itwebtech.cz), což lámalo Search Console submission.
     *
     * OND-384: tenhle controller ale na produkci nikdy neběžel. Vedle routy
     * existoval i statický `public/robots.txt` (OND-137 P4) a nginx statiku
     * servíruje dřív, než request dojde do `index.php` — takže živý web dál
     * inzeroval `Sitemap: https://ondraweb.cz/sitemap.xml`, tedy Framerovu
     * sitemapu na cizí doméně. Testy to nezachytily, protože PHPUnit chodí
     * přímo do HTTP kernelu a nginx vůbec nevidí.
     *
     * Statický soubor je proto smazaný a jeho obsah (včetně `Disallow: /admin`
     * a komentářů) žije tady — jediný zdroj pravdy. Guard proti návratu
     * duplicity: `RobotsTest::test_no_static_robots_txt_shadows_the_route`.
     */
    public function __invoke(): Response
    {
        // OND-459: testovací web (SEO_NOINDEX=true) zakáže vyhledávačům vše
        // a sitemapu neinzeruje.
        if (config('site.noindex')) {
            return response("User-agent: *\nDisallow: /\n", 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        $sitemapUrl = route('sitemap');

        $body = <<<TXT
        # Robots.txt — generováno dynamicky z aktuálního hostu (OND-384).
        # Nezakládat `public/robots.txt` — statika přebije tuhle routu.

        User-agent: *
        Allow: /

        # Block admin / Filament panel z indexace (citlivé login URL).
        Disallow: /admin
        Disallow: /admin/

        # Build assets a storage symlink necháváme indexovatelné (obrázky, OG karty
        # → Open Graph crawlers je potřebují).

        # Sitemap (Spatie laravel-sitemap, cache 600s — viz SitemapController).
        Sitemap: {$sitemapUrl}

        TXT;

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
