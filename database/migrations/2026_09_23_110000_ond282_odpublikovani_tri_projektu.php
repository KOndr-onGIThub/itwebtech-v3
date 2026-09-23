<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-282: tři projekty pryč z webu — odpublikovat, NEMAZAT.
 *
 * Rozhodnutí Ondřeje (22. 9. 23:55, OND-276): „Ani 1 z těchto projektů nebude
 * na webu. (…) klidně je dej jenom jako vypnout. Nemusíte nic mazat, stačí
 * že nebudou publikované."
 *
 * Mechanismus je ten, který už v aplikaci je: `portfolio_projects.published_at`.
 * NULL = neveřejné. `PortfolioProject::published()` je jediná brána, přes kterou
 * se projekty dostanou na web, a používá ji úplně všechno:
 *   - výpis /projekty ............ PageController::projects()
 *   - detail /projekty/{slug} .... PageController::project()  → 404
 *   - dlaždice „Další projekty" .. PageController::project()  (sameCategory + extras)
 *   - případovky na homepage ..... PageController::home()
 *   - sitemap.xml ................ SitemapGenerator::collectProjectUrls()
 *   - strukturovaná data ......... jsou součástí šablony detailu, ta se nevykreslí
 * Odpublikováním tedy projekt zmizí odevšud naráz, žádné další místo nezbývá.
 *
 * Řádky v `portfolio_projects`, překlady, screenshoty, outcomes ani tagy se
 * nemažou — zůstávají v DB. Obrázky zůstávají v repu.
 *
 * VRATNÉ JEDNÍM KROKEM, dvěma způsoby:
 *   a) `php artisan migrate:rollback --step=1` (down() níže), nebo
 *   b) v adminu Filament: Portfolio → projekt → přepínač „Publikováno" zapnout.
 * Obojí nastaví `published_at` zpátky a projekt se okamžitě vrátí všude.
 */
return new class extends Migration
{
    /**
     * Slugy k odpublikování.
     *
     * POZOR: `realitacky-v-akci` (web pro Realiťačky) v tomhle seznamu záměrně
     * NENÍ — Ondřejův pokyn se týká případovky o návrhu loga (`logo-realitacky`).
     */
    private const SLUGS = [
        'video-pitbike-akademie', // Video PitBike akademie
        'animace-delejme',        // Animace Dělejme
        'logo-realitacky',        // Logo Realiťačky
    ];

    public function up(): void
    {
        DB::table('portfolio_projects')
            ->whereIn('slug', self::SLUGS)
            ->update(['published_at' => null]);
    }

    /**
     * Vrácení publikace. Původní `published_at` byl čas seedu, takže se
     * nevrací na konkrétní historické datum — stačí, že je v minulosti
     * (`published()` filtruje `published_at <= now()`).
     */
    public function down(): void
    {
        $rows = DB::table('portfolio_projects')
            ->whereIn('slug', self::SLUGS)
            ->whereNull('published_at')
            ->get(['id', 'created_at']);

        foreach ($rows as $row) {
            DB::table('portfolio_projects')
                ->where('id', $row->id)
                ->update(['published_at' => $row->created_at ?? now()]);
        }
    }
};
