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

        // OND-204: dumpy v database/sql/ jsou stará data z itwebtech.cz.
        // Na čerstvé DB je hned po importu přepíšeme aktuálním zněním blogu —
        // na existující DB dělá totéž migrace 2026_09_16_110000_rewrite_blog_content.
        $this->call(BlogContentSeeder::class);

        // OND-219: dumpy DE překlady ani DE slugy vůbec neobsahují, takže
        // /de/blog by bez tohohle zůstal prázdný (filtr z OND-217).
        // Na existující DB dělá totéž migrace 2026_09_16_120000_seed_de_blog_content.
        $this->call(BlogContentDeSeeder::class);

        // OND-267: EN texty jsou v dumpech pořád ze starého importu (jiná
        // osnova než přepsaná CS/DE verze). Na existující DB dělá totéž
        // migrace 2026_09_23_100300_ond267_en_blog_obsah.
        $this->call(BlogContentEnSeeder::class);
    }
}
