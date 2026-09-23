<?php

use Database\Seeders\BlogLegacyDomainLinksSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-289 — zbylé absolutní odkazy na `itwebtech.cz` v textech článků,
 * tentokrát mimo sekci `/jak-na-to/` (tu uklidilo OND-286).
 *
 * Transformaci dělá `BlogLegacyDomainLinksSeeder`, stejně jako u OND-286 —
 * je to tatáž operace nad týmiž sloupci, jen jiná množina cílů. Migrace ho
 * volá znovu proto, že na produkci už migrace OND-286 proběhla (batch 18) a
 * sama se nezopakuje; seeder je idempotentní, takže druhý běh nad už
 * uklizenými `/jak-na-to/` odkazy nic nedělá.
 *
 * Texty článků se na stagingu ani na produkci seedery nepřepisují
 * (`EnsureArticlesSeededSeeder` pouští dumpy jen do prázdné tabulky), takže
 * úprava dat se tam dostane jedině migrací.
 *
 * Podpisový blok článku 12 (6 odkazů, text odkazu je jméno staré značky)
 * zůstává nedotčený — čeká na redakční rozhodnutí, ne na přepsání `href`.
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
