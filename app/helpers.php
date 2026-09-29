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
     * Na `/zapisky/{slug}` je to `cs.article`, na `/projekty/{url}` `cs.project`
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

if (!function_exists('screenshot_is_square_ish')) {
    /**
     * OND-265: je snímek „skoro čtverec"? Dominantní zdroje galerie jsou
     * čtverce 1800×1800 — ty se do čtvercové dlaždice vejdou bez ořezu.
     * Všechno ostatní (1.48 diagramy, 1.33 screenshoty webů, 0.58 mobilní
     * obrazovky) čtvercový `cover` usekával; audit OND-254 to našel jako
     * ztrátu funkčně podstatného obsahu (picker: tabulka DÍL/SKLAD/POČET).
     */
    function screenshot_is_square_ish(?string $path): bool
    {
        $dims = screenshot_dimensions_any($path);
        if ($dims === null) {
            return false;
        }
        $ratio = $dims['width'] / $dims['height'];

        return $ratio >= 0.85 && $ratio <= 1.2;
    }
}

if (!function_exists('screenshot_tile_ratio')) {
    /**
     * OND-265: poměr stran dlaždice v mřížce galerie detailu.
     *
     * Vrací přirozený poměr snímku ořezaný do rozumného rozsahu, aby extrémní
     * portréty (address_data 1076×2545) nevyrobily dvoumetrový sloupec. Spolu
     * s `object-fit: contain` v CSS to znamená: uvnitř rozsahu nulový ořez
     * i nulové letterbox pruhy, mimo rozsah decentní pruhy místo useknutého
     * obsahu. Neznámé rozměry → 1 (původní čtverec).
     */
    function screenshot_tile_ratio(?string $path): float
    {
        $dims = screenshot_dimensions_any($path);
        if ($dims === null) {
            return 1.0;
        }

        return round(max(0.6, min(1.5, $dims['width'] / $dims['height'])), 4);
    }
}

if (!function_exists('portfolio_lead_image')) {
    /**
     * OND-449 (B-06): hlavní obrázek projektu — JEDEN pro kartu (/projekty,
     * „Další projekty“) i pro lead detailu. Přechod karta → detail slibuje
     * „tatáž věc zblízka“; když karta brala první čtvercový snímek a detail
     * první široký, obsah se při přechodu vyměnil (9 z 21 projektů).
     *
     * Pravidlo detailu (OND-202/265) zůstává: první široký snímek (poměr
     * ≥ 1,5), `hero` má přednost. Karta z něj bere ořez 16:10 shora.
     * Čtvercový snímek jako lead nikdy — přes celou šířku by přebil hero
     * (past OND-268). Řádky `thumbnail` jsou jen záloha pro projekt bez
     * širokého snímku (dnes žádný).
     *
     * @param \Illuminate\Support\Collection|iterable|null $screens
     */
    function portfolio_lead_image($screens)
    {
        $screens = collect($screens ?? []);
        $ordered = $screens->reject(fn ($s) => $s->type === 'thumbnail')
            ->sortBy(fn ($s) => $s->type === 'hero' ? 0 : 1)
            ->values();

        return $ordered->first(fn ($s) => screenshot_gallery_role($s->path) === 'wide');
    }
}

if (!function_exists('portfolio_card_thumbnail')) {
    /**
     * Náhled do karty projektu. OND-449 (B-06): = `portfolio_lead_image()`,
     * tentýž soubor jako lead detailu. Bez širokého snímku záloha z dřívějška:
     * explicitní `thumbnail`, pak `hero`, pak první.
     *
     * @param \Illuminate\Support\Collection|iterable|null $screens
     */
    function portfolio_card_thumbnail($screens)
    {
        $screens = collect($screens ?? []);

        return portfolio_lead_image($screens)
            ?? $screens->firstWhere('type', 'thumbnail')
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

        // OND-449 (B-06): přesná mapa „zdrojová cesta → varianty“, kterou
        // zapisuje build (plugin `image-variants` ve vite.config.js). Glob podle
        // basename níž u obrázků projektů nefunguje — `hero-1`, `gallery-2` …
        // sdílí 20+ projektů, glob vrátí stovky souborů a spadne do Alpine
        // fallbacku. Lead detailu se pak dosazoval až skriptem a obrázek karty
        // při přechodu přejel do prázdného rámu.
        $exact = responsive_image_variant_map()[$path] ?? null;
        if (is_array($exact)) {
            return $cache[$path] = responsive_image_srcsets_from_files($exact);
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

        // OND-265: největší WebP — cíl odkazu do lightboxu. Dřív tam šel
        // `fallback`, tj. 320px varianta: po kliknutí se otevřela miniatura.
        $largest = end($variants['webp']) ?: end($variants['avif']) ?: $fallback;
        $largest = explode(' ', (string) $largest)[0];

        return $cache[$path] = [
            'avif'     => implode(', ', $variants['avif']),
            'webp'     => implode(', ', $variants['webp']),
            'fallback' => $fallback,
            'largest'  => $largest,
        ];
    }
}

if (!function_exists('responsive_image_variant_map')) {
    /**
     * OND-449: `public/build/image-variants.json` z buildu —
     * `{ "projects/barana/hero-1.png": ["assets/hero-1-….avif", "assets/hero-1-….webp", …] }`
     * v pořadí, v jakém je vrací `import.meta.glob` v resources/js/app.js
     * (šířky 320 → 1536, u každé AVIF a WebP). Bez buildu prázdná mapa.
     *
     * @return array<string, list<string>>
     */
    function responsive_image_variant_map(): array
    {
        static $map = null;
        if ($map === null) {
            $file = public_path('build/image-variants.json');
            $map = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        }

        return $map;
    }
}

if (!function_exists('responsive_image_srcsets_from_files')) {
    /**
     * OND-449: srcset z přesného seznamu variant (viz `responsive_image_variant_map()`).
     * N-tá varianta formátu = N-tá šířka ze seznamu. Menší zdroj než 1536 px dá
     * u větších šířek tentýž soubor (Vite ho sloučí) — ten se vypíše jednou,
     * s nejmenší šířkou, stejně jako to dělá Alpine fallback.
     *
     * @param  list<string>  $files
     * @return array{avif:string,webp:string,fallback:string,largest:string}|null
     */
    function responsive_image_srcsets_from_files(array $files): ?array
    {
        $widthList = [320, 480, 640, 768, 960, 1280, 1536];
        $variants = ['avif' => [], 'webp' => []];
        $seen = [];
        $index = ['avif' => 0, 'webp' => 0];
        foreach ($files as $file) {
            $format = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! isset($variants[$format])) {
                continue;
            }
            $width = $widthList[$index[$format]++] ?? end($widthList);
            if (isset($seen[$file])) {
                continue;
            }
            $seen[$file] = true;
            $variants[$format][] = asset('build/' . ltrim($file, '/')) . ' ' . $width . 'w';
        }
        if (! $variants['avif'] && ! $variants['webp']) {
            return null;
        }
        $fallback = explode(' ', $variants['webp'][0] ?? $variants['avif'][0])[0];
        $largest = explode(' ', (string) (end($variants['webp']) ?: end($variants['avif'])))[0];

        return [
            'avif'     => implode(', ', $variants['avif']),
            'webp'     => implode(', ', $variants['webp']),
            'fallback' => $fallback,
            'largest'  => $largest,
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

if (!function_exists('project_transition_name')) {
    /**
     * OND-438 — jméno pro přechod mezi stránkami (`view-transition-name`)
     * u karty projektu a v hlavě jeho detailu. Obrázek a titulek karty musí
     * nést STEJNÉ jméno jako hlavní vizuál a H1 detailu, jinak se nespárují.
     *
     * Jméno je složené ze slugu, protože musí být na stránce unikátní: při
     * dvou stejných jménech prohlížeč přechod tiše vzdá (žádná chyba, jen se
     * stránka vymění naráz). Ze slugu nechává jen znaky platné v CSS
     * identifikátoru.
     *
     *   project_transition_name('pitarena', 'img')   → project-img-pitarena
     *   project_transition_name('pitarena', 'title') → project-title-pitarena
     */
    function project_transition_name(string $slug, string $part): string
    {
        return 'project-' . $part . '-' . preg_replace('/[^a-z0-9-]+/', '-', strtolower($slug));
    }
}

if (!function_exists('portfolio_catalog_item')) {
    /**
     * OND-471 — údaje jedné realizace pro prototypy přehledu /projekty
     * (`?v=1|2|3`). Titulek, věta, náhled a „komu“ počítá stejně jako
     * `<x-portfolio.work>`; navíc obory, výsledky a text pro hledání.
     *
     * @param  array<string, list<string>>  $sectors  obor → slugy (config/portfolio.php)
     * @return array<string, mixed>
     */
    function portfolio_catalog_item(\App\Models\Portfolio\PortfolioProject $project, string $locale, array $sectors = []): array
    {
        $t = $project->translation($locale);
        $title = $t?->title ?? $project->client_name ?? $project->slug;
        $text = $t?->subtitle ?: ($t?->summary ? \Illuminate\Support\Str::limit($t->summary, 110) : null);

        $category = __('projects.detail.category_label.' . $project->category);
        if (str_starts_with($category, 'projects.detail.category_label.')) {
            $category = ucfirst($project->category);
        }

        $brand = trim(preg_split('/\s+(?:—|–|\()|,/u', (string) $project->client_name)[0] ?? '');
        $firstWord = fn (string $s) => mb_strtolower(preg_split('/[\s,]+/u', trim($s))[0] ?? '');
        $showBrand = $brand !== ''
            && $firstWord($brand) !== $firstWord($title)
            && ! str_contains(mb_strtolower($title), mb_strtolower($brand));

        $hero = portfolio_card_thumbnail($project->screenshots ?? collect());
        $tags = $project->tags->map(fn ($tag) => $tag->translation($locale)?->name ?? $tag->slug)->all();
        $outcomes = $project->outcomes
            ->map(fn ($o) => $o->translation($locale)?->label)
            ->filter()
            ->take(3)
            ->values()
            ->all();

        $search = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii(implode(' ', array_filter([
            $title, $project->client_name, $text, $category, $project->year, ...$tags,
        ]))));

        return [
            'slug'     => $project->slug,
            'url'      => $project->detailUrl($locale),
            'title'    => $title,
            'text'     => $text,
            'category' => $project->category,
            'categoryLabel' => $category,
            'year'     => $project->year,
            'brand'    => $showBrand ? $brand : null,
            'hero'     => $hero,
            'alt'      => $hero?->translation()?->alt ?: __('projects.card.thumbnail_alt', ['project' => $title]),
            'sectors'  => array_keys(array_filter($sectors, fn ($slugs) => in_array($project->slug, $slugs, true))),
            'outcomes' => $outcomes,
            'live'     => $project->live_url,
            'search'   => $search,
        ];
    }
}
