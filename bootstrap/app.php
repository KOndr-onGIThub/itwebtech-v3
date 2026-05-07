<?php

use App\Http\Middleware\SetLocale;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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

        // Globální security headers (HSTS, X-Frame-Options, X-Content-Type-Options,
        // Referrer-Policy, Permissions-Policy, …). Override defaultů v config/secure-headers.php.
        $middleware->append(SecureHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
