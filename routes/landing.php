<?php

use App\Http\Controllers\Landing\LandingLeadController;
use App\Http\Controllers\Landing\LandingPageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

$previewPath = config('landing.preview_path');
$domain = config('landing.domain');

if (filled($previewPath)) {
    Route::middleware(SetLocale::class)
        ->prefix($previewPath)
        ->group(function () use ($domain) {
            Route::get('/', [LandingPageController::class, 'show'])
                ->name($domain ? 'landing.preview' : 'landing.show');

            Route::post('/lead', [LandingLeadController::class, 'store'])
                ->name($domain ? 'landing.lead.preview' : 'landing.lead.store');
        });
}

if (filled($domain)) {
    Route::domain($domain)
        ->middleware(SetLocale::class)
        ->group(function () {
            Route::get('/', [LandingPageController::class, 'show'])->name('landing.show');
            Route::post('/lead', [LandingLeadController::class, 'store'])->name('landing.lead.store');
        });
}
