<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-363 — sjednocení názvu klienta na „Cyklocentrum Březí" v alt textech
 * galerie projektu `cyklocentrum`.
 *
 * Proč zvlášť, když je oprava i v YAMLu:
 * `EnsurePortfolioSeededSeeder` (OND-352) seeduje z YAMLu jen tehdy, je-li
 * `portfolio_projects` prázdná. Jakmile projekty existují, je navždy no-op —
 * a `2026_09_23_120000_ond268_…` má na produkci záznam v `migrations`, takže
 * se taky znovu nespustí. Bez téhle migrace by se oprava do už naplněné DB
 * nedostala vůbec.
 *
 * Funguje na obě strany:
 *   - prázdná DB  → tady no-op, data dorovná seeder z opraveného YAMLu;
 *   - naplněná DB → přepíše řádky rovnou.
 *
 * Na co matchuje: `portfolio_project_screenshots.path`. Cesty
 * `projects/cyklocentrum/gallery-4.png` a `gallery-5.webp` vznikají stejně
 * v obou větvích — `PortfolioSeeder` je odvozuje z pořadí a přípony URL
 * (`{type}-{n}.{ext}`), migrace z 23. 9. je má napsané natvrdo. Vzdálené
 * `framerusercontent.com` URL z YAMLu se do sloupce `path` nikdy nedostanou.
 *
 * Pojistka `PREFIX_FIX` dorovná i řádky, které by path-match minul (ruční
 * úprava cesty ve Filamentu, jiná přípona po překonvertování snímku).
 *
 * Idempotentní: druhý běh přepíše tytéž hodnoty na tytéž hodnoty.
 */
return new class extends Migration
{
    private const SLUG = 'cyklocentrum';

    private const OLD_NAME = 'Cyklo Centrum';

    private const NEW_NAME = 'Cyklocentrum Březí';

    /**
     * Cílové alt texty: [path, alt[locale]].
     *
     * Mapování ověřeno pohledem na LOKÁLNÍ soubory v `resources/img/`, ne
     * podle `url` v YAMLu. OND-268 obsah obou souborů vyměnil, framer URL
     * zůstaly u původních snímků:
     *   - `gallery-4.png`  = monitor + tablet v rukou + mobil, úvodní stránka
     *                        „Půjčovna, prodej a servis kol v srdci Pálavy"
     *                        a stránka půjčovny na tabletu,
     *   - `gallery-5.webp` = tři mobily, ceník kategorií (850–1300 Kč/den),
     *                        výlety po Pálavě a filtrování kol v katalogu.
     */
    private const ALTS = [
        [
            'projects/cyklocentrum/gallery-4.png',
            [
                'cs' => 'Cyklocentrum Březí – domovská stránka a půjčovna kol na monitoru, tabletu a mobilu',
                'en' => 'Cyklocentrum Březí – homepage and bike rental page on a monitor, tablet and phone',
                'de' => 'Cyklocentrum Březí – Startseite und Fahrradverleih auf Monitor, Tablet und Smartphone',
            ],
        ],
        [
            'projects/cyklocentrum/gallery-5.webp',
            [
                'cs' => 'Cyklocentrum Březí – ceník půjčovny, výlety po Pálavě a filtrování kol na třech mobilních obrazovkách',
                'en' => 'Cyklocentrum Březí – rental price list, Pálava trips and bike filtering on three mobile screens',
                'de' => 'Cyklocentrum Březí – Verleih-Preisliste, Pálava-Touren und Rad-Filter auf drei Mobilbildschirmen',
            ],
        ],
    ];

    public function up(): void
    {
        $projectId = DB::table('portfolio_projects')->where('slug', self::SLUG)->value('id');
        if (! $projectId) {
            $this->report('projekt `'.self::SLUG.'` v DB není, migrace je no-op');

            return;
        }

        $now = now();
        $byPath = 0;

        foreach (self::ALTS as [$path, $alts]) {
            $screenshotId = DB::table('portfolio_project_screenshots')
                ->where('project_id', $projectId)
                ->where('path', $path)
                ->value('id');

            if (! $screenshotId) {
                $this->report("snímek `{$path}` nenalezen, přeskakuji");

                continue;
            }

            foreach ($alts as $locale => $alt) {
                $byPath += DB::table('portfolio_project_screenshot_translations')
                    ->where('screenshot_id', $screenshotId)
                    ->where('locale', $locale)
                    ->where('alt', '!=', $alt)
                    ->update(['alt' => $alt, 'updated_at' => $now]);
            }
        }

        // Pojistka: cokoli pod tímhle projektem, co pořád začíná starým názvem.
        $screenshotIds = DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->pluck('id');

        $byPrefix = 0;
        $leftovers = DB::table('portfolio_project_screenshot_translations')
            ->whereIn('screenshot_id', $screenshotIds)
            ->where('alt', 'like', self::OLD_NAME.'%')
            ->get(['id', 'alt']);

        foreach ($leftovers as $row) {
            $byPrefix += DB::table('portfolio_project_screenshot_translations')
                ->where('id', $row->id)
                ->update([
                    'alt'        => self::NEW_NAME.substr($row->alt, strlen(self::OLD_NAME)),
                    'updated_at' => $now,
                ]);
        }

        $this->report("přepsáno podle cesty: {$byPath}, dorovnáno podle prefixu: {$byPrefix}");
    }

    /**
     * Zpátky se nevrací nic. Původní znění nesla chybný název klienta
     * („Cyklo Centrum" není jméno firmy, ARES/IČO 23969440 zná
     * `CYKLOCENTRUM BŘEZÍ s.r.o.`), takže jejich obnova nemá hodnotu a nese
     * riziko, že přepíšeme novější ruční úpravu z Filamentu.
     */
    public function down(): void
    {
        // záměrně prázdné
    }

    private function report(string $message): void
    {
        echo "  [ond363] {$message}\n";
    }
};
