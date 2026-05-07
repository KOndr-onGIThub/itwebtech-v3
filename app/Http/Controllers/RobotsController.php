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
     */
    public function __invoke(): Response
    {
        $sitemapUrl = route('sitemap');

        $body = "User-agent: *\nDisallow:\n\nSitemap: {$sitemapUrl}\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
