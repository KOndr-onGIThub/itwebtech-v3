<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-470: čtyři projekty pryč z webu — odpublikovat, NEMAZAT.
 *
 * Ondřej na OND-470 (29. 9. 20:10): „Některé projekty by možná stály
 * za unpublish. Ať tam zůstane to TOP a to co má smysl.“ Výběr udělal CEO
 * (29. 9. 20:42) podle návrhu z OND-471 §6: vypínají se projekty, které
 * nabídku nedokládají. Zůstane 17 projektů, jen weby a aplikace; kategorie
 * „Ostatní“ i volba „grafiku nebo článek“ ve větě nad přehledem zmizí samy.
 *
 * Mechanismus je stejný jako u OND-282: `published_at = NULL`.
 * `PortfolioProject::published()` je jediná brána na web (výpis, detail → 404,
 * „Další projekty“, homepage, /recenze, sitemap), projekt tak zmizí odevšud
 * naráz. Řádky, překlady, snímky, výsledky ani štítky se nemažou.
 *
 * VRATNÉ JEDNÍM KROKEM: `php artisan migrate:rollback --step=1` (down()),
 * nebo v adminu Portfolio → projekt → přepínač „Publikováno“.
 * `docs/portfolio-data.yaml` má u týchž slugů `published_at: null`, aby je
 * případný re-seed nevrátil.
 */
return new class extends Migration
{
    private const SLUGS = [
        'pitarena-cedule',     // Reklamní cedule PitArena
        'clanek-motorkari-cz', // Článek na Motorkáři.cz
        'elektro-srnak',       // Elektro Srnák (web bez hostingu)
        'yolk',                // YOLK (agenturní údržba cizích webů)
    ];

    public function up(): void
    {
        DB::table('portfolio_projects')
            ->whereIn('slug', self::SLUGS)
            ->update(['published_at' => null]);
    }

    /**
     * Vrácení publikace. Původní `published_at` byl čas seedu, stačí, že je
     * v minulosti (`published()` filtruje `published_at <= now()`).
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
