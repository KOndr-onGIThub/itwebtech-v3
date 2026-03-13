<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['cs', 'en', 'de'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (in_array($locale, self::SUPPORTED_LOCALES)) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }
}
