<?php

use Database\Seeders\BlogContentEnSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 / redline OND-262 části C a D — EN obsah blogu.
 *
 * Stejný důvod jako u 2026_09_16_120000_seed_de_blog_content: SQL dumpy
 * v `database/sql/` pouští `EnsureArticlesSeededSeeder` jen do prázdné
 * tabulky, takže na stagingu ani na produkci se úprava dumpu neprojeví —
 * migrace je jediná cesta ven. Obsah žije v `BlogContentEnSeeder`, aby
 * existoval na jednom místě; tentýž seeder volá i EnsureArticlesSeededSeeder
 * po importu dumpů do čerstvé DB.
 *
 * Co se mění:
 *   - článek 3 „How much does a website cost" — kompletní nový text (část C),
 *   - článek 6 — titulek, perex a nový slug `when-a-custom-app-beats-a-spreadsheet`
 *     (část D); tělo zůstává a řeší ho OND-275.
 *
 * 301 se neprogramuje: starý slug zůstane v `article_slugs` jako `active = 0`
 * a `PageController::article()` na něj přesměruje — stejný mechanismus jako
 * u českých slugů v OND-204.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — EN obsah tam dostane
        // EnsureArticlesSeededSeeder hned po importu dumpů.
        if (! DB::table('articles')->exists()) {
            return;
        }

        (new BlogContentEnSeeder)->run();
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u seed_de_blog_content. Návrat
        // ke starému strojovému překladu by zahodil i pozdější úpravy
        // z Filamentu, které migrace nezaložila. Stažení verze z webu se
        // řeší přepnutím `active` na slugu, ne rollbackem migrace.
    }
};
