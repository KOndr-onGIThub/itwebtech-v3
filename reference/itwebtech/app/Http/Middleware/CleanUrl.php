<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CleanUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();


        // Pokud URI obsahuje "&" před "?", přesměruj na opravenou URL
        if (strpos($uri, '&') !== false && (strpos($uri, '?') === false || strpos($uri, '&') < strpos($uri, '?'))) {
            Log::info('CleanUrl middleware activated: ' . $uri);
            
            $cleanedUri = strtok($uri, '&');
            return redirect($cleanedUri, 301);
        }

        return $next($request);
    }
}
