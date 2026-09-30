<?php

namespace App\Providers;

use App\Listeners\LogAdminLoginToAuditTrail;
use App\Listeners\NotifyOnFailedAdminLogins;
use App\Listeners\ResetTwoFactorChallengeOnLogin;
use App\Models\Article;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Portfolio\PortfolioProjectTranslation;
use App\Models\SitemapEntry;
use App\Models\SitemapOverride;
use App\Models\Slugs\ArticleSlug;
use App\Observers\FlushSitemapCache;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
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

        Event::listen(Login::class, ResetTwoFactorChallengeOnLogin::class);
        Event::listen(Login::class, LogAdminLoginToAuditTrail::class);
        Event::listen(Failed::class, NotifyOnFailedAdminLogins::class);

        // OND-485: změna článku, projektu nebo ručního záznamu sitemapy
        // hned zahodí její cache (jinak by nový článek čekal až 10 minut).
        // Překlad projektu nese lokalizovaný slug, slug článku jeho adresu.
        foreach ([
            Article::class,
            ArticleSlug::class,
            PortfolioProject::class,
            PortfolioProjectTranslation::class,
            SitemapEntry::class,
            SitemapOverride::class,
        ] as $model) {
            $model::observe(FlushSitemapCache::class);
        }
    }
}
