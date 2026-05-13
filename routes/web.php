<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeLeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

require __DIR__.'/landing.php';

/*
|--------------------------------------------------------------------------
| Sitemap (musí být před fallback /{slug} routou)
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

/*
|--------------------------------------------------------------------------
| Cookie policy (OND-125) — společná napříč jazyky.
|--------------------------------------------------------------------------
| Banner v cookies.js linkuje na `/cookies` (bez locale prefixu).
| Text na stránce zatím česky; pokud přibyde lokalizace, přidat slug
| do config/slugs.php a přesunout pod localized routes group.
*/
Route::get('/cookies', [PageController::class, 'cookies'])
    ->middleware(SetLocale::class)
    ->name('cookies');

/*
|--------------------------------------------------------------------------
| Localized routes
|--------------------------------------------------------------------------
| Default locale (cs) has no URL prefix.
| Non-default locales (en, de) keep their /{locale}/... prefix.
| Route names follow the pattern: {locale}.{page}
*/

$locales       = ['cs', 'en', 'de'];
$defaultLocale = $locales[0];

// Default locale — no prefix
Route::middleware(SetLocale::class)->group(function () use ($defaultLocale) {
    $s = config('slugs.' . $defaultLocale);

    Route::get('/',            [PageController::class, 'home'])->name("{$defaultLocale}.home");
    Route::get($s['contact'],  [PageController::class, 'contact'])->name("{$defaultLocale}.contact");
    Route::get($s['price'],    [PageController::class, 'price'])->name("{$defaultLocale}.price");
    Route::get($s['privacy'],  [PageController::class, 'privacy'])->name("{$defaultLocale}.privacy");
    Route::get($s['projects'], [PageController::class, 'projects'])->name("{$defaultLocale}.projects");
    Route::get($s['projects'] . '/{url}', [PageController::class, 'project'])->name("{$defaultLocale}.project");
    Route::get($s['blog'],     [PageController::class, 'blog'])->name("{$defaultLocale}.blog");
    Route::get($s['blog'] . '/{slug}', [PageController::class, 'article'])->name("{$defaultLocale}.article");
});

// Non-default locales — keep /{locale}/... prefix
foreach (array_slice($locales, 1) as $locale) {
    Route::prefix($locale)
        ->middleware(SetLocale::class)
        ->group(function () use ($locale) {
            $s = config('slugs.' . $locale);

            Route::get('/',            [PageController::class, 'home'])->name("{$locale}.home");
            Route::get($s['contact'],  [PageController::class, 'contact'])->name("{$locale}.contact");
            Route::get($s['price'],    [PageController::class, 'price'])->name("{$locale}.price");
            Route::get($s['privacy'],  [PageController::class, 'privacy'])->name("{$locale}.privacy");
            Route::get($s['projects'], [PageController::class, 'projects'])->name("{$locale}.projects");
            Route::get($s['projects'] . '/{url}', [PageController::class, 'project'])->name("{$locale}.project");
            Route::get($s['blog'],     [PageController::class, 'blog'])->name("{$locale}.blog");
            Route::get($s['blog'] . '/{slug}', [PageController::class, 'article'])->name("{$locale}.article");
        });
}

/*
|--------------------------------------------------------------------------
| Contact form POST — universal endpoint (used by Alpine.js axios call)
|--------------------------------------------------------------------------
| Posts to /contact regardless of locale. CSRF protected.
| SetLocale middleware runs so že validační hlášky se přeloží podle URL
| segmentu (defaultně cs) — bez něj Laravel padne na config('app.locale').
*/
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware(SetLocale::class)
    ->name('contact.send');

/*
|--------------------------------------------------------------------------
| Homepage inline lead POST — persists do `landing_leads` (source=home.inline)
|--------------------------------------------------------------------------
| SetLocale middleware musí běžet i na POST, jinak `Request::validate()`
| čte locale z config('app.locale') a vrací EN hlášky bez ohledu na
| existující lang/cs/validation.php (OND-100 QA blocker).
*/
Route::post('/poptavka', [HomeLeadController::class, 'store'])
    ->middleware(SetLocale::class)
    ->name('home.lead.store');

/*
|--------------------------------------------------------------------------
| Backward-compat redirect: /{defaultLocale}/{path?} → /{path}  (301)
|--------------------------------------------------------------------------
| Handles old URLs that included the default-locale prefix.
| Also redirects old English-only URLs from original site:
|   /price       → /cenik
|   /projects    → /projekty
|   /privacy-policy → /zasady-ochrany-osobnich-udaju
*/
Route::get("/{$defaultLocale}/{path?}", function (string $path = '') {
    return redirect('/' . $path, 301);
})->where('path', '.*');

// Legacy redirects from original site (English URLs → Czech)
Route::get('/price',          fn() => redirect('/cenik', 301));
Route::get('/privacy-policy', fn() => redirect('/zasady-ochrany-osobnich-udaju', 301));
Route::get('/projects',       fn() => redirect('/projekty', 301));
Route::get('/projects/{any}', fn(string $any) => redirect('/projekty/' . $any, 301))->where('any', '.*');
// NOTE: /jak-na-to slug is preserved as-is — no redirect needed

/*
|--------------------------------------------------------------------------
| Fallback: /{slug} without locale prefix
|--------------------------------------------------------------------------
| Searches slug across non-default locales and 301 redirects to the
| correct prefixed URL. e.g. /about-us → /en/about-us
*/
Route::get('/{slug}', function (string $slug) {
    foreach (config('slugs') as $locale => $map) {
        if ($page = array_search($slug, $map, strict: true)) {
            return redirect(lroute($page, $locale), 301);
        }
    }
    abort(404);
})->where('slug', '[^/]+');
