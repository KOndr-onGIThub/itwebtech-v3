<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-223 (OND-197 nález 5.5) — názvy štítků u projektů.
 *
 * `PortfolioSeeder` umí název štítku jen humanizovat ze slugu a jen v češtině
 * (`humanizeSlug()`), takže na detailu projektu končily popisky bez diakritiky
 * („Lokalni seo", „Dlouhodoba spoluprace", „Vicejazycny") nebo v žargonu
 * („Copywriting", „Branding", „Landing page"). EN a DE překlady nevznikly
 * vůbec — `PortfolioTag::translation()` padá zpátky na `cs`, takže na
 * `/en/projects/*` a `/de/projekte/*` svítily české štítky.
 *
 * Tady jsou názvy pro všech 70 slugů ve všech třech jazycích na jednom místě.
 * Volá se ze dvou míst:
 *  - `PortfolioSeeder` (čerstvá DB, hned po vytvoření štítků),
 *  - migrace `2026_09_17_300000_ond223_nazvy_stitku` (staging a produkce, kde
 *    se seeder po prvním naplnění přeskakuje).
 *
 * Slugy se nikde nemění — jsou to stabilní identifikátory v pivotu.
 */
class PortfolioTagNamesSeeder extends Seeder
{
    use WithoutModelEvents;

    /** slug => [locale => název] */
    public const NAMES = [
        // --- typ projektu -------------------------------------------------
        'web' => [
            'cs' => 'Web',
            'en' => 'Website',
            'de' => 'Website',
        ],
        'mikroweb' => [
            'cs' => 'Malý web o jedné stránce',
            'en' => 'Small one-page site',
            'de' => 'Kleine Ein-Seiten-Website',
        ],
        'single-page' => [
            'cs' => 'Všechno na jedné stránce',
            'en' => 'Everything on one page',
            'de' => 'Alles auf einer Seite',
        ],
        'landing-page' => [
            'cs' => 'Samostatná stránka pro reklamu',
            'en' => 'Standalone page for advertising',
            'de' => 'Eigenständige Seite für Werbung',
        ],
        'aplikace' => [
            'cs' => 'Aplikace na míru',
            'en' => 'Custom application',
            'de' => 'Individuelle Anwendung',
        ],
        'web-app' => [
            'cs' => 'Aplikace v prohlížeči',
            'en' => 'Application in the browser',
            'de' => 'Anwendung im Browser',
        ],
        'redesign' => [
            'cs' => 'Předělání starého webu',
            'en' => 'Rebuild of an old site',
            'de' => 'Neubau einer alten Website',
        ],
        'vlastni-projekt' => [
            'cs' => 'Můj vlastní projekt',
            'en' => 'My own project',
            'de' => 'Mein eigenes Projekt',
        ],
        'ukazka' => [
            'cs' => 'Ukázková práce',
            'en' => 'Showcase piece',
            'de' => 'Schaustück',
        ],

        // --- co web nebo aplikace umí -------------------------------------
        'rezervace' => [
            'cs' => 'Rezervace',
            'en' => 'Booking',
            'de' => 'Buchung',
        ],
        'platby' => [
            'cs' => 'Online platby',
            'en' => 'Online payments',
            'de' => 'Online-Zahlungen',
        ],
        'admin' => [
            'cs' => 'Správa obsahu klientem',
            'en' => 'Content managed by the client',
            'de' => 'Inhaltspflege durch den Kunden',
        ],
        'blog' => [
            'cs' => 'Blog',
            'en' => 'Blog',
            'de' => 'Blog',
        ],
        'galerie' => [
            'cs' => 'Fotogalerie',
            'en' => 'Photo gallery',
            'de' => 'Fotogalerie',
        ],
        'vicejazycny' => [
            'cs' => 'Web ve více jazycích',
            'en' => 'Site in several languages',
            'de' => 'Website in mehreren Sprachen',
        ],
        'e-mail' => [
            'cs' => 'Firemní e-maily',
            'en' => 'Company email',
            'de' => 'Firmen-E-Mail',
        ],
        'automatizace' => [
            'cs' => 'Ruční práce nahrazená automatem',
            'en' => 'Manual work replaced by automation',
            'de' => 'Handarbeit durch Automatik ersetzt',
        ],
        'generator' => [
            'cs' => 'Generování dokumentů',
            'en' => 'Document generation',
            'de' => 'Dokumentenerstellung',
        ],
        'pdf' => [
            'cs' => 'Výstup do PDF',
            'en' => 'PDF output',
            'de' => 'Ausgabe als PDF',
        ],
        'dashboard' => [
            'cs' => 'Přehled čísel na jedné obrazovce',
            'en' => 'All the numbers on one screen',
            'de' => 'Alle Zahlen auf einem Bildschirm',
        ],
        'bi' => [
            'cs' => 'Vyhodnocování firemních dat',
            'en' => 'Making sense of company data',
            'de' => 'Auswertung von Unternehmensdaten',
        ],
        'kpi' => [
            'cs' => 'Sledování klíčových čísel',
            'en' => 'Tracking key numbers',
            'de' => 'Verfolgung wichtiger Kennzahlen',
        ],
        'manazerske-rozhodovani' => [
            'cs' => 'Podklad pro rozhodování vedení',
            'en' => 'Basis for management decisions',
            'de' => 'Grundlage für Entscheidungen der Leitung',
        ],
        'barcode' => [
            'cs' => 'Čtení čárových kódů',
            'en' => 'Barcode scanning',
            'de' => 'Barcode-Scannen',
        ],
        'sklad' => [
            'cs' => 'Sklad',
            'en' => 'Warehouse',
            'de' => 'Lager',
        ],
        'vychystavani' => [
            'cs' => 'Vychystávání objednávek',
            'en' => 'Order picking',
            'de' => 'Kommissionierung',
        ],
        'tablet' => [
            'cs' => 'Ovládání na tabletu',
            'en' => 'Operated on a tablet',
            'de' => 'Bedienung auf dem Tablet',
        ],
        'mobilni-prvni' => [
            'cs' => 'Navrženo nejdřív pro mobil',
            'en' => 'Designed for mobile first',
            'de' => 'Zuerst für Mobilgeräte entworfen',
        ],

        // --- návrh a značka -----------------------------------------------
        'branding' => [
            'cs' => 'Značka',
            'en' => 'Brand',
            'de' => 'Marke',
        ],
        'identita' => [
            'cs' => 'Jednotný vzhled značky',
            'en' => 'Consistent brand look',
            'de' => 'Einheitliches Erscheinungsbild',
        ],
        'logo' => [
            'cs' => 'Logo',
            'en' => 'Logo',
            'de' => 'Logo',
        ],
        'vektor' => [
            'cs' => 'Logo v tiskové kvalitě',
            'en' => 'Logo in print quality',
            'de' => 'Logo in Druckqualität',
        ],
        'design' => [
            'cs' => 'Grafický návrh',
            'en' => 'Graphic design',
            'de' => 'Grafikdesign',
        ],
        'ux' => [
            'cs' => 'Návrh, ve kterém se člověk vyzná',
            'en' => 'A layout people can find their way in',
            'de' => 'Aufbau, in dem man sich zurechtfindet',
        ],
        'premium' => [
            'cs' => 'Prémiové provedení',
            'en' => 'Premium finish',
            'de' => 'Premium-Ausführung',
        ],
        'animace' => [
            'cs' => 'Animace',
            'en' => 'Animation',
            'de' => 'Animation',
        ],
        'video' => [
            'cs' => 'Video',
            'en' => 'Video',
            'de' => 'Video',
        ],

        // --- tisk a polygrafie ---------------------------------------------
        'tisk' => [
            'cs' => 'Podklady pro tisk',
            'en' => 'Print-ready artwork',
            'de' => 'Druckvorlagen',
        ],
        'velkoformat' => [
            'cs' => 'Velkoformátový tisk',
            'en' => 'Large-format print',
            'de' => 'Großformatdruck',
        ],
        'signage' => [
            'cs' => 'Cedule a orientační značení',
            'en' => 'Signs and wayfinding',
            'de' => 'Schilder und Leitsystem',
        ],
        'vizitky' => [
            'cs' => 'Vizitky',
            'en' => 'Business cards',
            'de' => 'Visitenkarten',
        ],
        'vizitka' => [
            'cs' => 'Web jako vizitka firmy',
            'en' => 'Site as the company’s calling card',
            'de' => 'Website als Visitenkarte der Firma',
        ],

        // --- texty, obsah a propagace --------------------------------------
        'copywriting' => [
            'cs' => 'Texty na web',
            'en' => 'Website copy',
            'de' => 'Website-Texte',
        ],
        'obsah' => [
            'cs' => 'Obsah webu',
            'en' => 'Site content',
            'de' => 'Website-Inhalte',
        ],
        'content-marketing' => [
            'cs' => 'Články, které přivádějí zákazníky',
            'en' => 'Articles that bring customers in',
            'de' => 'Artikel, die Kunden bringen',
        ],
        'pr' => [
            'cs' => 'Článek v médiu',
            'en' => 'Article in the media',
            'de' => 'Artikel in den Medien',
        ],
        'propagace' => [
            'cs' => 'Propagace',
            'en' => 'Promotion',
            'de' => 'Werbung',
        ],
        'kampane' => [
            'cs' => 'Placená reklama',
            'en' => 'Paid advertising',
            'de' => 'Bezahlte Werbung',
        ],
        'social-media' => [
            'cs' => 'Obsah na sociální sítě',
            'en' => 'Content for social media',
            'de' => 'Inhalte für soziale Netzwerke',
        ],
        'seo' => [
            'cs' => 'Viditelnost ve vyhledávačích',
            'en' => 'Search visibility',
            'de' => 'Sichtbarkeit in Suchmaschinen',
        ],
        'lokalni-seo' => [
            'cs' => 'Vyhledávání v okolí',
            'en' => 'Local search',
            'de' => 'Lokale Suche',
        ],
        'sprava-produktu' => [
            'cs' => 'Správa nabídky produktů',
            'en' => 'Managing the product range',
            'de' => 'Pflege des Produktangebots',
        ],
        'showroom' => [
            'cs' => 'Showroom',
            'en' => 'Showroom',
            'de' => 'Showroom',
        ],

        // --- obory a typ zákazníka ------------------------------------------
        'b2b' => [
            'cs' => 'Prodej firmám',
            'en' => 'Selling to businesses',
            'de' => 'Verkauf an Unternehmen',
        ],
        'lokalni-podnikani' => [
            'cs' => 'Podnikání v místě',
            'en' => 'Local business',
            'de' => 'Lokales Unternehmen',
        ],
        'prumysl' => [
            'cs' => 'Průmysl',
            'en' => 'Industry',
            'de' => 'Industrie',
        ],
        'vyroba' => [
            'cs' => 'Výroba',
            'en' => 'Manufacturing',
            'de' => 'Fertigung',
        ],
        'logistika' => [
            'cs' => 'Logistika',
            'en' => 'Logistics',
            'de' => 'Logistik',
        ],
        'stavebnictvi' => [
            'cs' => 'Stavebnictví',
            'en' => 'Construction',
            'de' => 'Bauwesen',
        ],
        'remeslo' => [
            'cs' => 'Řemeslo',
            'en' => 'Skilled trade',
            'de' => 'Handwerk',
        ],
        'zdravotnictvi' => [
            'cs' => 'Zdravotnictví',
            'en' => 'Healthcare',
            'de' => 'Gesundheitswesen',
        ],
        'ubytovani' => [
            'cs' => 'Ubytování',
            'en' => 'Accommodation',
            'de' => 'Unterkunft',
        ],
        'motocykly' => [
            'cs' => 'Motorky',
            'en' => 'Motorcycles',
            'de' => 'Motorräder',
        ],

        // --- způsob spolupráce a technika ------------------------------------
        'dlouhodoba-spoluprace' => [
            'cs' => 'Dlouhodobá spolupráce',
            'en' => 'Long-term partnership',
            'de' => 'Langfristige Zusammenarbeit',
        ],
        'agenturni-spoluprace' => [
            'cs' => 'Práce pro agenturu',
            'en' => 'Work for an agency',
            'de' => 'Arbeit für eine Agentur',
        ],
        'udrzba' => [
            'cs' => 'Údržba a úpravy webu',
            'en' => 'Upkeep and site changes',
            'de' => 'Wartung und Änderungen',
        ],
        'vyvoj' => [
            'cs' => 'Programování na míru',
            'en' => 'Custom development',
            'de' => 'Individuelle Programmierung',
        ],
        'wordpress' => [
            'cs' => 'WordPress',
            'en' => 'WordPress',
            'de' => 'WordPress',
        ],
        'excel' => [
            'cs' => 'Excel',
            'en' => 'Excel',
            'de' => 'Excel',
        ],
        'vba' => [
            'cs' => 'Makra v Excelu',
            'en' => 'Excel macros',
            'de' => 'Excel-Makros',
        ],
    ];

    public function run(): void
    {
        foreach (self::NAMES as $slug => $names) {
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
}
