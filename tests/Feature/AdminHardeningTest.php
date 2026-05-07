<?php

namespace Tests\Feature;

use App\Listeners\NotifyOnFailedAdminLogins;
use App\Mail\AdminFailedLoginAlert;
use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_service_round_trip(): void
    {
        /** @var TwoFactorAuthenticationService $service */
        $service = app(TwoFactorAuthenticationService::class);

        $secret = $service->generateSecret();
        $this->assertNotEmpty($secret);

        // verify s vygenerovaným validním kódem prochází.
        $google2fa = new \PragmaRX\Google2FA\Google2FA;
        $code = $google2fa->getCurrentOtp($secret);
        $this->assertTrue($service->verify($secret, $code));

        // Špatný kód musí selhat.
        $this->assertFalse($service->verify($secret, '000000'));
    }

    public function test_recovery_codes_are_consumed_only_once(): void
    {
        /** @var TwoFactorAuthenticationService $service */
        $service = app(TwoFactorAuthenticationService::class);

        $codes = $service->generateRecoveryCodes(3);
        $this->assertCount(3, $codes);

        $user = User::factory()->create([
            'two_factor_secret' => 'whatever',
            'two_factor_recovery_codes' => $codes,
            'two_factor_confirmed_at' => now(),
        ]);

        $first = $codes[0];
        $this->assertTrue($service->consumeRecoveryCode($user, $first));
        $this->assertFalse($service->consumeRecoveryCode($user->fresh(), $first), 'Recovery kód nesmí jít použít dvakrát.');
        $this->assertCount(2, $user->fresh()->two_factor_recovery_codes);
    }

    public function test_failed_login_listener_sends_email_after_threshold(): void
    {
        Mail::fake();
        Cache::flush();

        Config::set('admin.email', 'admin@example.com');

        $listener = app(NotifyOnFailedAdminLogins::class);

        // Simuluj 9 pokusů — žádný email.
        for ($i = 0; $i < 9; $i++) {
            $listener->handle(new Failed('web', null, ['email' => 'attacker@example.com', 'password' => 'x']));
        }
        Mail::assertNothingSent();

        // 10. pokus překročí threshold a pošle alert.
        $listener->handle(new Failed('web', null, ['email' => 'attacker@example.com', 'password' => 'x']));

        Mail::assertSent(AdminFailedLoginAlert::class, function (AdminFailedLoginAlert $mail): bool {
            return $mail->hasTo('admin@example.com')
                && $mail->attempts === 10
                && $mail->attemptedEmail === 'attacker@example.com';
        });
    }

    public function test_failed_login_listener_skips_when_admin_email_missing(): void
    {
        Mail::fake();
        Cache::flush();

        Config::set('admin.email', null);

        $listener = app(NotifyOnFailedAdminLogins::class);

        for ($i = 0; $i < 10; $i++) {
            $listener->handle(new Failed('web', null, ['email' => 'x@example.com', 'password' => 'x']));
        }

        Mail::assertNothingSent();
    }

    public function test_login_event_clears_two_factor_passed_flag(): void
    {
        Event::fake([\Illuminate\Auth\Events\Login::class]);

        // Listener je registrovaný v AppServiceProvider — ověř pomocí přímého volání.
        session()->put('auth.two_factor.passed', true);
        $listener = new \App\Listeners\ResetTwoFactorChallengeOnLogin;
        $user = User::factory()->create();
        $listener->handle(new \Illuminate\Auth\Events\Login('web', $user, false));

        $this->assertNull(session()->get('auth.two_factor.passed'));
    }

    public function test_secure_headers_middleware_emits_expected_headers(): void
    {
        Config::set('app.env', 'production');
        Config::set('secure-headers.hsts.enable', true);

        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'sameorigin');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertNotEmpty($response->headers->get('Strict-Transport-Security'));
        $this->assertNotEmpty($response->headers->get('Permissions-Policy'));
    }
}
