<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-468 — B-07, varianta V7 A (Ondřej 29. 9. 2026 na OND-441: „vyřadit“).
 *
 * Z galerií detailu (a tím i z lightboxu, cs/en/de) mizí 10 slabých snímků
 * u 6 projektů. Každý byl před vyřazením prohlédnut, jestli je to opravdu
 * popsaný snímek:
 *
 *   cyklocentrum   gallery-1, gallery-2  obecné bloky „Proč právě my?“ a „Naše hodnoty“
 *   excel-tools    gallery-4             nečitelná kontrolní tabulka linek
 *   hcms           gallery-1             táž obrazovka jako hlavní snímek
 *   hcms           gallery-4             nečitelné kartičky funkčních požadavků
 *   kemp-veselka   gallery-4, gallery-5  málo vypovídající mockupy
 *   picker         gallery-2, gallery-4  výřezy obou telefonů z `gallery-1`
 *   zubni-provazek gallery-3             druhý článek z blogu vedle `gallery-4`
 *
 * Mimo migraci: `barana/gallery-5` a `excel-tools/gallery-3` jsou z galerie
 * pryč už od OND-449 (PR #227). `clanek-motorkari-cz/gallery-1` zůstává,
 * projekt by klesl pod 3 snímky.
 *
 * Hlavní snímek (lead, zároveň obrázek karty podle B-06) se nemění
 * u žádného projektu: `hero` se nevyřazuje a u Cyklocentra a Zubního
 * Provázku, kde je lead široký galerijní snímek, zůstává `gallery-4`
 * a `gallery-1`.
 *
 * Mažou se jen řádky v DB, soubory v `resources/img/projects` zůstávají,
 * takže je `down()` umí vrátit. Mazání sedí na dvojici (cesta, `gallery`):
 * řádek, kterému někdo ve Filamentu vyměnil obrázek, se nesmaže, a řádek
 * `thumbnail` na téže cestě taky ne. `down()` vrací jen řádky, které
 * chybí. Druhý běh obou směrů je no-op.
 *
 * Na prázdné DB je migrace inertní. Projekty tam zakládá PortfolioSeeder
 * z `docs/portfolio-data.yaml`, ze kterého jsou tytéž snímky vyřazené.
 */
return new class extends Migration
{
    /** [slug, path, sort_order, alt[locale]] — alt podle produkce 29. 9. 2026, caption je u všech prázdný. */
    private const REMOVE = [
        [
            'cyklocentrum', 'projects/cyklocentrum/gallery-1.png', 1,
            [
                'cs' => '„Proč právě my?“ na mobilu na šířku: tři důvody, proč přijet právě sem',
                'en' => '“Why us?” on a phone in landscape: three reasons to come here',
                'de' => '„Warum gerade wir?“ auf dem Handy im Querformat: drei Gründe, genau hierher zu kommen',
            ],
        ],
        [
            'cyklocentrum', 'projects/cyklocentrum/gallery-2.png', 2,
            [
                'cs' => '„Naše hodnoty“ na notebooku: fotka z vyjížďky a tři hodnoty týmu',
                'en' => '“Our values” on a laptop: a photo from a ride and the team’s three values',
                'de' => '„Unsere Werte“ auf dem Notebook: Foto von einer Radtour und drei Werte des Teams',
            ],
        ],
        [
            'excel-tools', 'projects/excel-tools/gallery-4.jpg', 3,
            [
                'cs' => 'Kontrolní přehled linek, který odhalí chybějící kombinaci kurzu a druhu',
                'en' => 'Line-by-line check that reveals a missing route and type combination',
                'de' => 'Prüfübersicht je Linie, die eine fehlende Kombination aus Route und Art aufdeckt',
            ],
        ],
        [
            'hcms', 'projects/hcms/gallery-1.jpg', 1,
            [
                'cs' => 'Obrazovka pro zadání volání v plné velikosti: díl, dodavatel, zásoby a dnešní historie',
                'en' => 'The call entry screen at full size: part, supplier, stock and today’s history',
                'de' => 'Die Rufmaske in voller Größe: Teil, Lieferant, Bestände und heutiger Verlauf',
            ],
        ],
        [
            'hcms', 'projects/hcms/gallery-4.jpg', 4,
            [
                'cs' => 'Soupis funkčních požadavků na systém',
                'en' => 'List of the system’s functional requirements',
                'de' => 'Liste der funktionalen Anforderungen an das System',
            ],
        ],
        [
            'kemp-veselka', 'projects/kemp-veselka/gallery-4.webp', 4,
            [
                'cs' => 'Rezervace po telefonu: sekce „Pro rezervaci volejte“ s číslem jako tlačítkem',
                'en' => 'Booking by phone: the “Call to book” section with the number as a button',
                'de' => 'Buchung per Telefon: der Bereich „Zum Buchen anrufen“ mit der Nummer als Button',
            ],
        ],
        [
            'kemp-veselka', 'projects/kemp-veselka/gallery-5.webp', 5,
            [
                'cs' => 'Nabídka ubytování zabalená jako dárek: chatky, karavany a stanování s tlačítky Rezervovat a Ceník',
                'en' => 'The accommodation offer wrapped as a gift: cabins, caravans and tents with Book and Price list buttons',
                'de' => 'Das Unterkunftsangebot als Geschenk verpackt: Hütten, Wohnmobile und Zelte mit den Buttons Buchen und Preisliste',
            ],
        ],
        [
            'picker', 'projects/picker/gallery-2.jpg', 2,
            [
                'cs' => 'Obrazovka skladníka na výšku: díl, regál, počet kusů a odpočet',
                'en' => 'The warehouse operator screen in portrait: part, rack, quantity and countdown',
                'de' => 'Bildschirm für Lagermitarbeiter im Hochformat: Teil, Regal, Stückzahl und Countdown',
            ],
        ],
        [
            'picker', 'projects/picker/gallery-4.jpg', 4,
            [
                'cs' => 'Hlášení problému: díl nenalezen, poškozený díl nebo box, vlastní zpráva',
                'en' => 'Reporting a problem: part not found, damaged part or box, custom message',
                'de' => 'Problem melden: Teil nicht gefunden, Teil oder Box beschädigt, eigene Nachricht',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/gallery-3.png', 3,
            [
                'cs' => 'Článek „Rady rodičům“ na mobilu: kdy začít chodit s dítětem k zubaři',
                'en' => 'The “Advice for parents” article on a phone: when to start taking a child to the dentist',
                'de' => 'Der Artikel „Tipps für Eltern“ auf dem Handy: ab wann man mit dem Kind zum Zahnarzt geht',
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::REMOVE as [$slug, $path]) {
            $projectId = $this->projectId($slug);
            if (! $projectId) {
                continue;
            }

            $ids = DB::table('portfolio_project_screenshots')
                ->where('project_id', $projectId)
                ->where('path', $path)
                ->where('type', 'gallery')
                ->pluck('id');

            if ($ids->isEmpty()) {
                continue;
            }

            DB::table('portfolio_project_screenshot_translations')->whereIn('screenshot_id', $ids)->delete();
            DB::table('portfolio_project_screenshots')->whereIn('id', $ids)->delete();
        }
    }

    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $now = now();

        foreach (self::REMOVE as [$slug, $path, $sortOrder, $alts]) {
            $projectId = $this->projectId($slug);
            if (! $projectId) {
                continue;
            }

            $exists = DB::table('portfolio_project_screenshots')
                ->where('project_id', $projectId)
                ->where('path', $path)
                ->where('type', 'gallery')
                ->exists();
            if ($exists) {
                continue;
            }

            $screenshotId = DB::table('portfolio_project_screenshots')->insertGetId([
                'project_id' => $projectId,
                'path'       => $path,
                'type'       => 'gallery',
                'sort_order' => $sortOrder,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($alts as $locale => $alt) {
                DB::table('portfolio_project_screenshot_translations')->insert([
                    'screenshot_id' => $screenshotId,
                    'locale'        => $locale,
                    'alt'           => $alt,
                    'caption'       => null,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }
};
