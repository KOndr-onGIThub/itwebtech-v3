<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-454 — opravy případovek po QA OND-450: V1 průměrná pozice pryč,
 * V2 popisky 11 snímků místo „sekce N“, V3 BARANA bez „0,2 s“, V4 zdroj
 * a datum do řádku pod výsledkem, V6 popisek hlavního snímku BARANY,
 * Kemp Veselka rezervuje po telefonu.
 *
 * Čerstvá DB = `docs/portfolio-data.yaml`, produkce = datová migrace
 * `2026_09_28_230000_ond454_…`. Seed musí být přesně stav „po migraci“.
 */
class Ond454PortfolioQaFixesTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    private const MIGRATION = '2026_09_28_230000_ond454_pripadovky_opravy_po_qa.php';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_no_detail_has_section_n_captions_or_average_position(): void
    {
        $projects = PortfolioProject::published()->get();
        // OND-470: 21 → 17 (čtyři projekty odpublikované).
        $this->assertCount(17, $projects);

        foreach ($projects as $project) {
            foreach (self::LOCALES as $locale) {
                $label = "{$project->slug} ({$locale})";
                $html = $this->get($project->detailUrl($locale))->assertOk()->getContent();

                preg_match_all('#\b(?:alt|data-glightbox)="([^"]*)"#', $html, $m);
                $this->assertNotEmpty($m[1], "{$label}: stránka bez obrázků");
                foreach ($m[1] as $caption) {
                    $this->assertDoesNotMatchRegularExpression(
                        '/(sekce|section|Abschnitt) \d/iu',
                        html_entity_decode($caption, ENT_QUOTES | ENT_HTML5),
                        $label
                    );
                }

                $this->assertDoesNotMatchRegularExpression(
                    '/průměrná pozice|average position|durchschnittliche Position/iu',
                    html_entity_decode($html, ENT_QUOTES | ENT_HTML5),
                    $label
                );
            }
        }

        $this->assertSame(0, DB::table('portfolio_project_screenshot_translations')
            ->where(fn ($q) => $q->where('alt', 'like', '%sekce %')->orWhere('alt', 'like', '%section %')->orWhere('alt', 'like', '%Abschnitt %'))
            ->get()->filter(fn ($r) => preg_match('/(sekce|section|Abschnitt) \d/u', $r->alt))->count());
    }

    public function test_result_source_line_under_result_and_no_source_brackets_in_text(): void
    {
        $nb = "\u{00A0}";
        $gsc = [
            'cs' => "Google Search Console (pozice 16.{$nb}5.{$nb}2025 – 13.{$nb}9.{$nb}2026), Google Analytics (návštěvy 1.{$nb}1. – 16.{$nb}9.{$nb}2026)",
            'en' => "Google Search Console (rankings 16{$nb}May{$nb}2025 – 13{$nb}September{$nb}2026), Google Analytics (visits 1{$nb}January – 16{$nb}September{$nb}2026)",
            'de' => 'Google Search Console (Positionen 16.05.2025 – 13.09.2026), Google Analytics (Besuche 01.01. – 16.09.2026)',
        ];
        $expected = [
            'cyklocentrum' => [
                'cs' => "Stav k 16.{$nb}9.{$nb}2026. Zdroj: {$gsc['cs']}.",
                'en' => "As of 16{$nb}September{$nb}2026. Source: {$gsc['en']}.",
                'de' => "Stand: 16.09.2026. Quelle: {$gsc['de']}.",
            ],
            'pitarena-eshop' => [
                'cs' => "Stav k 17.{$nb}9.{$nb}2026. Zdroj: Lighthouse 13.4.1 (rychlost, měřeno lokálně 16.{$nb}9.{$nb}2026), administrace e-shopu (objednávky 1.{$nb}7. – 17.{$nb}9.{$nb}2026).",
                'en' => "As of 17{$nb}September{$nb}2026. Source: Lighthouse 13.4.1 (speed, measured locally on 16{$nb}September{$nb}2026), shop administration (orders 1{$nb}July – 17{$nb}September{$nb}2026).",
                'de' => 'Stand: 17.09.2026. Quelle: Lighthouse 13.4.1 (Geschwindigkeit, lokal gemessen am 16.09.2026), Shop-Administration (Bestellungen 01.07. – 17.09.2026).',
            ],
        ];
        $expected['zubni-provazek'] = $expected['cyklocentrum'];

        $brackets = ['(Search Console', '(Google Analytics', '(měřeno', '(measured', '(gemessen', '(údaje z', '(figures from', '(Daten aus', '4 551', '4,551', '4.551'];

        foreach ($expected as $slug => $lines) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();
            foreach (self::LOCALES as $locale) {
                $label = "{$slug} ({$locale})";
                $flat = $this->flat($this->get($project->detailUrl($locale))->assertOk()->getContent());
                $this->assertMatchesRegularExpression(
                    '#<h2 class="pd-step__title">' . preg_quote(__('projects.detail.result', [], $locale), '#') . '</h2> <p class="pd-step__text">([^<]+)</p> <p class="pd-story__source">' . preg_quote(e($lines[$locale]), '#') . '</p>#u',
                    $flat,
                    $label
                );
                $result = $project->translation($locale)->result;
                foreach ($brackets as $phrase) {
                    $this->assertStringNotContainsString($phrase, $result, "{$label}: „{$phrase}“");
                }
            }
        }
    }

    public function test_barana_without_server_time_and_hero_caption_says_laptop(): void
    {
        $project = PortfolioProject::where('slug', 'barana')->firstOrFail();
        $hero = [
            'cs' => 'Web na notebooku, tabletu i mobilu: úvod, stránka o pergolách a poptávkový formulář',
            'en' => 'The website on a laptop, tablet and phone: homepage, pergola page and enquiry form',
            'de' => 'Die Website auf Notebook, Tablet und Handy: Startseite, Pergola-Seite und Anfrageformular',
        ];

        foreach (self::LOCALES as $locale) {
            $html = $this->get($project->detailUrl($locale))->assertOk()->getContent();
            $this->assertDoesNotMatchRegularExpression('/\b0[,.]2\s?s\b/u', $this->visibleText($html), "barana ({$locale})");
            $this->assertStringContainsString('alt="' . e($hero[$locale]) . '"', $html, "barana ({$locale})");
            $this->assertStringNotContainsStringIgnoringCase('monitor', $hero[$locale]);
        }
        // Řádek zdroje u BARANY nevzniká (pět týdnů je doba realizace, ne měření).
        $this->assertStringNotContainsString('pd-story__source', $this->get('/projekty/barana')->getContent());
    }

    public function test_kemp_veselka_books_by_phone_not_online(): void
    {
        $project = PortfolioProject::where('slug', 'kemp-veselka')->firstOrFail();
        $forbidden = [
            'cs' => ['pár tapnutí', 'rezervační proces', 'rovnou rezervovat', 'jednoduchou rezervací'],
            'en' => ['few taps', 'booking flow', 'book on the spot', 'simple booking'],
            'de' => ['wenige Taps', 'Buchungsablauf', 'direkt buchen', 'einfacher Buchung'],
        ];
        $phone = ['cs' => 'rovnou k telefonu', 'en' => 'straight to the phone number', 'de' => 'direkt zur Telefonnummer'];

        foreach (self::LOCALES as $locale) {
            $html = html_entity_decode($this->get($project->detailUrl($locale))->assertOk()->getContent(), ENT_QUOTES | ENT_HTML5);
            foreach ($forbidden[$locale] as $phrase) {
                $this->assertStringNotContainsStringIgnoringCase($phrase, $html, "kemp-veselka ({$locale}): „{$phrase}“");
            }
            $this->assertStringContainsString($phone[$locale], $this->visibleText($html), "kemp-veselka ({$locale})");
        }
    }

    public function test_migration_down_and_up_round_trip_on_seeded_data(): void
    {
        $migration = require database_path('migrations/' . self::MIGRATION);
        $after = $this->fingerprint();

        $migration->down();
        $this->assertNotSame($after, $this->fingerprint(), 'down() nic nevrátil — seed neodpovídá stavu po migraci');
        $cyklo = PortfolioProject::where('slug', 'cyklocentrum')->first();
        $this->assertNull($cyklo->result_as_of);
        $this->assertNull($cyklo->translation('de')->result_source);
        $this->assertStringContainsString('von 16,8 auf 8,6', $cyklo->translation('de')->result);
        // 11 snímků × 3 jazyky; 10 z nich OND-468 z galerie vyřadilo, zbývá 1.
        $this->assertSame(3, DB::table('portfolio_project_screenshot_translations')
            ->get()->filter(fn ($r) => preg_match('/(sekce|section|Abschnitt) \d/u', $r->alt))->count());

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'up() po down() nedal stav ze seedu');

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'druhý běh up() není no-op');
    }

    public function test_migration_leaves_manual_edits_alone(): void
    {
        $migration = require database_path('migrations/' . self::MIGRATION);
        $migration->down();

        $cyklo = PortfolioProject::where('slug', 'cyklocentrum')->first();
        $cyklo->translation('cs')->update(['result' => 'Ručně upravený výsledek.', 'result_source' => 'Ruční zdroj']);
        $migration->up();

        $cs = $cyklo->translation('cs')->fresh();
        $this->assertSame('Ručně upravený výsledek.', $cs->result);
        $this->assertSame('Ruční zdroj', $cs->result_source);
        // Ostatní jazyky ruční úpravu nemají, ty migrace opraví.
        $this->assertStringNotContainsString('16.8', $cyklo->translation('en')->fresh()->result);
        $this->assertNotNull($cyklo->translation('en')->fresh()->result_source);
    }

    public function test_migration_is_inert_on_empty_database(): void
    {
        DB::table('portfolio_projects')->delete();
        $migration = require database_path('migrations/' . self::MIGRATION);
        $migration->up();
        $migration->down();
        $this->assertSame(0, DB::table('portfolio_projects')->count());
    }

    private function fingerprint(): string
    {
        return md5(json_encode([
            DB::table('portfolio_project_translations')->orderBy('id')->get()->map(fn ($r) => collect((array) $r)->except(['created_at', 'updated_at']))->all(),
            // SQLite drží datum ze seederu (Eloquent) i s časem, z migrace bez něj.
            DB::table('portfolio_projects')->orderBy('id')->get(['slug', DB::raw('substr(result_as_of, 1, 10) as as_of')])->all(),
            DB::table('portfolio_project_screenshots as s')->join('portfolio_project_screenshot_translations as t', 't.screenshot_id', '=', 's.id')
                ->orderBy('s.project_id')->orderBy('s.path')->orderBy('s.type')->orderBy('t.locale')
                ->get(['s.project_id', 's.path', 's.type', 't.locale', 't.alt', 't.caption'])->all(),
        ]));
    }

    /** Text stránky bez skriptů, stylů a atributů — hledá se v tom, co člověk čte. */
    private function visibleText(string $html): string
    {
        $html = preg_replace('#<(script|style)\b.*?</\1>#s', ' ', $html);

        return html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5);
    }

    private function flat(string $html): string
    {
        return preg_replace('/\s+/', ' ', $html);
    }
}
