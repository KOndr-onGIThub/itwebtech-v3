<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-292 — náhledové obrázky tří článků ve výpisu /zapisky.
 *
 * Rozhodnutí boardu 23. 9. 2026 (kartička OND-292, odpověď „doplnit
 * screenshoty"): tři z pěti publikovaných článků neměly ve výpisu náhled.
 * Vzniklo to v OND-268, kde šly pryč AI a stock ilustrace — druhá půlka
 * tehdejšího zadání („nahradit screenshoty z mých projektů") se u těchhle
 * tří nenaplnila, protože se pro ně nenašel vhodný vlastní záběr.
 *
 * Náhledy jsou čtvercové výřezy z případovek, stylem shodné s těmi dvěma,
 * které už ve výpisu jsou (`redesign_preview.webp`, `jak-muze-…_preview.webp`).
 * Vygenerované skriptem `ond292/previews.cjs`, ať jdou kdykoli přegenerovat.
 *
 * ⚠️ Soubory se NEjmenují `{slug}_preview.webp`, ale `{slug}_nahled.webp`.
 * Vedle totiž pořád leží starý stock `{slug}_preview.jpg` a
 * `responsive_image_srcsets()` hledá varianty globem podle BASENAME
 * (`assets/{basename}-*.{avif,webp}`) — dva zdrojové soubory se stejným
 * basename by v buildu smíchaly varianty dvou různých obrázků a prohlížeč
 * by si mohl vybrat velikost z toho špatného. Ty staré `_preview.jpg` jsou
 * dnes osiřelé (v DB na produkci je NULL); smazat je může až rozhodnutí
 * boardu, tahle migrace na ně nesahá.
 *
 * Podmíněná v obou směrech: sáhne jen na řádek, kde stojí očekávaná hodnota
 * (NULL/prázdno na produkci po OND-268, nebo starý stock název na čerstvé
 * instalaci ze `database/sql/article_translations.sql`). Ruční úpravu
 * z Filamentu proto nepřepíše a druhý běh je no-op.
 */
return new class extends Migration
{
    /**
     * slug => [nový náhled, starý stock náhled ze SQL fixture].
     *
     * Klíčem je aktuální CS slug; `article_slugs` drží i ty historické,
     * takže se článek najde i po přejmenování z OND-266.
     */
    private const PREVIEWS = [
        'kolik-stoji-webove-stranky' => [
            'kolik-stoji-webove-stranky_nahled.webp',
            'kolik-stoji-webove-stranky_preview.jpg',
        ],
        'potrebuje-vase-firma-webovou-stranku' => [
            'potrebuje-vase-firma-webovou-stranku_nahled.webp',
            'potrebuje-vase-firma-webovou-stranku_preview.jpg',
        ],
        'jak-se-pripravit-na-novy-web' => [
            'jak-se-pripravit-na-novy-web_nahled.webp',
            'jak-definovat-pozadavky-na-vyvoj-webove-stranky_preview.jpg',
        ],
    ];

    public function up(): void
    {
        if (! DB::table('articles')->exists()) {
            return;
        }

        foreach (self::PREVIEWS as $slug => [$new, $old]) {
            $articleId = $this->articleId($slug);
            if (! $articleId) {
                continue;
            }

            // Všechny jazyky: náhled je screenshot, ne text.
            DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where(function ($q) use ($old) {
                    $q->whereNull('img_preview')
                      ->orWhere('img_preview', '')
                      ->orWhere('img_preview', $old);
                })
                ->update(['img_preview' => $new, 'updated_at' => now()]);
        }
    }

    /**
     * Zpět na stav po OND-268, tedy NULL — ne na stock ilustraci.
     * Ta šla pryč rozhodnutím boardu a vracet ji by bylo proti němu.
     */
    public function down(): void
    {
        if (! DB::table('articles')->exists()) {
            return;
        }

        foreach (self::PREVIEWS as $slug => [$new, $old]) {
            $articleId = $this->articleId($slug);
            if (! $articleId) {
                continue;
            }

            DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where('img_preview', $new)
                ->update(['img_preview' => null, 'updated_at' => now()]);
        }
    }

    private function articleId(string $slug): ?int
    {
        return DB::table('article_slugs')->where('slug', $slug)->value('article_id');
    }
};
