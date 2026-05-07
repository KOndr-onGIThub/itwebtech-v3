<?php

if (!function_exists('lroute')) {
    /**
     * Generate a URL for a locale-aware named route.
     *
     * Route names follow the pattern: {locale}.{page}
     * Example: lroute('home')       → /cs/
     *          lroute('home', 'en') → /en/
     */
    function lroute(string $name, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        return route("{$locale}.{$name}");
    }
}

if (!function_exists('screenshot_url')) {
    /**
     * Resolve a portfolio screenshot path to a public URL.
     *
     * Conventions:
     *  - paths starting with `img/projects/` → seeded data, served via Vite build pipeline
     *    (jen vrátíme cestu — `<x-responsive-image>` ji najde v `window.sharedImages`).
     *  - paths starting with `portfolio/`    → uploaded přes Filament admin do `storage/app/public/portfolio/...`,
     *                                          vrátíme veřejnou URL přes `Storage::disk('public')->url()`.
     *  - jiné cesty                          → vrátíme tak, jak jsou (např. plné absolutní URL).
     */
    function screenshot_url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'portfolio/')) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // img/projects/... → ponechat (build pipeline)
        return $path;
    }
}

if (!function_exists('screenshot_is_storage')) {
    /**
     * True když daná screenshot path patří do storage uploadu (Filament),
     * tj. nepatří do build-time Vite pipeline. Používá se v šablonách pro výběr
     * mezi `<img src=Storage::url>` a `<x-responsive-image>`.
     */
    function screenshot_is_storage(?string $path): bool
    {
        return $path !== null && str_starts_with($path, 'portfolio/');
    }
}

if (!function_exists('current_page')) {
    /**
     * Get the current page name without locale prefix.
     *
     * Route names are like 'cs.home', 'en.about', etc.
     * Returns: 'home', 'about', etc.
     */
    function current_page(): string
    {
        $name = request()->route()?->getName() ?? '';
        // Strip locale prefix (first segment before the dot)
        $dotPos = strpos($name, '.');
        return $dotPos !== false ? substr($name, $dotPos + 1) : 'home';
    }
}
