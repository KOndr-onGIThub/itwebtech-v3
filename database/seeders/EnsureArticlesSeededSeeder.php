<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Idempotentní guard pro blogové články.
 *
 * Pokud je tabulka `articles` prázdná, spustí ImportOldDataSeeder a doplní
 * články, jejich slugy a překlady ze SQL dumpů v `database/sql/`.
 * Pokud tabulka už články obsahuje, neudělá nic a vrátí se.
 *
 * Volá se z `docker/entrypoint.d/50-laravel-deploy.sh` při startu kontejneru.
 *
 * Bezpečné pro opakované spuštění:
 *   - count check zajistí no-op, jakmile data existují
 *   - ImportOldDataSeeder používá INSERT IGNORE, takže ani by data nepřepsal
 *
 * Viz OND-77.
 */
class EnsureArticlesSeededSeeder extends Seeder
{
    public function run(): void
    {
        $before = DB::table('articles')->count();
        $this->command->info("[ensure-articles] articles count: {$before}");

        if ($before > 0) {
            $this->command->info('[ensure-articles] OK, články existují, import se přeskakuje.');

            return;
        }

        $this->command->info('[ensure-articles] tabulka articles je prázdná → spouštím ImportOldDataSeeder.');
        $this->call(ImportOldDataSeeder::class);

        $after = DB::table('articles')->count();
        $this->command->info("[ensure-articles] articles count po importu: {$after}");
    }
}
