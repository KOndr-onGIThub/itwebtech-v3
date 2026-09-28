<?php

use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-432 (OND-418) — publikace článků 1, 5, 7, 9, 11, 12 v CS/EN/DE.
 *
 * Texty jsou z dokumentů `clanky-final-cs/en/de` (OND-430) s redliny
 * `review-preklady` (OND-431) a žijí v `articles()` blogových seederů.
 * Migrace je přenese na existující DB, na čerstvé to udělá
 * `EnsureArticlesSeededSeeder`.
 *
 * Záměrně ne `BlogContent*Seeder::run()`: ten by kromě textů přepsal slugy
 * a `bonus`/`extra` i u ostatních článků a posunul `updated_at` všem
 * (stejný důvod jako u 2026_09_27_100000_ond389_clanek_3_cena). Tady se mění:
 *
 *   1. nové články: všechny sloupce z `articles()[id]` (texty, obrázky,
 *      `bonus`/`extra` = null). DE řádky dosud neexistují, zakládají se.
 *   2. publikované 3, 4, 10, 13: jen pole, do kterých přibyla věta
 *      s odkazem na nový článek. Zbytek textu je v seederu shodný
 *      s produkcí (ověřeno na kopii produkční DB 28. 9. 2026).
 *   3. `published = 1` pro nové články. Článek 8 zůstává stažený,
 *      jeho adresa vede na 11 (`PageController::REMOVED_ARTICLE_REDIRECTS`).
 *   4. slugy: CS beze změny, EN 9 a 12 nové (starý zůstane `active = 0`
 *      kvůli 301), DE všech šest nových.
 *
 * Update je absolutní, opakovaný běh data nezmění (posune jen `updated_at`).
 */
return new class extends Migration
{
    private const ARTICLE_IDS = [1, 5, 7, 9, 11, 12];

    /** Pole publikovaných článků, do kterých přibyla věta s odkazem. */
    private const LINKED_FIELDS = [
        3  => ['content_2'],
        4  => ['content_1', 'content_2'],
        10 => ['content_2'],
        13 => ['content_1', 'content_2'],
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

        $ids = DB::table('articles')->whereIn('id', self::ARTICLE_IDS)->pluck('id')->all();

        foreach ($seeders as $locale => $seeder) {
            $articles = $seeder->articles();

            foreach ($ids as $id) {
                $this->upsertTranslation($id, $locale, $articles[$id]);
            }

            foreach (self::LINKED_FIELDS as $id => $fields) {
                DB::table('article_translations')
                    ->where('article_id', $id)
                    ->where('locale', $locale)
                    ->update(array_intersect_key($articles[$id], array_flip($fields)) + ['updated_at' => now()]);
            }
        }

        DB::table('articles')
            ->whereIn('id', $ids)
            ->where('published', false)
            ->update(['published' => true, 'updated_at' => now()]);

        foreach ([9, 12] as $id) {
            if (in_array($id, $ids, true)) {
                $this->activateSlug($id, 'en', BlogContentEnSeeder::NEW_EN_SLUGS[$id][1]);
            }
        }

        foreach ($ids as $id) {
            $this->activateSlug($id, 'de', BlogContentDeSeeder::DE_SLUGS[$id]);
        }
    }

    public function down(): void
    {
        // Záměrně bez rollbacku, stejně jako u ond389 a ond275. Návrat by
        // vrátil starý strojový import (EN) a bloky v `bonus`, které se
        // vykreslovaly pod textem. Stažení článku = `published = 0`
        // a záznam v `REMOVED_ARTICLE_REDIRECTS`, ne rollback migrace.
    }

    private function upsertTranslation(int $id, string $locale, array $content): void
    {
        $row = DB::table('article_translations')
            ->where('article_id', $id)
            ->where('locale', $locale);

        $payload = $content + ['active' => 1, 'updated_at' => now()];

        // Ne updateOrInsert: ten by při každém běhu přepsal i created_at.
        if ((clone $row)->exists()) {
            $row->update($payload);

            return;
        }

        DB::table('article_translations')->insert($payload + [
            'article_id' => $id,
            'locale'     => $locale,
            'created_at' => now(),
        ]);
    }

    private function activateSlug(int $id, string $locale, string $slug): void
    {
        DB::table('article_slugs')
            ->where('article_id', $id)
            ->where('locale', $locale)
            ->where('slug', '!=', $slug)
            ->where('active', true)
            ->update(['active' => 0, 'updated_at' => now()]);

        $exists = DB::table('article_slugs')
            ->where('article_id', $id)
            ->where('locale', $locale)
            ->where('slug', $slug);

        if ((clone $exists)->where('active', true)->exists()) {
            return;
        }

        if ($exists->exists()) {
            $exists->update(['active' => 1, 'updated_at' => now()]);

            return;
        }

        DB::table('article_slugs')->insert([
            'article_id' => $id,
            'locale'     => $locale,
            'slug'       => $slug,
            'active'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
