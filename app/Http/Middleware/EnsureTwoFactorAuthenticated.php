<?php

namespace App\Http\Middleware;

use App\Filament\Pages\TwoFactorChallenge;
use App\Filament\Pages\TwoFactorSetup;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hlídá 2FA gate v admin panelu.
 *
 * Pravidla:
 * 1. Anonymní user → propustit (Authenticate middleware sám přesměruje na login).
 * 2. User s aktivovaným 2FA + bez session flagu `auth.two_factor.passed` → vynutit challenge.
 * 3. User bez aktivovaného 2FA → vynutit setup stránku (single-admin režim,
 *    není koho výjimkovat).
 *
 * Vždy se propouští samotná setup/challenge stránka, livewire endpoint
 * a logout, jinak by uživatel uvízl v redirect smyčce.
 */
class EnsureTwoFactorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return $next($request);
        }

        if ($this->isAllowedRoute($request)) {
            return $next($request);
        }

        if ($user->hasEnabledTwoFactor()) {
            if (session()->get('auth.two_factor.passed') === true) {
                return $next($request);
            }

            return redirect()->guest($this->challengeUrl());
        }

        // Bez 2FA — vynutit setup.
        return redirect()->to($this->setupUrl());
    }

    private function isAllowedRoute(Request $request): bool
    {
        $path = '/'.ltrim($request->path(), '/');

        $challengePath = parse_url($this->challengeUrl(), PHP_URL_PATH) ?: '/admin/two-factor-challenge';
        $setupPath = parse_url($this->setupUrl(), PHP_URL_PATH) ?: '/admin/two-factor';
        $logoutPath = parse_url(Filament::getLogoutUrl(), PHP_URL_PATH) ?: '/admin/logout';

        // Livewire endpoint (POST /livewire/update) se používá pro form submity
        // na challenge i setup stránce — bez něj by se nedalo nic potvrdit.
        if (str_starts_with($path, '/livewire/')) {
            return true;
        }

        return $path === $challengePath
            || $path === $setupPath
            || $path === $logoutPath;
    }

    private function challengeUrl(): string
    {
        return TwoFactorChallenge::getUrl(panel: 'admin');
    }

    private function setupUrl(): string
    {
        return TwoFactorSetup::getUrl(panel: 'admin');
    }
}
