<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Setup flow pro TOTP 2FA.
 *
 * - Pokud nemá user secret: vygeneruje pending secret do session a zobrazí QR.
 * - Po zadání správného OTP: persist secret + recovery codes, zobrazí codes.
 * - Pokud už má aktivní 2FA: zobrazí status + možnost vypnout (po zadání hesla).
 */
class TwoFactorSetup extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Účet';

    protected static string $view = 'filament.pages.two-factor-setup';

    protected static ?string $slug = 'two-factor';

    /**
     * @var array<string, mixed>
     */
    public array $confirmData = [];

    /**
     * Pending secret držený v session, dokud user nepotvrdí kód.
     */
    public ?string $pendingSecret = null;

    /**
     * Pole recovery kódů zobrazených uživateli po úspěšné aktivaci.
     *
     * @var array<int, string>|null
     */
    public ?array $freshRecoveryCodes = null;

    public function getTitle(): string|Htmlable
    {
        return 'Dvoufaktorové ověření';
    }

    public static function getNavigationLabel(): string
    {
        return 'Dvoufaktorové ověření';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('confirmData')
            ->schema([
                TextInput::make('code')
                    ->label('Ověřovací kód')
                    ->placeholder('123456')
                    ->autocomplete(false)
                    ->required()
                    ->minLength(6)
                    ->maxLength(8),
            ]);
    }

    /**
     * Akce: vygeneruj nový secret a zobraz QR.
     */
    public function generate(TwoFactorAuthenticationService $service): void
    {
        $this->pendingSecret = $service->generateSecret();
        session()->put('two_factor.pending_secret', $this->pendingSecret);
        $this->freshRecoveryCodes = null;
    }

    public function getPendingSecret(): ?string
    {
        if ($this->pendingSecret !== null) {
            return $this->pendingSecret;
        }

        $secret = session()->get('two_factor.pending_secret');

        return is_string($secret) ? $secret : null;
    }

    /**
     * Akce: ověř OTP kód proti pending secretu a aktivuj 2FA.
     */
    public function confirm(TwoFactorAuthenticationService $service): void
    {
        $secret = $this->getPendingSecret();

        if ($secret === null) {
            Notification::make()
                ->title('Nejprve vygeneruj secret a naskenuj QR kód.')
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();
        $code = (string) ($data['code'] ?? '');

        if (! $service->verify($secret, $code)) {
            throw ValidationException::withMessages([
                'confirmData.code' => 'Neplatný kód. Zkus to znovu.',
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        $codes = $service->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $codes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        session()->forget('two_factor.pending_secret');
        session()->put('auth.two_factor.passed', true);

        $this->pendingSecret = null;
        $this->freshRecoveryCodes = $codes;
        $this->form->fill();

        Notification::make()
            ->title('2FA aktivováno.')
            ->body('Ulož si recovery kódy mimo aplikaci. Každý lze použít právě jednou.')
            ->success()
            ->send();
    }

    /**
     * Akce: zruš pending secret (uživatel chce začít znovu).
     */
    public function cancel(): void
    {
        session()->forget('two_factor.pending_secret');
        $this->pendingSecret = null;
        $this->freshRecoveryCodes = null;
        $this->form->fill();
    }

    /**
     * Akce: regeneruj recovery kódy (vyžaduje aktivní 2FA).
     */
    public function regenerateRecoveryCodes(TwoFactorAuthenticationService $service): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasEnabledTwoFactor()) {
            return;
        }

        $codes = $service->generateRecoveryCodes();

        $user->forceFill(['two_factor_recovery_codes' => $codes])->save();

        $this->freshRecoveryCodes = $codes;

        Notification::make()
            ->title('Recovery kódy přegenerovány.')
            ->success()
            ->send();
    }

    /**
     * Akce: vypni 2FA (s hard-delete secretu i kódů).
     */
    public function disable(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        session()->forget(['two_factor.pending_secret', 'auth.two_factor.passed']);
        $this->freshRecoveryCodes = null;
        $this->pendingSecret = null;

        Notification::make()
            ->title('2FA vypnuto.')
            ->warning()
            ->send();
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasEnabledTwoFactor()) {
            return [
                Action::make('regenerateRecoveryCodes')
                    ->label('Přegenerovat recovery kódy')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action('regenerateRecoveryCodes'),
                Action::make('disable')
                    ->label('Vypnout 2FA')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Opravdu vypnout 2FA?')
                    ->modalDescription('Po vypnutí přijdeš o ochranu druhým faktorem. Můžeš ji kdykoli znovu zapnout.')
                    ->action('disable'),
            ];
        }

        return [];
    }
}
