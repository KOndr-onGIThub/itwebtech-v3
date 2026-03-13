<?php

use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
| Redirects / to the default locale defined by APP_LOCALE in .env
*/
Route::get('/', function () {
    return redirect('/' . config('app.locale'));
});

/*
|--------------------------------------------------------------------------
| Localized routes  /{locale}/...
|--------------------------------------------------------------------------
| Slugs are defined in config/slugs.php.
| Route names follow the pattern: {locale}.{page}
| e.g. cs.home, en.home, de.home
| IMPORTANT: must be registered BEFORE the fallback /{slug} route!
*/
foreach (['cs', 'en', 'de'] as $locale) {
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
| Fallback: /{slug} without locale prefix
|--------------------------------------------------------------------------
| Searches slug across all locales and 301 redirects to the correct URL.
| e.g. /about-us → /en/about-us
*/
Route::get('/{slug}', function (string $slug) {
    foreach (config('slugs') as $locale => $map) {
        if ($page = array_search($slug, $map, strict: true)) {
            return redirect(lroute($page, $locale), 301);
        }
    }
    abort(404);
})->where('slug', '[^/]+');
