<?php

if (!function_exists('lroute')) {
    /**
     * Generate a URL for a locale-aware named route.
     *
     * Route names follow the pattern: {locale}.{page}
     * Example: lroute('home')                          → /cs/
     *          lroute('home', 'en')                    → /en/
     *          lroute('project', null, ['url' => $s])  → /projekty/{s}
     */
    function lroute(string $name, ?string $locale = null, array $params = []): string
    {
        $locale ??= app()->getLocale();
        return route("{$locale}.{$name}", $params);
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
