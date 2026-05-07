<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

/**
 * Po každém úspěšném loginu zruš flag `auth.two_factor.passed`,
 * aby uživatel s aktivním 2FA musel znovu projít challenge.
 */
class ResetTwoFactorChallengeOnLogin
{
    public function handle(Login $event): void
    {
        session()->forget('auth.two_factor.passed');
    }
}
