<?php

namespace App\Providers;

use App\Listeners\NotifyOnFailedAdminLogins;
use App\Listeners\ResetTwoFactorChallengeOnLogin;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
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
        Event::listen(Login::class, ResetTwoFactorChallengeOnLogin::class);
        Event::listen(Failed::class, NotifyOnFailedAdminLogins::class);
    }
}
