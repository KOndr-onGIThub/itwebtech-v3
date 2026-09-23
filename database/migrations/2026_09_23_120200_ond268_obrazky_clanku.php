<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-268 (B4) — AI a stock ilustrace v článcích `/jak-na-to`.
 *
 * Odpověď Ondřeje na otázku 2 (22. 9. 21:50) zní doslova: „Všechny pryč,
 * nahradit screenshoty z mých projektů. Kde nebude vhodný vlastní screenshot,
 * zůstane článek bez obrázku."
 *
 * Dvě poloviny řešení:
 *   a) kde náhrada JE, vyměnil se obsah souboru pod stejným názvem
 *      (`resources/img/articles/…`) — v DB se to neprojeví, Vite dá novému
 *      obsahu nový hash, takže ani immutable cache nevadí. Týká se
 *      `kdy-se-vyplati-aplikace-na-miru` (hero i náhled), `digi_marketing`,
 *      `best_web`, `redesign`, `redesign_preview` a `before-after_2`;
 *   b) kde náhrada NENÍ, pole se vyprázdní — tohle dělá tahle migrace.
 *
 * Šablony to unesou: `blog.blade.php` má dlaždici v `@if ($t->img_preview)`
 * a `article.blade.php` všechny čtyři sloty v `@if`. Karta bez obrázku je
 * titulek + perex + tlačítko, ne díra v mřížce.
 *
 * Důsledek, který není chyba: `img_main` se v `article.blade.php` skládá do
 * `image` v JSON-LD. Je v `array_filter`, takže se klíč jen vypustí a schéma
 * zůstane platné — tři články tím ale přijdou o náhledový obrázek v Google
 * rich snippetu. To je daň za odstranění cizího stocku.
 *
 * Mění se VŠECHNY lokalizace: název souboru je v `cs`, `en` i `de` stejný
 * (ověřeno na produkci 23. 9.), takže vyprázdnění jen `cs` by nechalo stock
 * svítit na `/en` a `/de`.
 *
 * Inertní na nenaplněné DB, idempotentní (druhý běh najde už prázdná pole).
 */
return new class extends Migration
{
    /**
     * Sloty bez náhrady — `slug článku => [sloupce na NULL]`.
     *
     * Proč zrovna tyhle (Jackovo odůvodnění, OND-263 kap. 7b):
     *   - `kolik-stoji-webove-stranky` — cenu nelze ilustrovat fotkou webu,
     *     cokoli z portfolia by byla výplň;
     *   - `potrebuje-vase-firma-webovou-stranku` — teze článku zní „někdy web
     *     nepotřebujete", hero s hezkým klientským webem ji popírá. Uvnitř
     *     článku dva reálné weby zůstávají, tam text o webech mluví;
     *   - `jak-se-pripravit-na-novy-web` — článek je o přípravě PŘED stavbou,
     *     hotový web to neilustruje. Jeho `_preview` byl navíc jen výřez
     *     prázdného modrého nebe, takže ve výpisu svítil prázdný čtverec;
     *   - `redesign-…` — jen závěrečné AI konfety „NEW WEBSITE LIVE".
     */
    private const CLEAR = [
        'potrebuje-vase-firma-webovou-stranku' => ['img_main', 'img_preview'],
        'jak-se-pripravit-na-novy-web'         => ['img_main', 'img_preview', 'img_mid'],
        'kolik-stoji-webove-stranky'           => ['img_main', 'img_preview', 'img_mid', 'img_end'],
        'redesign-webovych-stranek-duvody-signaly-a-jak-na-to' => ['img_end'],
    ];

    /**
     * Alt texty pro obrázky, které v článcích zůstávají — `slug => sloupec =>
     * locale => text`. Dosud se renderovaly s `alt=""` (viz doprovodná
     * schema migrace `…_120100_…`).
     */
    private const ALTS = [
        'potrebuje-vase-firma-webovou-stranku' => [
            'img_mid_alt' => [
                'cs' => 'Domovská stránka webu BARANA pro bioklimatické pergoly',
                'en' => 'Homepage of the BARANA website for bioclimatic pergolas',
                'de' => 'Startseite der BARANA-Website für bioklimatische Pergolen',
            ],
            'img_end_alt' => [
                'cs' => 'Domovská stránka webu Nové interiéry – podlahy a dveře',
                'en' => 'Homepage of the Nové interiéry website – flooring and doors',
                'de' => 'Startseite der Website Nové interiéry – Böden und Türen',
            ],
        ],
        'redesign-webovych-stranek-duvody-signaly-a-jak-na-to' => [
            'img_mid_alt' => [
                'cs' => 'Střechy Zajíc – původní web vedle dnešní verze',
                'en' => 'Střechy Zajíc – the original website next to the current version',
                'de' => 'Střechy Zajíc – die ursprüngliche Website neben der heutigen Version',
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('article_translations')->exists()) {
            return;
        }

        foreach (self::CLEAR as $slug => $columns) {
            $articleId = $this->articleId($slug);
            if (! $articleId) {
                continue;
            }

            DB::table('article_translations')
                ->where('article_id', $articleId)
                ->update(array_fill_keys($columns, null) + ['updated_at' => now()]);
        }

        foreach (self::ALTS as $slug => $columns) {
            $articleId = $this->articleId($slug);
            if (! $articleId) {
                continue;
            }

            foreach ($columns as $column => $byLocale) {
                foreach ($byLocale as $locale => $text) {
                    DB::table('article_translations')
                        ->where('article_id', $articleId)
                        ->where('locale', $locale)
                        ->update([$column => $text, 'updated_at' => now()]);
                }
            }
        }
    }

    /**
     * Zpátky se vrací jen alt texty (na NULL). Názvy souborů se NEobnovují:
     * vracely by na web přesně ten cizí stock, který board nechal odstranit,
     * a rollback migrace není místo, kde takové rozhodnutí otáčet. Kdyby bylo
     * potřeba, jsou názvy vypsané v komentáři výš a pole jsou editovatelná
     * ve Filamentu.
     */
    public function down(): void
    {
        if (! DB::table('article_translations')->exists()) {
            return;
        }

        foreach (self::ALTS as $slug => $columns) {
            $articleId = $this->articleId($slug);
            if (! $articleId) {
                continue;
            }

            DB::table('article_translations')
                ->where('article_id', $articleId)
                ->update(array_fill_keys(array_keys($columns), null));
        }
    }

    private function articleId(string $slug): ?int
    {
        return DB::table('article_slugs')->where('slug', $slug)->value('article_id');
    }
};
