<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * OND-455: staré domény (`itwebtech.cz`, `www.ondraweb.cz`, …) → 301 na
 * kanonický host se zachovanou cestou i query.
 *
 * Běží jako globální middleware, tedy před routováním — před jazykovou
 * logikou i před 301 mapou starých cest v routes/web.php. Stará adresa tak
 * skončí nejvýš na dvou skocích: doména, pak cesta.
 *
 * Prázdný `redirects.canonical_host` = nic nedělá. Health check kontejneru
 * (`localhost`/`127.0.0.1`) ani kanonický host v seznamu nejsou.
 */
class RedirectLegacyHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = config('redirects.canonical_host');

        if ($canonical !== ''
            && $request->getHost() !== $canonical
            && in_array($request->getHost(), config('redirects.legacy_hosts'), true)) {
            return redirect()->away('https://'.$canonical.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
