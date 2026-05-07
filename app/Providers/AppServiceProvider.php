<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Production i staging běží za HTTPS (Cloudflare + nginx). Pojistka
        // proti tomu, aby se v sitemapě/canonical odkazech objevilo http://
        // i kdyby byla špatně nastavená APP_URL nebo proxy hlavičky.
        if ($this->app->environment(['production', 'staging'])) {
            URL::forceScheme('https');
        }
    }
}
