<?php

use App\Http\Middleware\NoindexWhenConfigured;
use App\Http\Middleware\RedirectLegacyHost;
use App\Http\Middleware\SetLocale;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => SetLocale::class,
        ]);

        // Web běží za nginx + Cloudflare. Bez trustProxies Laravel nečte
        // X-Forwarded-Proto a generuje absolutní URL přes http://, což rozbíjí
        // sitemap, kanonické odkazy a redirecty.
        $middleware->trustProxies(at: '*', headers:
            Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB
        );

        // OND-455: staré domény → 301 na kanonický host. Za trustProxies
        // (host z X-Forwarded-Host), ale pořád před routováním.
        $middleware->append(RedirectLegacyHost::class);

        // OND-459: testovací web mimo vyhledávače (env SEO_NOINDEX=true).
        $middleware->append(NoindexWhenConfigured::class);

        // Globální security headers (HSTS, X-Frame-Options, X-Content-Type-Options,
        // Referrer-Policy, Permissions-Policy, …). Override defaultů v config/secure-headers.php.
        $middleware->append(SecureHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
