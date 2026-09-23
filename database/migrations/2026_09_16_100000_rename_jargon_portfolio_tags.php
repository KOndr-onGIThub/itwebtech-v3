<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-198 (nález 5.5) — štítky u projektů byly generované ze slugu
 * (PortfolioSeeder::humanizeSlug), takže se klientovi zobrazovaly jako
 * technický žargon bez diakritiky: „Kampane", „Mobilni prvni",
 * „Landing page". Názvy štítků žijí v DB (portfolio_tag_translations),
 * ne v lang souborech — seeder je po prvním naplnění přeskakuje.
 * Tato migrace je proto jediná cesta, jak změnu dostat na produkci.
 *
 * Slugy zůstávají beze změny (jsou to stabilní identifikátory v pivotu),
 * mění se jen zobrazovaný název.
 */
return new class extends Migration
{
    /** slug => [locale => nový název] */
    private const RENAMES = [
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

    public function up(): void
    {
        foreach (self::RENAMES as $slug => $names) {
            $tagId = DB::table('portfolio_tags')->where('slug', $slug)->value('id');
            if (! $tagId) {
                continue;
            }

            foreach ($names as $locale => $name) {
                DB::table('portfolio_tag_translations')->updateOrInsert(
                    ['tag_id' => $tagId, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }
    }

    public function down(): void
    {
        // Obnoví původní humanizovaný název ze slugu (ucfirst + pomlčky na mezery),
        // tj. přesně to, co generoval PortfolioSeeder::humanizeSlug.
        foreach (array_keys(self::RENAMES) as $slug) {
            $tagId = DB::table('portfolio_tags')->where('slug', $slug)->value('id');
            if (! $tagId) {
                continue;
            }

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->where('locale', 'cs')
                ->update(['name' => ucfirst(str_replace('-', ' ', $slug))]);

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->whereIn('locale', ['en', 'de'])
                ->delete();
        }
    }
};
