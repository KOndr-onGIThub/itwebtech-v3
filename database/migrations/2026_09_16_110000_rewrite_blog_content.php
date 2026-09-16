<?php

use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-204 (OND-197 bod 11b) — nasazení přepsaných blogových textů.
 *
 * Texty článků žijí v DB (`article_translations`), ne v lang souborech.
 * `EnsureArticlesSeededSeeder` importuje SQL dumpy jen do prázdné tabulky,
 * takže na stagingu i produkci je migrace jediná cesta, jak změnu dostat ven
 * (stejný důvod jako u 2026_09_16_100000_rename_jargon_portfolio_tags.php).
 *
 * Obsah je v BlogContentSeeder, aby existoval jen na jednom místě — tentýž
 * seeder volá i EnsureArticlesSeededSeeder po importu dumpů do čerstvé DB.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — texty tam dostane
        // EnsureArticlesSeededSeeder hned po importu dumpů.
        if (! DB::table('articles')->exists()) {
            return;
        }

        (new BlogContentSeeder)->run();
    }

    public function down(): void
    {
        // Původní texty jsou ve `database/sql/article_translations.sql`
        // (jednorázový import staré databáze). Automatický rollback by je
        // musel znovu naparsovat a přepsal by i případné pozdější úpravy
        // z Filamentu, proto ho záměrně neděláme.
    }
};
