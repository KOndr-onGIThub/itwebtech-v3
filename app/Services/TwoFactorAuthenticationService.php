<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * Tenká vrstva nad pragmarx/google2fa: generování secretu,
 * QR kódu (SVG inline), recovery kódů a ověřování OTP.
 *
 * Single-admin režim — žádné multi-user UI, jen přímé operace nad
 * konkrétním Userem.
 */
class TwoFactorAuthenticationService
{
    public function __construct(private readonly Google2FA $engine) {}

    /**
     * Vygeneruje nový base32 secret (zatím bez uložení).
     */
    public function generateSecret(): string
    {
        return $this->engine->generateSecretKey();
    }

    /**
     * Vygeneruje sadu jednorázových recovery kódů.
     *
     * @return array<int, string>
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        return collect(range(1, $count))
            ->map(fn () => Str::random(10).'-'.Str::random(10))
            ->all();
    }

    /**
     * Otp Auth URL pro QR kód.
     */
    public function otpauthUrl(User $user, string $secret): string
    {
        $issuer = (string) config('app.name', 'Filament');

        return $this->engine->getQRCodeUrl($issuer, $user->email, $secret);
    }

    /**
     * Inline SVG s QR kódem (vhodné pro vykreslení v Blade přes {!! !!}).
     */
    public function qrCodeSvg(string $otpauthUrl, int $size = 220): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd
        );

        $svg = (new Writer($renderer))->writeString($otpauthUrl);

        // Bacon vkládá XML deklaraci, kterou Blade nepotřebuje.
        return preg_replace('/^<\?xml.*?\?>\s*/', '', $svg) ?? $svg;
    }

    /**
     * Ověří, že OTP odpovídá aktuálnímu (nebo blízkému) timeslotu pro daný secret.
     */
    public function verify(string $secret, string $otp): bool
    {
        $otp = preg_replace('/\s+/', '', $otp) ?? '';

        if ($otp === '') {
            return false;
        }

        return (bool) $this->engine->verifyKey($secret, $otp);
    }

    /**
     * Ověří jednorázový recovery kód a — pokud je validní — odstraní ho z user záznamu.
     */
    public function consumeRecoveryCode(User $user, string $code): bool
    {
        $code = trim($code);

        if ($code === '') {
            return false;
        }

        /** @var array<int, string> $codes */
        $codes = $user->two_factor_recovery_codes ?? [];

        $remaining = [];
        $matched = false;

        foreach ($codes as $existing) {
            if (! $matched && hash_equals($existing, $code)) {
                $matched = true;

                continue;
            }

            $remaining[] = $existing;
        }

        if (! $matched) {
            return false;
        }

        $user->forceFill(['two_factor_recovery_codes' => $remaining])->save();

        return true;
    }
}
