<?php

namespace App\Listeners;

use App\Models\AdminLoginLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

/**
 * Zapíše do `admin_login_log` audit záznam o úspěšném přihlášení.
 *
 * Pouze pro admin guarda(y) — `web` (Filament panel). API tokeny
 * a podobné neauth flow se neloggují.
 */
class LogAdminLoginToAuditTrail
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        try {
            AdminLoginLog::create([
                'user_id' => $event->user->getKey(),
                'ip' => request()?->ip(),
                'user_agent' => mb_substr((string) (request()?->userAgent() ?? ''), 0, 512),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Nesmí rozbít login flow kvůli auditní vrstvě.
            Log::error('Admin login audit zápis selhal: '.$e->getMessage());
        }
    }
}
