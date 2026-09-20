<?php

if (!function_exists('lroute')) {
    /**
     * Generate a URL for a locale-aware named route.
     *
     * Route names follow the pattern: {locale}.{page}
     * Example: lroute('home')       → /
     *          lroute('home', 'en') → /en/
     *
     * OND-162 F4: home routes mají trailing slash konzistentně s tím, jak je
     * web serveruje (`/`, `/en/`, `/de/`). Bez toho hreflang URL bez slashe
     * neodpovídala canonical s lomítkem a Google to hlásil jako mismatch.
     */
    function lroute(string $name, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $url = route("{$locale}.{$name}");

        if ($name === 'home') {
            return rtrim($url, '/') . '/';
        }

        return $url;
    }
}

if (!function_exists('lroute_safe')) {
    /**
     * Jako lroute(), ale pro routes s povinnými parametry vrátí homepage.
     *
     * OND-212: chybové stránky se renderují pod routou, která chybu vyvolala.
     * Na `/jak-na-to/{slug}` je to `cs.article`, na `/projekty/{url}` `cs.project`
     * — obě mají povinný parametr. Layout i navbar staví přepínač jazyků přes
     * `lroute(current_page(), $locale)` a bez parametru z toho spadne
     * UrlGenerationException. Laravel pak místo naší 404 vrátí holou Symfony
     * stránku „An Error Occurred".
     *
     * Na běžných stránkách k výjimce nedojde: detail článku i projektu si
     * hreflang URL předává explicitně přes $hreflangs, sem to nikdy nedojde.
     */
    function lroute_safe(string $name, ?string $locale = null): string
    {
        try {
            return lroute($name, $locale);
        } catch (\Illuminate\Routing\Exceptions\UrlGenerationException) {
            return lroute('home', $locale);
        }
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

if (!function_exists('screenshot_dimensions')) {
    /**
     * Resolve intrinsic width/height for a storage-served portfolio screenshot
     * (OND-137 P4 — CLS reduction).
     *
     * Build-time Vite assets (`img/projects/...`) už `<x-responsive-image>` řeší
     * sám (preset šířky + height z imagetools metadata). Tady řešíme jen
     * uploady přes Filament admin do `storage/app/public/portfolio/...`, kde
     * žádná migrace s width/height column zatím neexistuje.
     *
     * Strategy:
     *  - getimagesize() na disk file (fast — čte jen image header, ne celý obsah).
     *  - cache::rememberForever s mtime-based key → re-upload souboru se stejným
     *    path invaliduje cache automaticky.
     *  - Pokud soubor chybí nebo není image, vrátí null a šablona dimensions
     *    prostě nevypíše (graceful degradation).
     *
     * @return array{width:int,height:int}|null
     */
    function screenshot_dimensions(?string $path): ?array
    {
        if ($path === null || $path === '' || !screenshot_is_storage($path)) {
            return null;
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        try {
            if (!$disk->exists($path)) {
                return null;
            }
            $mtime = $disk->lastModified($path);
            $absPath = $disk->path($path);
        } catch (\Throwable $e) {
            return null;
        }

        $cacheKey = 'screenshot_dims:' . md5($path) . ':' . $mtime;

        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function () use ($absPath) {
            $info = @getimagesize($absPath);
            if ($info === false || !isset($info[0], $info[1])) {
                return null;
            }
            return ['width' => (int) $info[0], 'height' => (int) $info[1]];
        });
    }
}

if (!function_exists('screenshot_dimensions_any')) {
    /**
     * OND-202: intrinsic rozměry screenshotu pro OBĚ rodiny cest —
     * storage uploady (`portfolio/...`, deleguje na screenshot_dimensions)
     * i build-time zdroje (`projects/...` v resources/img). Render-time
     * getimagesize čte jen hlavičku; cache klíč nese mtime, takže výměna
     * souboru invaliduje sama.
     *
     * @return array{width:int,height:int}|null
     */
    function screenshot_dimensions_any(?string $path): ?array
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (screenshot_is_storage($path)) {
            return screenshot_dimensions($path);
        }
        $absPath = resource_path('img/' . ltrim($path, '/'));
        if (!is_file($absPath)) {
            return null;
        }
        $cacheKey = 'shot-dims:' . $path . ':' . (string) @filemtime($absPath);
        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, static function () use ($absPath): ?array {
            $info = @getimagesize($absPath);
            if ($info === false || !isset($info[0], $info[1]) || (int) $info[1] === 0) {
                return null;
            }
            return ['width' => (int) $info[0], 'height' => (int) $info[1]];
        });
    }
}

if (!function_exists('screenshot_gallery_role')) {
    /**
     * OND-202 (zamítnutí karty boardem 2×): role snímku v galerii detailu
     * projektu, určená z poměru stran — přesně dle zadání boardu („jde
     * jednoduše vidět z rozměrů"):
     *
     *  - `wide` (poměr >= 1.5): hlavní vizuály — 3-device studio mockupy
     *    (2048×1152) a widescreen bannery (1500×750). Zobrazují se VÝHRADNĚ
     *    na celou šířku v přirozeném poměru, nikdy v malé kartě.
     *  - `card` (poměr < 1.5): podpůrné snímky — čtvercové detailní záběry
     *    (1800×1800), portréty stránek. Zobrazují se v párové mřížce
     *    v jednotném čtvercovém výřezu (dominantní čtverce = nulový ořez;
     *    plný snímek je vždy v lightboxu).
     *
     * Neznámé rozměry (např. absolutní URL) → `wide` (bez ořezu = bezpečné).
     */
    function screenshot_gallery_role(?string $path): string
    {
        $dims = screenshot_dimensions_any($path);
        if ($dims === null) {
            return 'wide';
        }
        return ($dims['width'] / $dims['height']) >= 1.5 ? 'wide' : 'card';
    }
}

if (!function_exists('portfolio_card_thumbnail')) {
    /**
     * OND-202: výběr náhledovky do malé karty (výpis projektů, homepage).
     * Wide 3-device mockup je v malé kartě nečitelný (zadání boardu) —
     * preferujeme explicitní `thumbnail`, pak první čtvercový/`card` snímek
     * (detailní záběr jednoho zařízení), teprve pak hero/první.
     *
     * @param \Illuminate\Support\Collection $screens
     */
    function portfolio_card_thumbnail($screens)
    {
        $screens = collect($screens ?? []);
        return $screens->firstWhere('type', 'thumbnail')
            ?? $screens->first(static fn ($s) => screenshot_gallery_role($s->path) === 'card')
            ?? $screens->firstWhere('type', 'hero')
            ?? $screens->first();
    }
}

if (!function_exists('responsive_image_srcsets')) {
    /**
     * Server-side resolver pro `<x-responsive-image>` (OND-123 iter3).
     *
     * Vite imagetools generuje 7 šířek (320/480/640/768/960/1280/1536) × 2 formáty
     * (AVIF, WebP). Ve výsledném buildu žijí jako `assets/{basename}-{hash}.{ext}`
     * a originálně byly přístupné jen přes `window.sharedImages` z JS bundlu —
     * tj. `<picture>` v HTML byl prázdný a srcset se nastavoval až přes Alpine
     * `x-init`. To blokovalo preload LCP obrázku, dokud Alpine nedoběhl.
     *
     * Tento helper najde varianty server-side a vrátí už hotové srcset stringy,
     * takže komponent může vyrenderovat `<source srcset="...">` přímo v HTML
     * streamu — browser začne fetchovat hned po parse hlavičky.
     *
     * Předpoklady:
     *   - varianty jsou ve `public/build/assets/{basename}-*.{avif,webp}`
     *   - velikost souboru monotónně roste se šířkou v rámci formátu
     *     (ověřeno na všech homepage obrázcích 2026-05-13)
     *
     * Pokud build assety neexistují (dev mód bez build), vrátí `null` a komponent
     * spadne zpět na klasický Alpine `x-init` flow.
     *
     * @return array{avif:string,webp:string,fallback:string}|null
     */
    function responsive_image_srcsets(string $path): ?array
    {
        static $cache = [];
        if (isset($cache[$path])) {
            return $cache[$path];
        }

        $basename = pathinfo($path, PATHINFO_FILENAME);
        if ($basename === '') {
            return $cache[$path] = null;
        }

        $assetsDir = public_path('build/assets');
        if (!is_dir($assetsDir)) {
            return $cache[$path] = null;
        }

        // Konfigurace odpovídá `import.meta.glob` query v resources/js/app.js
        $widthList = [320, 480, 640, 768, 960, 1280, 1536];

        $maxWidths = count($widthList);

        $variants = ['avif' => [], 'webp' => []];
        foreach (['avif', 'webp'] as $format) {
            // Realpath kvůli ochraně před symlinky / traverzal; basename už je
            // pathinfo()-očištěný, takže do glob patternu jde bezpečně.
            $matches = glob($assetsDir . '/' . $basename . '-*.' . $format) ?: [];

            $count = count($matches);
            if ($count === 0) {
                continue;
            }

            // Hotfix 500: pokud je víc variantů než widthList (např. `yolk_preview.jpg`
            // i `yolk_preview.webp` v resources/img/.../yolk/ — oba zdroje vygenerují
            // 7 šířek se stejným basename → glob vrátí 14), je nemožné z file system
            // pouze přiřadit šířky správně. Bail-out na Alpine fallback je bezpečnější
            // než vyrenderovat zkažený srcset (nebo dříve undefined-offset → 500).
            if ($count > $maxWidths) {
                return $cache[$path] = null;
            }

            // Třídění podle velikosti — Vite imagetools generuje výstupy
            // v pořadí query.w; menší šířka = menší soubor.
            usort($matches, fn ($a, $b) => filesize($a) <=> filesize($b));

            // Pokud je variantů míň než widthList (source byl menší než 1536px),
            // vezmeme jen prvních N šířek od nejmenší.
            $widths = array_slice($widthList, 0, $count);

            foreach ($matches as $i => $absPath) {
                $url = asset('build/assets/' . basename($absPath));
                $variants[$format][] = $url . ' ' . $widths[$i] . 'w';
            }
        }

        if (empty($variants['avif']) && empty($variants['webp'])) {
            return $cache[$path] = null;
        }

        // fallback src pro `<img>` — nejmenší WebP (univerzální podpora);
        // pokud WebP neexistuje, sáhneme po nejmenším AVIFu.
        $fallback = $variants['webp'][0] ?? $variants['avif'][0] ?? '';
        $fallback = explode(' ', $fallback)[0];

        return $cache[$path] = [
            'avif'     => implode(', ', $variants['avif']),
            'webp'     => implode(', ', $variants['webp']),
            'fallback' => $fallback,
        ];
    }
}

if (!function_exists('asset_v')) {
    /**
     * OND-237: URL veřejného assetu z `public/` s content-hash otiskem v query.
     *
     * Proč to existuje: base image `serversideup/php` má v
     * `/etc/nginx/server-opts.d/performance.conf` plošné pravidlo
     * `Cache-Control: public, max-age=31536000, immutable` pro VŠECHNY
     * obrázky/css/js podle přípony — ne jen pro hashované `/build/` assety.
     * `immutable` znamená, že prohlížeč soubor ani nereviduje, dokud rok
     * nevyprší; ani běžný reload nepomůže.
     *
     * Naše brand assety ale žijí na stabilní cestě (`img/logo/logo_main_svg.svg`),
     * takže rebranding itwebtech → ONDRAWEB (OND-199, 2026-09-16) obsah souboru
     * vyměnil, ale URL ne. Každý, kdo web navštívil dřív, dostával ze své cache
     * staré logo — přesně to hlásil board z mobilu (OND-237).
     *
     * Otisk je z OBSAHU (ne z mtime): deploy, který soubor nemění, URL nemění,
     * takže dlouhá cache zůstává účinná; změna souboru = nová URL = nová cache
     * entry. Tím se `immutable` stává pravdivým tvrzením a nemusíme headery
     * oslabovat (viz OND-123, kde šly nahoru kvůli PSI auditu).
     *
     * Výsledek se drží v per-request statické memo mapě; hashují se jen soubory,
     * které stránka opravdu vykreslí (logo, favicony, OG obrázek).
     */
    function asset_v(string $path): string
    {
        static $cache = [];

        $path = ltrim($path, '/');

        if (!array_key_exists($path, $cache)) {
            $absPath = public_path($path);
            $hash = is_file($absPath) ? @md5_file($absPath) : false;
            $cache[$path] = $hash === false ? null : substr($hash, 0, 8);
        }

        $url = asset($path);

        return $cache[$path] === null ? $url : $url . '?v=' . $cache[$path];
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
