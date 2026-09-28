<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * OND-459: testovací web (`itwebtech.ondrejkriska.cz`) nesmí jít do Googlu,
 * jakmile ostrý web běží na Webglobe. S `site.noindex` (env SEO_NOINDEX=true)
 * dostane každá odpověď z Laravelu `X-Robots-Tag: noindex, nofollow`.
 * robots.txt a meta robots řeší RobotsController a layouty.
 *
 * Statické soubory servíruje nginx bez Laravelu, ty hlavičku nedostanou.
 * Pokrývá je `Disallow: /` v robots.txt.
 */
class NoindexWhenConfigured
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (config('site.noindex')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
