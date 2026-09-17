<?php

use Database\Seeders\PortfolioTagNamesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-223 (OND-197 nález 5.5) — srozumitelné názvy štítků ve všech jazycích.
 *
 * Navazuje na 2026_09_16_100000_rename_jargon_portfolio_tags.php (OND-198),
 * která opravila jen 3 štítky ze 70. Zbytek zůstal tak, jak ho vyrobil
 * `PortfolioSeeder::humanizeSlug()` — tedy bez diakritiky a jen česky:
 *
 *   /projekty/pitarena       → „Seo", „Dlouhodoba spoluprace"
 *   /de/projekte/pitarena    → „Rezervace", „Platby", „Seo" (české štítky na
 *                              německé stránce; `PortfolioTag::translation()`
 *                              padá zpátky na `cs`, protože EN/DE neexistují)
 *
 * Názvy žijí v DB (`portfolio_tag_translations`), ne v lang souborech, a
 * seeder se po prvním naplnění přeskakuje — migrace je proto jediná cesta,
 * jak je dostat na staging a produkci. Zdrojem je
 * `PortfolioTagNamesSeeder::NAMES`, aby čerstvý seed dal stejný výsledek.
 *
 * Slugy se nemění — jsou to stabilní identifikátory v pivotu.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new PortfolioTagNamesSeeder())->run();
    }

    /**
     * Vrací stav před migrací: EN/DE překlady, které tahle migrace založila,
     * se smažou a `cs` se vrátí na humanizovaný slug. Výjimkou jsou tři
     * štítky z OND-198 — ty měly svoje názvy už předtím, takže se obnovují
     * na hodnoty z migrace 2026_09_16_100000.
     */
    public function down(): void
    {
        $keepFromOnd198 = [
            'landing-page' => [
                'cs' => 'Samostatná stránka pro reklamu',
                'en' => 'Standalone page for advertising',
                'de' => 'Eigenständige Seite für Werbung',
            ],
            'kampane' => [
                'cs' => 'Placená reklama',
                'en' => 'Paid advertising',
                'de' => 'Bezahlte Werbung',
            ],
            'mobilni-prvni' => [
                'cs' => 'Navrženo nejdřív pro mobil',
                'en' => 'Designed for mobile first',
                'de' => 'Zuerst für Mobilgeräte entworfen',
            ],
        ];

        foreach (array_keys(PortfolioTagNamesSeeder::NAMES) as $slug) {
            $tagId = DB::table('portfolio_tags')->where('slug', $slug)->value('id');
            if (! $tagId) {
                continue;
            }

            if (isset($keepFromOnd198[$slug])) {
                foreach ($keepFromOnd198[$slug] as $locale => $name) {
                    DB::table('portfolio_tag_translations')->updateOrInsert(
                        ['tag_id' => $tagId, 'locale' => $locale],
                        ['name' => $name]
                    );
                }
                continue;
            }

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->whereIn('locale', ['en', 'de'])
                ->delete();

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->where('locale', 'cs')
                ->update(['name' => self::humanizeSlug($slug)]);
        }
    }

    /** Kopie PortfolioSeeder::humanizeSlug() — down() musí vrátit přesně ten stav. */
    private static function humanizeSlug(string $slug): string
    {
        return ucfirst(str_replace('-', ' ', $slug));
    }
};
