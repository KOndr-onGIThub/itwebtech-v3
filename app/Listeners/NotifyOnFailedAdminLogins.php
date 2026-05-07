<?php

namespace App\Listeners;

use App\Mail\AdminFailedLoginAlert;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Po překročení threshold failed loginů (per IP) v rámci 1h pošli email
 * na ADMIN_EMAIL. Zabraňuje brute-force pokusům na `/admin`.
 *
 * Counter je per-IP, drží se v cache. Po překročení se pošle alert
 * a okno se restartuje (TTL znova nastaví na 1h).
 */
class NotifyOnFailedAdminLogins
{
    private const THRESHOLD = 10;

    private const WINDOW_SECONDS = 3600;

    public function handle(Failed $event): void
    {
        $ip = request()?->ip() ?? 'unknown';
        $email = $event->credentials['email'] ?? null;
        $userAgent = (string) (request()?->userAgent() ?? 'unknown');

        $cacheKey = 'admin_failed_login:'.sha1($ip);
        $count = (int) Cache::get($cacheKey, 0) + 1;

        Cache::put($cacheKey, $count, self::WINDOW_SECONDS);

        if ($count < self::THRESHOLD) {
            return;
        }

        $recipient = config('admin.email');

        if (empty($recipient)) {
            Log::warning('Failed-login threshold překročen, ale ADMIN_EMAIL není nastaveno.', [
                'ip' => $ip,
                'count' => $count,
            ]);

            return;
        }

        try {
            Mail::to($recipient)->send(new AdminFailedLoginAlert(
                ip: $ip,
                attemptedEmail: is_string($email) ? $email : null,
                userAgent: $userAgent,
                attempts: $count,
                windowMinutes: (int) (self::WINDOW_SECONDS / 60),
            ));
        } catch (\Throwable $e) {
            // Nesmí selhat login flow kvůli SMTP.
            Log::error('Failed-login notifikace se nepodařila odeslat: '.$e->getMessage());
        }

        // Reset okna, ať nepošleme 2 emaily během minuty pro stejnou IP.
        Cache::forget($cacheKey);
    }
}
