<?php

use Database\Seeders\BlogLegacyDomainLinksSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-286 — 26 absolutních odkazů na `itwebtech.cz/jak-na-to/...` v textech článků.
 *
 * Texty článků se na stagingu ani na produkci seedery nepřepisují (dumpy z
 * `database/sql/` pouští `EnsureArticlesSeededSeeder` jen do prázdné tabulky),
 * takže úprava dat se tam dostane jedině migrací. Vlastní transformace žije
 * v `BlogLegacyDomainLinksSeeder`, aby měla čerstvá i naplněná DB jeden zdroj.
 *
 * Rozbor, proč to není jeden `str_replace`, je v hlavičce seederu.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — texty i jejich úklid tam dostane
        // `EnsureArticlesSeededSeeder` hned po importu dumpů.
        if (! DB::table('articles')->exists()) {
            return;
        }

        (new BlogLegacyDomainLinksSeeder)->run();
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u ostatních textových migrací blogu.
        // Návrat odkazů na opuštěnou doménu není stav, do kterého by se někdo
        // chtěl vrátit, a rollback by zahodil i pozdější úpravy z Filamentu.
    }
};
