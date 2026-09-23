<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-294 — obrazové vady v galeriích případovek (doověření auditu C3).
 *
 * Datová část. Soubory se nemění vůbec — jsou to podklady klienta a sahat
 * do nich by znamenalo přemalovávat cizí screenshoty. Mění se jen to, co
 * o nich web tvrdí, a to, které z nich vůbec ukazuje:
 *
 *   1. SMAZAT  realitacky-v-akci `gallery-3` (bod 3) — brandingová nástěnka
 *              s desítkami mikro-textů. Na stránce nečitelná, působí jako
 *              interní pracovní dokument, ne jako ukázka práce.
 *   2. SMAZAT  vp-industry `gallery-1` (bod 6) — stránka dokumentu „Analýza
 *              konkurence" s legendou ŠPATNĚ/DOBRÉ. Textový podklad mezi
 *              ukázkami webu, navíc se jménem klienta a číslem analýzy
 *              v hlavičce. Obsah analýzy nese text případovky.
 *   3. PŘIDAT  zubni-provazek `gallery-2.png` jako `type = 'thumbnail'`
 *              (bod 7, nový nález). Miniatura karty se brala z prvního
 *              čtvercového snímku, a tím je po výměně hera (OND-263)
 *              `hero-1.png` — administrace ordinačních hodin. Karta je
 *              vidět na `/projekty` i v „Dalších projektech" po celém webu.
 *              Řádek ukazuje na TÝŽ soubor jako `gallery-2`, takže se nic
 *              nového nenačítá; šablona detailu řádky `thumbnail` vynechává
 *              (`detail-gallery.blade.php`), takže se v galerii neobjeví
 *              dvakrát. Stejný nástroj jako HCMS a VAN spedition (OND-268).
 *   4. POPISEK choccoboard `gallery-1` a `gallery-2` (bod 1) a excel-tools
 *              `gallery-5` (bod 2). V obou případech jde o anonymizaci
 *              klientových čísel — šedá plocha přes KPI tabulku, černé
 *              bloky přes grafy — která se bez vysvětlení čte jako
 *              nedonačtený nebo rozbitý obrázek. `figcaption` galerie ji
 *              pojmenuje. Obrázky zůstávají: u Excel Tools board
 *              u OND-268 rozhodl interní podklady nechat.
 *   5. OPRAVIT alt u snímků, kterých se to týká — „sekce 1" nikomu nic
 *              neříká a u dvou snímků alt ani neodpovídal obsahu.
 *
 * Bod 4 zadání (překlep „MOTOCROS AKADEMI" na ceduli PitArena) tu záměrně
 * není: je to podklad klienta a rozhoduje o něm Ondřej, ne migrace.
 * Bod 5 zadání (FRL Creator) řeší PR #154 k OND-292 stejným nástrojem
 * (`hero-1` → `thumbnail`), takže ho tahle migrace nedubluje.
 *
 * Soubory necháváme v repu i po smazání řádků — jakmile je nic
 * nereferencuje, Vite je do buildu nezahrne.
 *
 * Inertní na nenaplněné DB (tam projekty zakládá PortfolioSeeder z YAMLu,
 * který je se stejným výsledkem srovnaný). Idempotentní: druhý běh nic
 * nepřidá ani nesmaže podruhé.
 */
return new class extends Migration
{
    /** Snímky ke smazání: [slug, path] */
    private const REMOVE = [
        ['realitacky-v-akci', 'projects/realitacky-v-akci/gallery-3.webp'],
        ['vp-industry', 'projects/vp-industry/gallery-1.webp'],
    ];

    /** Nové řádky: [slug, path, type, sort_order, alt[locale]] */
    private const ADD = [
        [
            'slug'       => 'zubni-provazek',
            'path'       => 'projects/zubni-provazek/gallery-2.png',
            'type'       => 'thumbnail',
            'sort_order' => 6,
            'alt'        => [
                'cs' => 'Zubní Provázek – web ordinace na tabletu, logo a tým v ordinaci',
                'en' => 'Zubní Provázek – the practice website on a tablet, logo and the team',
                'de' => 'Zubní Provázek – Praxis-Website auf dem Tablet, Logo und das Team',
            ],
        ],
    ];

    /**
     * Popisky pod snímek: [slug, path, caption[locale]].
     *
     * `caption` je v datovém modelu od začátku a šablona i CSS ho umí
     * (`portfolio-detail-gallery__band figcaption`), jen ho zatím žádný
     * projekt nepoužíval.
     */
    private const CAPTION = [
        [
            'choccoboard', 'projects/choccoboard/gallery-1.webp',
            [
                'cs' => 'Konkrétní tržby jsou na přání klienta zakryté.',
                'en' => "The actual revenue figures are masked at the client's request.",
                'de' => 'Die konkreten Umsatzzahlen sind auf Wunsch des Kunden ausgeblendet.',
            ],
        ],
        [
            'choccoboard', 'projects/choccoboard/gallery-2.webp',
            [
                'cs' => 'Konkrétní tržby jsou na přání klienta zakryté.',
                'en' => "The actual revenue figures are masked at the client's request.",
                'de' => 'Die konkreten Umsatzzahlen sind auf Wunsch des Kunden ausgeblendet.',
            ],
        ],
        [
            'excel-tools', 'projects/excel-tools/gallery-5.jpg',
            [
                'cs' => 'Jména a čísla v grafech jsou začerněná — jde o interní provozní data klienta.',
                'en' => "Names and figures in the charts are redacted — this is the client's internal operational data.",
                'de' => 'Namen und Zahlen in den Diagrammen sind geschwärzt — es sind interne Betriebsdaten des Kunden.',
            ],
        ],
    ];

    /** Opravy alt textů: [slug, path, alt[locale]] */
    private const RETITLE = [
        [
            'choccoboard', 'projects/choccoboard/gallery-1.webp',
            [
                'cs' => 'Choccoboard – přehledová obrazovka s KPI tabulkou a TOP 10 položek na notebooku',
                'en' => 'Choccoboard – overview screen with the KPI table and top 10 items on a laptop',
                'de' => 'Choccoboard – Übersichtsbildschirm mit KPI-Tabelle und Top-10-Positionen auf dem Notebook',
            ],
        ],
        [
            'choccoboard', 'projects/choccoboard/gallery-2.webp',
            [
                'cs' => 'Choccoboard – tentýž přehled na tabletu a na mobilu',
                'en' => 'Choccoboard – the same overview on a tablet and on a phone',
                'de' => 'Choccoboard – dieselbe Übersicht auf Tablet und Smartphone',
            ],
        ],
        [
            'excel-tools', 'projects/excel-tools/gallery-5.jpg',
            [
                'cs' => 'Excel Tools (VBA) – denní logistický report s grafy Head count a Absence',
                'en' => 'Excel Tools (VBA) – daily logistics report with head count and absence charts',
                'de' => 'Excel Tools (VBA) – täglicher Logistikbericht mit Head-count- und Absenz-Diagrammen',
            ],
        ],
        [
            'realitacky-v-akci', 'projects/realitacky-v-akci/gallery-4.webp',
            [
                'cs' => 'Realiťačky v Akci – správa nabídek nemovitostí v administraci webu',
                'en' => 'Realiťačky v Akci – property listing management in the site administration',
                'de' => 'Realiťačky v Akci – Verwaltung der Immobilienangebote im Website-Backend',
            ],
        ],
        [
            'vp-industry', 'projects/vp-industry/gallery-4.webp',
            [
                'cs' => 'VP Industry – článek o typech značících laserů na tabletu',
                'en' => 'VP Industry – article on types of marking lasers on a tablet',
                'de' => 'VP Industry – Artikel über Typen von Markierlasern auf einem Tablet',
            ],
        ],
        [
            'vp-industry', 'projects/vp-industry/gallery-5.webp',
            [
                'cs' => 'VP Industry – články webu na mobilních obrazovkách',
                'en' => "VP Industry – the site's articles on mobile screens",
                'de' => 'VP Industry – Artikel der Website auf Mobilbildschirmen',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/gallery-2.png',
            [
                'cs' => 'Zubní Provázek – web ordinace na tabletu, logo a tým v ordinaci',
                'en' => 'Zubní Provázek – the practice website on a tablet, logo and the team',
                'de' => 'Zubní Provázek – Praxis-Website auf dem Tablet, Logo und das Team',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/gallery-5.png',
            [
                'cs' => 'Zubní Provázek – informace pro pacienty a ordinační doba na notebooku',
                'en' => 'Zubní Provázek – patient information and opening hours on a laptop',
                'de' => 'Zubní Provázek – Patienteninformationen und Sprechzeiten auf dem Notebook',
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $now = now();

        foreach (self::REMOVE as [$slug, $path]) {
            $this->deleteScreenshot($slug, $path, 'gallery');
        }

        foreach (self::ADD as $row) {
            $projectId = $this->projectId($row['slug']);
            if (! $projectId) {
                continue;
            }

            // Kontrola na dvojici (path, type), ne jen na path: `thumbnail`
            // schválně ukazuje na týž soubor jako existující `gallery` řádek.
            $exists = DB::table('portfolio_project_screenshots')
                ->where('project_id', $projectId)
                ->where('path', $row['path'])
                ->where('type', $row['type'])
                ->exists();
            if ($exists) {
                continue;
            }

            $screenshotId = DB::table('portfolio_project_screenshots')->insertGetId([
                'project_id' => $projectId,
                'path'       => $row['path'],
                'type'       => $row['type'],
                'sort_order' => $row['sort_order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($row['alt'] as $locale => $alt) {
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

        foreach (self::CAPTION as [$slug, $path, $captions]) {
            $this->updateTranslations($slug, $path, 'caption', $captions, $now);
        }

        foreach (self::RETITLE as [$slug, $path, $alts]) {
            $this->updateTranslations($slug, $path, 'alt', $alts, $now);
        }
    }

    /**
     * Vrací jen přidaný řádek a popisky.
     *
     * Alt texty se nevrací: původní znění byla buď generická („sekce 1"),
     * nebo přímo neodpovídala obsahu snímku, takže jejich obnova nemá
     * hodnotu a nese riziko, že přepíšeme novější ruční úpravu z adminu.
     * Smazané řádky se nevrací taky — obsahovaly to, co má z webu zmizet;
     * kdyby se měly vrátit, je to jeden klik ve Filamentu.
     */
    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::ADD as $row) {
            $this->deleteScreenshot($row['slug'], $row['path'], $row['type']);
        }

        foreach (self::CAPTION as [$slug, $path, $captions]) {
            $this->updateTranslations(
                $slug,
                $path,
                'caption',
                array_fill_keys(array_keys($captions), null),
                now()
            );
        }
    }

    private function updateTranslations(
        string $slug,
        string $path,
        string $column,
        array $values,
        $now
    ): void {
        $screenshotId = $this->screenshotId($slug, $path);
        if (! $screenshotId) {
            return;
        }

        foreach ($values as $locale => $value) {
            DB::table('portfolio_project_screenshot_translations')
                ->where('screenshot_id', $screenshotId)
                ->where('locale', $locale)
                ->update([$column => $value, 'updated_at' => $now]);
        }
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    /**
     * Pozor: u zubního provázku sdílí `gallery` a `thumbnail` jednu cestu.
     * Pro popisky a alty nás zajímá galerijní řádek, který je tam dřív —
     * `orderBy('id')` drží deterministický výběr i po přidání `thumbnail`.
     */
    private function screenshotId(string $slug, string $path): ?int
    {
        $projectId = $this->projectId($slug);
        if (! $projectId) {
            return null;
        }

        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->where('path', $path)
            ->where('type', '!=', 'thumbnail')
            ->orderBy('id')
            ->value('id');
    }

    /**
     * `$type` je povinný schválně: kde `gallery` a `thumbnail` sdílí cestu,
     * by mazání jen podle `path` sebralo oba řádky.
     */
    private function deleteScreenshot(string $slug, string $path, string $type): void
    {
        $projectId = $this->projectId($slug);
        if (! $projectId) {
            return;
        }

        $ids = DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->where('path', $path)
            ->where('type', $type)
            ->pluck('id');

        if ($ids->isEmpty()) {
            return;
        }

        DB::table('portfolio_project_screenshot_translations')
            ->whereIn('screenshot_id', $ids)
            ->delete();

        DB::table('portfolio_project_screenshots')->whereIn('id', $ids)->delete();
    }
};
