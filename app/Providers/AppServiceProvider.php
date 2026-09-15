<?php

namespace App\Providers;

use App\Listeners\LogAdminLoginToAuditTrail;
use App\Listeners\NotifyOnFailedAdminLogins;
use App\Listeners\ResetTwoFactorChallengeOnLogin;
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
    }
}
