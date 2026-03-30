<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sitemap;
use Illuminate\Support\Facades\View;
use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;

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
        $sitemaps = Sitemap::all();
        View::share('sitemaps', $sitemaps);

        TwillNavigation::addLink(
            NavigationLink::make()->forModule('articles')
        );
    }
}
