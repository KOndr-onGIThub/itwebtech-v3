<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-209: naplnění DE a EN slugů pro projekty, jejichž český slug je popis,
 * ne značka.
 *
 * Pravidlo (drženo i pro budoucí projekty):
 *   slug kopíruje lokalizovaný *titulek*.
 *   - Titulek se v DE/EN nepřekládá, protože je to vlastní jméno
 *     (PitArena, BARANA, Střechy Zajíc, Autokemp Veselka, Elektro Srnák…)
 *     → slug zůstává jazyk-neutrální, žádný řádek tady.
 *   - Titulek přeložený JE a překlad se promítá do slugu (Animace upoutání
 *     pozornosti → Aufmerksamkeits-Animation) → slug se lokalizuje.
 *
 * Tím pádem se mění právě 4 z 24 projektů. U zbytku by lokalizace slugu
 * znamenala přeložit jméno firmy, což je pro SEO i pro klienta špatně.
 *
 * Staré DE/EN adresy (= český slug) zůstávají funkční: PageController::project()
 * je najde přes jazyk-neutrální slug a udělá 301 na kanonickou adresu locale.
 *
 * Reverzibilní: down() slugy zase vynuluje. Ručně upravené slugy (Filament)
 * se nepřepisují — update je podmíněný očekávanou hodnotou.
 */
return new class extends Migration
{
    /**
     * slug projektu => [locale => lokalizovaný slug]
     */
    private const LOCALIZED_SLUGS = [
        // „PitAréna — e-shop" / „PitArena — Onlineshop" / „PitArena — online shop"
        'pitarena-eshop' => [
            'de' => 'pitarena-onlineshop',
            'en' => 'pitarena-online-shop',
        ],
        // „Článek na Motorkáři.cz" / „Artikel auf Motorkáři.cz" / „Article on Motorkáři.cz"
        'clanek-motorkari-cz' => [
            'de' => 'artikel-motorkari-cz',
            'en' => 'article-motorkari-cz',
        ],
        // „Reklamní cedule PitArena" / „PitArena Werbeschild" / „PitArena outdoor sign"
        'pitarena-cedule' => [
            'de' => 'pitarena-werbeschild',
            'en' => 'pitarena-outdoor-sign',
        ],
        // „Animace upoutání pozornosti" / „Aufmerksamkeits-Animation" / „Attention-grabbing animation"
        'animace-delejme' => [
            'de' => 'aufmerksamkeits-animation',
            'en' => 'attention-grabbing-animation',
        ],
    ];

    public function up(): void
    {
        $this->apply('to');
    }

    public function down(): void
    {
        $this->apply('from');
    }

    /**
     * @param  'to'|'from'  $direction  'to' = nastav lokalizovaný slug, 'from' = zpět na NULL
     */
    private function apply(string $direction): void
    {
        // Prázdná DB (RefreshDatabase v testech, čerstvá instalace) → no-op.
        // Data pro testy dodává PortfolioSeeder z docs/portfolio-data.yaml.
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::LOCALIZED_SLUGS as $projectSlug => $locales) {
            $projectId = DB::table('portfolio_projects')->where('slug', $projectSlug)->value('id');
            if (! $projectId) {
                continue;
            }

            foreach ($locales as $locale => $localizedSlug) {
                $expected = $direction === 'to' ? null : $localizedSlug;
                $new      = $direction === 'to' ? $localizedSlug : null;

                DB::table('portfolio_project_translations')
                    ->where('project_id', $projectId)
                    ->where('locale', $locale)
                    ->when(
                        $expected === null,
                        fn ($q) => $q->whereNull('slug'),
                        fn ($q) => $q->where('slug', $expected),
                    )
                    ->update(['slug' => $new, 'updated_at' => now()]);
            }
        }
    }
};
