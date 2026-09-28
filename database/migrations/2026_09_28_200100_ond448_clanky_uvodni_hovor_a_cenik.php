<?php

use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-448 (OND-441, body B-02 a B-08) — články v CS/EN/DE podle webu.
 *
 * B-02: web neslibuje bezplatnou ani nezávaznou specifikaci a první krok je
 * úvodní hovor. B-08: počet stránek není hranice ceny ani balíčku a nikde
 * se netvrdí, že dodané texty cenu snižují (texty píše vždy Ondřej).
 * Znění je z dokumentu `texty-en-de` (OND-446) a žije v `articles()`
 * blogových seederů — migrace ho přenese na existující DB.
 *
 *   3 `kolik-stoji-webove-stranky`      content_1 (balíčky podle účelu, odstavec
 *                                        o typech stránek), content_2 (zvedne /
 *                                        sníží, specifikace)
 *   4 `jak-se-pripravit-na-novy-web`    content_2 (texty v „Co už máte“, věta
 *                                        o rozhodnutí nad specifikací)
 *   6 `kdy-se-vyplati-aplikace-na-miru` content_2 („zdarma při prvním hovoru“)
 *   7 design vs. obsah                  content_2 (texty v rozpočtu)
 *
 * Záměrně jen tahle pole, ne `BlogContent*Seeder::run()` (přepsal by slugy,
 * `bonus`/`extra` a `updated_at` ostatních článků — viz
 * 2026_09_27_100000_ond389_clanek_3_cena). Zbytek textu je v seederech shodný
 * s produkcí (ověřeno 28. 9. 2026 proti produkční DB, 12/12 řádků, všechna pole).
 *
 * Update je absolutní, opakovaný běh data nezmění (posune jen `updated_at`).
 */
return new class extends Migration
{
    private const FIELDS = [
        3 => ['content_1', 'content_2'],
        4 => ['content_2'],
        6 => ['content_2'],
        7 => ['content_2'],
    ];

    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — obsah tam dostane
        // EnsureArticlesSeededSeeder hned po importu dumpů.
        if (! DB::table('articles')->exists()) {
            return;
        }

        $seeders = [
            'cs' => new BlogContentSeeder,
            'en' => new BlogContentEnSeeder,
            'de' => new BlogContentDeSeeder,
        ];

        foreach ($seeders as $locale => $seeder) {
            $articles = $seeder->articles();

            foreach (self::FIELDS as $id => $fields) {
                DB::table('article_translations')
                    ->where('article_id', $id)
                    ->where('locale', $locale)
                    ->update(array_intersect_key($articles[$id], array_flip($fields)) + ['updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u 2026_09_27_100000_ond389_clanek_3_cena.
        // Návrat by vrátil sliby bezplatné specifikace a počty stránek, které board stáhl.
    }
};
