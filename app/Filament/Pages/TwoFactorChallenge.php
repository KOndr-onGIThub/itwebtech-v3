<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Challenge stránka — uživatel je přihlášen, ale ještě neprošel 2FA kontrolou.
 *
 * Zobrazena middlewarem `EnsureTwoFactorAuthenticated`, pokud má user 2FA
 * aktivované a v session není flag `auth.two_factor.passed`.
 */
class TwoFactorChallenge extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.two-factor-challenge';

    protected static ?string $slug = 'two-factor-challenge';

    /** Tato stránka nesmí být v navigaci — je dostupná jen z auth flow. */
    protected static bool $shouldRegisterNavigation = false;

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public bool $useRecoveryCode = false;

    public function getTitle(): string|Htmlable
    {
        return 'Dvoufaktorové ověření';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Dvoufaktorové ověření';
    }

    public function mount(): void
    {
        if (! Auth::check()) {
            redirect()->to(Filament::getLoginUrl());

            return;
        }

        if (session()->get('auth.two_factor.passed') === true) {
            redirect()->intended(Filament::getUrl());

            return;
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('code')
                    ->label($this->useRecoveryCode ? 'Recovery kód' : 'Ověřovací kód')
                    ->placeholder($this->useRecoveryCode ? 'xxxxxxxxxx-xxxxxxxxxx' : '123456')
                    ->autocomplete(false)
                    ->required(),
            ]);
    }

    public function toggleRecovery(): void
    {
        $this->useRecoveryCode = ! $this->useRecoveryCode;
        $this->form->fill();
    }

    public function authenticate(TwoFactorAuthenticationService $service): mixed
    {
        $data = $this->form->getState();
        $code = (string) ($data['code'] ?? '');

        /** @var User $user */
        $user = Auth::user();

        $valid = $this->useRecoveryCode
            ? $service->consumeRecoveryCode($user, $code)
            : $service->verify((string) $user->two_factor_secret, $code);

        if (! $valid) {
            throw ValidationException::withMessages([
                'data.code' => $this->useRecoveryCode
                    ? 'Neplatný recovery kód.'
                    : 'Neplatný ověřovací kód.',
            ]);
        }

        session()->put('auth.two_factor.passed', true);

        return redirect()->intended(Filament::getUrl());
    }

    public function logout(): mixed
    {
        Auth::guard(Filament::getCurrentPanel()?->getAuthGuard() ?? 'web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to(Filament::getLoginUrl());
    }
}
