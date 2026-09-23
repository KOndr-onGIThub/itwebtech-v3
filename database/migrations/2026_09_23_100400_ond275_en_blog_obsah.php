<?php

use Database\Seeders\BlogContentEnSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-275 / redline OND-274 — zbytek EN obsahu blogu.
 *
 * Doplňuje, co OND-267 (2026_09_23_100300) nechalo otevřené: články 4, 10
 * a 13 v celém rozsahu a u článku 6 perex a tělo. Slug čtyřky se mění na
 * `how-to-prepare-for-a-new-website`.
 *
 * Obsah žije v `BlogContentEnSeeder`, aby existoval na jednom místě —
 * migrace ho jen pustí na existující DB. Na čerstvé DB dělá totéž
 * `EnsureArticlesSeededSeeder` po importu dumpů. Seeder je idempotentní,
 * takže opakovaný běh s migrací z OND-267 nekoliduje: obě sahají na stejné
 * řádky absolutním updatem a vyhrává ta pozdější, tedy tahle.
 *
 * Pořadí (100400) je záměrně až za OND-267 (100300) i za vlnou 1
 * z OND-256 (2026_09_22_1000xx–1200xx), ať si obsah nepřepisujeme navzájem.
 *
 * 301 se neprogramuje: starý slug `how-to-define-website-development-requirements`
 * zůstane v `article_slugs` jako `active = 0` a `PageController::article()`
 * na něj přesměruje — stejný mechanismus jako u CS slugů v OND-204.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — EN obsah tam dostane
        // EnsureArticlesSeededSeeder hned po importu dumpů. Migrace, která
        // by záznam zakládala bezpodmínečně, by tuhle ochranu shodila.
        if (! DB::table('articles')->exists()) {
            return;
        }

        (new BlogContentEnSeeder)->run();
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u 100300 a seed_de_blog_content.
        // Návrat ke starému strojovému překladu by zahodil i pozdější úpravy
        // z Filamentu, které migrace nezaložila. Stažení verze z webu se řeší
        // přepnutím `active` na slugu, ne rollbackem migrace.
    }
};
