<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const DEFAULT_LOCALE     = 'cs';
    public const NON_DEFAULT_LOCALES = ['en', 'de'];

    public function handle(Request $request, Closure $next): Response
    {
        $segment = $request->segment(1);

        $locale = in_array($segment, self::NON_DEFAULT_LOCALES)
            ? $segment
            : self::DEFAULT_LOCALE;

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
