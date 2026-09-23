<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-268 (B4) — datová část nasazení vizuálních podkladů od Jacka (OND-263).
 *
 * Většina balíku jsou výměny OBSAHU souborů na stejných cestách (hero swapy,
 * opravené ořezy, koláže) — ty se v DB neprojeví vůbec, protože `path` zůstává.
 * Tahle migrace řeší jen to, co se bez zásahu do dat udělat nedá:
 *
 *   1. PŘIDAT  vanspedition `gallery-3.webp` — protějšek „po" k existujícímu
 *              „před" (`gallery-2`). Dvojice před/po je teprve sdělení.
 *   2. PŘIDAT  hcms `thumbnail-1.jpg` jako `type = 'thumbnail'`. Miniatura
 *              karty se dosud brala z první čtvercové karty = functions model
 *              (nečitelná šedozelená plocha). `portfolio_card_thumbnail()` dává
 *              `type = 'thumbnail'` přednost, šablona detailu ho vynechává.
 *   3. SMAZAT  josefopa `gallery-2` (po výměně hero doslovný duplikát) a
 *              `gallery-3` (mřížka klipartových maskotů s useknutými popisky).
 *   4. SMAZAT  excel-tools `gallery-2` (`address_data`) — interní návod, který
 *              nese adresářovou strukturu a názvy serverů klienta.
 *              Rozhodnutí Ondřeje 22. 9. 22:37, bez náhrady.
 *   5. SMAZAT  realitacky-v-akci `gallery-5` — marketingový vizuál rezervačního
 *              a pokladního systému pro fitness („FitnessGym", permanentky).
 *              K projektu nepatří a k žádnému jinému v portfoliu taky ne
 *              (ověřeno na všech 101 snímcích galerií, OND-260).
 *   6. OPRAVIT alt u snímků, kterým výměna obsahu změnila identitu, a u tří
 *              hero karet, jejichž alt tvrdil „domovská stránka", ačkoli
 *              ukazují vnitřní obrazovku.
 *
 * Soubory samotné mažeme z DB jen jako řádky — obrázky zůstávají v repu.
 * Jakmile je nic nereferencuje, Vite je do buildu nezahrne.
 *
 * Inertní na nenaplněné DB (tam projekty zakládá PortfolioSeeder z YAMLu,
 * který je se stejným výsledkem srovnaný). Idempotentní: druhý běh nic nepřidá
 * ani nesmaže podruhé.
 */
return new class extends Migration
{
    /** Nové snímky: [slug, path, type, sort_order, alt[locale]] */
    private const ADD = [
        [
            'slug'       => 'vanspedition',
            'path'       => 'projects/vanspedition/gallery-3.webp',
            'type'       => 'gallery',
            'sort_order' => 3,
            'alt'        => [
                'cs' => 'VAN spedition – dnešní podoba webu, protějšek snímku původní verze',
                'en' => 'VAN spedition – the current website, counterpart to the shot of the old version',
                'de' => 'VAN spedition – die heutige Website als Gegenstück zur alten Version',
            ],
        ],
        /*
         * Pojistka, ne nový obrázek. `portfolio_card_thumbnail()` bere
         * PRVNÍ skoro čtvercový snímek (poměr 0,85–1,2) a teprve pak hero.
         * `gallery-2` u VAN spedition je „před" — starý červený web — a tím,
         * že ho Jack doplnil na 1:1, by se z něj rázem stala miniatura karty
         * v /projekty i v „Další projekty". Karta by projekt prodávala webem,
         * který jsme nahradili. Ověřeno na vykreslené kartě, ne odhadem.
         *
         * Řádek `thumbnail` ukazuje na TÝŽ soubor jako hero, takže se nic
         * nového nenačítá a karta zůstane přesně taková, jaká je dnes.
         * Šablona detailu řádky `type = 'thumbnail'` vynechává, takže se
         * hero v galerii neobjeví dvakrát.
         */
        [
            'slug'       => 'vanspedition',
            'path'       => 'projects/vanspedition/hero-1.webp',
            'type'       => 'thumbnail',
            'sort_order' => 4,
            'alt'        => [
                'cs' => 'VAN spedition – domovská stránka mikrowebu logistické firmy',
                'en' => 'VAN spedition – homepage of the logistics company micro-site',
                'de' => 'VAN spedition – Startseite der Logistikunternehmens-Microsite',
            ],
        ],
        [
            'slug'       => 'hcms',
            'path'       => 'projects/hcms/thumbnail-1.jpg',
            'type'       => 'thumbnail',
            'sort_order' => 7,
            'alt'        => [
                'cs' => 'HCMS – obrazovka Hot Call Management System pro závod Toyota',
                'en' => 'HCMS – Hot Call Management System screen for the Toyota plant',
                'de' => 'HCMS – Bildschirm des Hot Call Management System für das Toyota-Werk',
            ],
        ],
    ];

    /** Snímky ke smazání: [slug, path] */
    private const REMOVE = [
        ['josefopa', 'projects/josefopa/gallery-2.webp'],
        ['josefopa', 'projects/josefopa/gallery-3.webp'],
        ['excel-tools', 'projects/excel-tools/gallery-2.jpg'],
        ['realitacky-v-akci', 'projects/realitacky-v-akci/gallery-5.webp'],
    ];

    /**
     * Opravy alt textů: [slug, path, alt[locale]].
     *
     * Dvě skupiny:
     *   a) slot, do kterého se přestěhovala homepage klienta — alt to má říct;
     *   b) čtvercové `hero-1` u cyklocentra, zubního provázku a VP Industry.
     *      Ta karta NIKDY neukazovala domovskou stránku (u zubního provázku
     *      je to dokonce administrace redakčního systému), alt to tvrdil.
     *      Výměnou lead snímku se ta nepravda stala zjevnou.
     */
    private const RETITLE = [
        [
            'cyklocentrum', 'projects/cyklocentrum/gallery-4.png',
            [
                'cs' => 'Cyklo Centrum – domovská stránka půjčovny, prodeje a servisu kol',
                'en' => 'Cyklo Centrum – homepage of the bike rental, sales and service shop',
                'de' => 'Cyklo Centrum – Startseite von Fahrradverleih, -verkauf und -service',
            ],
        ],
        [
            'cyklocentrum', 'projects/cyklocentrum/gallery-5.webp',
            [
                'cs' => 'Cyklo Centrum – ceník půjčovny kol na třech mobilních obrazovkách',
                'en' => 'Cyklo Centrum – bike rental price list on three mobile screens',
                'de' => 'Cyklo Centrum – Preisliste des Fahrradverleihs auf drei Mobilbildschirmen',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/gallery-1.png',
            [
                'cs' => 'Zubní Provázek – domovská stránka zubní ordinace',
                'en' => 'Zubní Provázek – homepage of the dental practice',
                'de' => 'Zubní Provázek – Startseite der Zahnarztpraxis',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/gallery-4.png',
            [
                'cs' => 'Zubní Provázek – článek na blogu ordinace',
                'en' => 'Zubní Provázek – an article on the practice blog',
                'de' => 'Zubní Provázek – ein Artikel im Blog der Praxis',
            ],
        ],
        [
            'zubni-provazek', 'projects/zubni-provazek/hero-1.png',
            [
                'cs' => 'Zubní Provázek – administrace webu: úprava ordinačních hodin',
                'en' => 'Zubní Provázek – site administration: editing the opening hours',
                'de' => 'Zubní Provázek – Website-Verwaltung: Bearbeitung der Sprechzeiten',
            ],
        ],
        [
            'nove-interiery', 'projects/nove-interiery/gallery-4.png',
            [
                'cs' => 'Nové interiéry – pozvánka do showroomu v Říčanech',
                'en' => 'Nové interiéry – invitation to the showroom in Říčany',
                'de' => 'Nové interiéry – Einladung in den Showroom in Říčany',
            ],
        ],
        [
            'vp-industry', 'projects/vp-industry/gallery-2.webp',
            [
                'cs' => 'VP Industry – domovská stránka webu pro průmyslové značení',
                'en' => 'VP Industry – homepage of the industrial marking website',
                'de' => 'VP Industry – Startseite der Website für industrielle Kennzeichnung',
            ],
        ],
        [
            'vp-industry', 'projects/vp-industry/hero-1.webp',
            [
                'cs' => 'VP Industry – katalog značících laserů na tabletu',
                'en' => 'VP Industry – catalogue of marking lasers on a tablet',
                'de' => 'VP Industry – Katalog der Markierlaser auf einem Tablet',
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $now = now();

        foreach (self::ADD as $row) {
            $projectId = $this->projectId($row['slug']);
            if (! $projectId) {
                continue;
            }

            // Pozor na dvojici (path, type), ne jen na path: u VAN spedition
            // ukazuje `thumbnail` na týž soubor jako `hero` a kontrola jen
            // podle cesty by řádek nikdy nezaložila.
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

        foreach (self::REMOVE as [$slug, $path]) {
            $this->deleteScreenshot($slug, $path, 'gallery');
        }

        foreach (self::RETITLE as [$slug, $path, $alts]) {
            $screenshotId = $this->screenshotId($slug, $path);
            if (! $screenshotId) {
                continue;
            }

            foreach ($alts as $locale => $alt) {
                DB::table('portfolio_project_screenshot_translations')
                    ->where('screenshot_id', $screenshotId)
                    ->where('locale', $locale)
                    ->update(['alt' => $alt, 'updated_at' => $now]);
            }
        }
    }

    /**
     * Vrací jen to, co se vrátit dá.
     *
     * Přidané snímky se smažou a alt texty se NEvrací — původní znění byla
     * buď generická („sekce 4"), nebo přímo nepravdivá, takže jejich obnova
     * nemá hodnotu a nese riziko, že přepíšeme novější ruční úpravu z adminu.
     * Smazané řádky (bod 3–5) se nevrací: obsahovaly to, co má z webu zmizet;
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
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    private function screenshotId(string $slug, string $path): ?int
    {
        $projectId = $this->projectId($slug);
        if (! $projectId) {
            return null;
        }

        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->where('path', $path)
            ->value('id');
    }

    /**
     * `$type` je povinný schválně: u VAN spedition sdílí `hero` a `thumbnail`
     * jednu cestu, takže mazání jen podle `path` by při rollbacku sebralo
     * i hero snímek.
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
