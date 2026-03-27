<?php

use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Localized routes
|--------------------------------------------------------------------------
| The first locale in the array is the default — it gets no URL prefix.
| All other locales keep their /{locale}/... prefix.
|
| Slugs are defined in config/slugs.php.
| Route names follow the pattern: {locale}.{page}
| e.g. cs.home, en.home, de.home
*/

$locales       = ['cs', 'en', 'de'];
$defaultLocale = $locales[0];

// Default locale — no prefix
Route::middleware(SetLocale::class)->group(function () use ($defaultLocale) {

    Route::get('/', [PageController::class, 'index'])->name("{$defaultLocale}.home");

    // TODO: add more routes following this pattern:
    // $s = config('slugs.' . $defaultLocale);
    // Route::get($s['about'],   [PageController::class, 'about'])->name("{$defaultLocale}.about");
    // Route::get($s['contact'], [PageController::class, 'contact'])->name("{$defaultLocale}.contact");

});

// Non-default locales — keep /{locale}/... prefix
foreach (array_slice($locales, 1) as $locale) {
    Route::prefix($locale)
        ->middleware(SetLocale::class)
        ->group(function () use ($locale) {

            Route::get('/', [PageController::class, 'index'])->name("{$locale}.home");

            // TODO: add more routes following this pattern:
            // $s = config('slugs.' . $locale);
            // Route::get($s['about'],   [PageController::class, 'about'])->name("{$locale}.about");
            // Route::get($s['contact'], [PageController::class, 'contact'])->name("{$locale}.contact");

        });
}

/*
|--------------------------------------------------------------------------
| Backward-compat redirect: /{defaultLocale}/{path?} → /{path}  (301)
|--------------------------------------------------------------------------
| Handles old URLs that included the default-locale prefix.
*/
Route::get("/{$defaultLocale}/{path?}", function (string $path = '') {
    return redirect('/' . $path, 301);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Fallback: /{slug} without locale prefix
|--------------------------------------------------------------------------
| Searches slug across non-default locales and 301 redirects to the
| correct prefixed URL.  e.g. /about-us → /en/about-us
| (Default-locale slugs are already registered above without a prefix.)
*/
Route::get('/{slug}', function (string $slug) {
    foreach (config('slugs') as $locale => $map) {
        if ($page = array_search($slug, $map, strict: true)) {
            return redirect(lroute($page, $locale), 301);
        }
    }
    abort(404);
})->where('slug', '[^/]+');
