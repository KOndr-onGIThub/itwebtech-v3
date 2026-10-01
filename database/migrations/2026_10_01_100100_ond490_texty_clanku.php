<?php

use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-490 / OND-492 — texty článků z dokumentu `texty` (OND-491, oddíly E a F):
 * „vlastní kód“ → „programuju od základu“, „jednoduchý prezentační web“,
 * „rozbitý odkaz na poptávku“, seznam „Snižuje cenu“ (6 položek), „nemám jak
 * ovlivnit“, dvojtečky uprostřed věty, kratší titulky článků 5, 11 a 12
 * (mění se H1 a <title>, slug ne) a „zhruba polovina“ místo „většina“.
 *
 * Nové znění je v seederech, migrace ho přenese na existující DB. Vzor
 * 2026_09_27_100000_ond389_clanek_3_cena.php, ale zapisují se jen pole, která
 * se mění (tabulka FIELDS), ne celé `articles()[id]`. Ostatní pole dotčených
 * článků se seederem sedí bajt po bajtu (ověřeno 1. 10. 2026 proti DB
 * testovací instance, kopie produkce), takže by se nic nezměnilo, ale zápis
 * jen nutných polí je bezpečnější. `run()` seederů se nespouští — přepsal by
 * slugy, `published` a `updated_at` všech článků.
 *
 * Update je absolutní, opakovaný běh je no-op.
 */
return new class extends Migration
{
    /** locale => article_id => pole */
    private const FIELDS = [
        'cs' => [
            3 => ['content_1', 'content_2'],
            5 => ['title', 'content_2'],
            7 => ['perex'],
            9 => ['content_1'],
            10 => ['content_2'],
            11 => ['title', 'content_1'],
            12 => ['title'],
            13 => ['content_1', 'content_2'],
        ],
        'en' => [
            3 => ['content_1', 'content_2'],
            10 => ['content_2'],
            13 => ['content_1', 'content_2'],
        ],
        'de' => [
            3 => ['content_1', 'content_2'],
            10 => ['content_2'],
            13 => ['content_1', 'content_2'],
        ],
    ];

    public function up(): void
    {
        // Na čerstvé DB ještě články nejsou — obsah tam dostane
        // EnsureArticlesSeededSeeder ze seederů, které už mají nové znění.
        if (! DB::table('articles')->exists()) {
            return;
        }

        $seeders = [
            'cs' => new BlogContentSeeder,
            'en' => new BlogContentEnSeeder,
            'de' => new BlogContentDeSeeder,
        ];

        foreach (self::FIELDS as $locale => $articles) {
            $source = $seeders[$locale]->articles();

            foreach ($articles as $articleId => $fields) {
                DB::table('article_translations')
                    ->where('article_id', $articleId)
                    ->where('locale', $locale)
                    ->update(array_intersect_key($source[$articleId], array_flip($fields)) + ['updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u 2026_09_27_100000_ond389_clanek_3_cena.
        // Návrat by vrátil znění, které board v OND-490 stáhl.
    }
};
