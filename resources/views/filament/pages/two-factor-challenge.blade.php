<x-filament-panels::page>
    <form wire:submit.prevent="authenticate" class="space-y-6">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            @if ($useRecoveryCode)
                Zadej jeden ze svých recovery kódů.
            @else
                Otevři autentikační aplikaci a zadej aktuální 6-místný kód.
            @endif
        </div>

        {{ $this->form }}

        <div class="flex flex-col gap-3">
            <x-filament::button type="submit" color="primary" class="w-full">
                Pokračovat
            </x-filament::button>

            <button
                type="button"
                wire:click="toggleRecovery"
                class="text-sm text-primary-600 hover:underline dark:text-primary-400"
            >
                @if ($useRecoveryCode)
                    Použít TOTP kód místo recovery
                @else
                    Použít recovery kód
                @endif
            </button>

            <button
                type="button"
                wire:click="logout"
                class="text-sm text-gray-500 hover:underline"
            >
                Odhlásit se
            </button>
        </div>
    </form>
</x-filament-panels::page>
