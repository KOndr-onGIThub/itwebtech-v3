@php
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $service = app(\App\Services\TwoFactorAuthenticationService::class);
    $pendingSecret = $this->getPendingSecret();
    $hasEnabled = $user->hasEnabledTwoFactor();
@endphp

<x-filament-panels::page>
    @if ($freshRecoveryCodes)
        <x-filament::section>
            <x-slot name="heading">Recovery kódy</x-slot>
            <x-slot name="description">
                Ulož si je na bezpečné místo mimo aplikaci. Každý kód lze použít jen jednou pro přihlášení bez TOTP.
            </x-slot>

            <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                @foreach ($freshRecoveryCodes as $code)
                    <code class="rounded bg-gray-100 px-2 py-1 dark:bg-gray-800">{{ $code }}</code>
                @endforeach
            </div>
        </x-filament::section>
    @endif

    @if ($hasEnabled)
        <x-filament::section>
            <x-slot name="heading">2FA je aktivní</x-slot>
            <x-slot name="description">
                Při přihlášení jsi vyzván k zadání 6-místného kódu z autentikační aplikace
                (Google Authenticator, 1Password, Authy, Bitwarden, …).
            </x-slot>

            <div class="text-sm text-gray-600 dark:text-gray-300">
                Aktivováno: {{ $user->two_factor_confirmed_at?->format('d.m.Y H:i') }}<br>
                Zbývající recovery kódy:
                <strong>{{ count($user->two_factor_recovery_codes ?? []) }}</strong>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <x-slot name="heading">Aktivace dvoufaktorového ověření</x-slot>
            <x-slot name="description">
                Po aktivaci budeš při každém přihlášení potřebovat 6-místný kód
                z autentikační aplikace.
            </x-slot>

            @if (! $pendingSecret)
                <x-filament::button wire:click="generate" icon="heroicon-o-qr-code">
                    Vygenerovat QR kód
                </x-filament::button>
            @else
                @php
                    $otpauth = $service->otpauthUrl($user, $pendingSecret);
                    $svg = $service->qrCodeSvg($otpauth);
                @endphp

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <p class="mb-2 text-sm text-gray-700 dark:text-gray-300">
                            <strong>1.</strong> Naskenuj QR kód autentikační aplikací.
                        </p>
                        <div class="inline-block rounded bg-white p-2">
                            {!! $svg !!}
                        </div>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            Manuálně:
                            <code class="break-all">{{ $pendingSecret }}</code>
                        </p>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-gray-700 dark:text-gray-300">
                            <strong>2.</strong> Zadej kód z aplikace pro potvrzení.
                        </p>

                        <form wire:submit.prevent="confirm" class="space-y-4">
                            {{ $this->form }}
                            <div class="flex gap-2">
                                <x-filament::button type="submit" color="primary">
                                    Aktivovat
                                </x-filament::button>
                                <x-filament::button color="gray" wire:click="cancel" type="button">
                                    Zrušit
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </x-filament::section>
    @endif
</x-filament-panels::page>
