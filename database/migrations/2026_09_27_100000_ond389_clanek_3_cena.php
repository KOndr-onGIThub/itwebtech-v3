<?php

use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-389 / znění OND-388 — článek 3 „Kolik stojí webové stránky" podle ceníku.
 *
 * Board v OND-347 zrušil tři cenová pásma, vstupní cenu 25 000 Kč / €1,000 /
 * 1.000 € i odmítací větu „rozpočet do dvaceti tisíc" / „under €800" /
 * „unter 800 €". Do `lang/` to došlo (OND-354, OND-385), článek žije v DB
 * a zůstal. Nové znění je v seederech, migrace ho přenese na existující DB.
 *
 * Záměrně jen `article_id = 3`, ne celé `BlogContent*Seeder::run()`. Texty
 * ostatních článků (4, 6, 10, 13) sice v seederech sedí s produkcí bajt po
 * bajtu (ověřeno 27. 9. 2026, 90/90 polí), ale `run()` navíc přepisuje slugy,
 * `published`, `active`, `bonus`/`extra`, DE obrázky podle CS a posouvá
 * `updated_at` všem pěti článkům. Nic z toho tahle změna nepotřebuje.
 *
 * Sloupce se berou přímo z `articles()[3]` seederu, aby text existoval na
 * jednom místě. Update je absolutní, opakovaný běh je no-op.
 */
return new class extends Migration
{
    private const ARTICLE_ID = 3;

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
            DB::table('article_translations')
                ->where('article_id', self::ARTICLE_ID)
                ->where('locale', $locale)
                ->update($seeder->articles()[self::ARTICLE_ID] + ['updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u 2026_09_23_100400_ond275_en_blog_obsah.
        // Návrat by vrátil zrušené ceny a odmítací větu, které board stáhl.
    }
};
