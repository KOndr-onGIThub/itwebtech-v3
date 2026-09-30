<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(SitemapGenerator $generator): Response
    {
        $xml = Cache::remember(
            (string) config('sitemap.cache_key', 'sitemap.xml'),
            (int) config('sitemap.cache_ttl', 600),
            fn () => $generator->build(),
        );

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * OND-485: XSL styl sitemapy. Skupiny (stránky / projekty / zápisky)
     * a jazyk se poznají podle začátku URL — prefixy se berou z config/slugs,
     * takže přejmenování sekce styl nerozbije.
     */
    public function stylesheet(): Response
    {
        $base     = rtrim(url('/'), '/');
        $prefixes = ['project' => [], 'article' => [], 'lang' => []];

        foreach (config('slugs') as $locale => $slugs) {
            $root = $base . ($locale === array_key_first(config('slugs')) ? '' : '/' . $locale);

            $prefixes['project'][] = $root . '/' . $slugs['projects'] . '/';
            $prefixes['article'][] = $root . '/' . $slugs['blog'] . '/';

            if ($root !== $base) {
                $prefixes['lang'][$locale] = $root . '/';
            }
        }

        // trim: XML deklarace musí být úplně první, Blade komentář nad ní nechá prázdný řádek.
        $xsl = trim(view('sitemap.stylesheet', ['base' => $base, 'prefixes' => $prefixes])->render());

        return response($xsl, 200, ['Content-Type' => 'text/xsl; charset=UTF-8']);
    }
}
