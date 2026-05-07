<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Vytvoří/aktualizuje single-admin uživatele podle `ADMIN_EMAIL`/`ADMIN_PASSWORD`.
 *
 * Běží jen mimo `local` a `testing` (ve vývoji se používá běžný User::factory
 * z `DatabaseSeeder`). Idempotentní — opakované spuštění aktualizuje heslo
 * podle aktuální env hodnoty.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            return;
        }

        $email = config('admin.email');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command?->warn('AdminUserSeeder: ADMIN_EMAIL nebo ADMIN_PASSWORD neni nastaveno, preskakuji.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
            ],
        );
    }
}
