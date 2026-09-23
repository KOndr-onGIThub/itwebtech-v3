<?php

use Database\Seeders\BlogContentDeSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-219 (navazuje na OND-217, texty z OND-218) — nasazení DE překladů blogu.
 *
 * Články neměly DE slug ani DE překlad, takže /de/blog byl po fixu z OND-217
 * prázdný. Stejný důvod jako u 2026_09_16_110000_rewrite_blog_content.php:
 * `EnsureArticlesSeededSeeder` importuje SQL dumpy jen do prázdné tabulky,
 * takže na stagingu i produkci je migrace jediná cesta, jak obsah dostat ven.
 *
 * Obsah je v BlogContentDeSeeder, aby existoval jen na jednom místě — tentýž
 * seeder volá i EnsureArticlesSeededSeeder po importu dumpů do čerstvé DB.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — DE obsah tam dostane
        // EnsureArticlesSeededSeeder hned po importu dumpů.
        if (! DB::table('articles')->exists()) {
            return;
        }

        (new BlogContentDeSeeder)->run();
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u rewrite_blog_content. Smazání
        // DE řádků by zahodilo i pozdější úpravy z Filamentu, které migrace
        // nezaložila. Stažení DE verze z webu se řeší přepnutím `active` na
        // slugu, ne rollbackem migrace.
    }
};
