<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-449 — případovky podle plánu OND-441: B-03 PitArena, B-04 BARANA,
 * B-05 odkaz na živý web za „Výsledkem“, B-06 karta = lead, B-07 popisky,
 * B-07b video BARANY, B-09 zdroj a datum výsledku.
 *
 * Čerstvá DB = `docs/portfolio-data.yaml`, produkce = datová migrace
 * `2026_09_28_210100_ond449_…`. Test migrace pouští `down()` a `up()`
 * nad seedem: seed musí být přesně stav „po migraci“, jinak `down()`
 * nenajde nové hodnoty a nic nevrátí.
 */
class Ond449PortfolioTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    private const MIGRATION = '2026_09_28_210100_ond449_pripadovky_popisky_media.php';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_pitarena_detail_has_no_booking_voucher_sales_or_average_position(): void
    {
        $forbidden = [
            // „rezervace startovních čísel“ je schválený text (systém závodů), zakázané jsou tréninky.
            'cs' => ['rezervace trénink', 'rezervací trénink', 'rezervace, vouchery', 'online prodej', 'bez ručního zásahu', 'měsíce dopředu', 'průměrn'],
            'en' => ['booking', 'online sale', 'without manual', 'months ahead', 'average position'],
            'de' => ['Buchung', 'Online-Verkauf', 'ohne manuellen', 'Monate im Voraus', 'durchschnittliche Position'],
        ];
        $project = PortfolioProject::where('slug', 'pitarena')->firstOrFail();

        foreach (self::LOCALES as $locale) {
            $body = $this->visibleText($this->get($project->detailUrl($locale))->assertOk()->getContent());
            foreach ($forbidden[$locale] as $phrase) {
                $this->assertStringNotContainsStringIgnoringCase($phrase, $body, "pitarena ({$locale}): „{$phrase}“");
            }
        }

        $this->assertStringContainsString(
            '<title>PitArena — web, závodní systém a značka pro motokrosové centrum — Ondřej Kriška, ONDRAWEB</title>',
            $this->get('/projekty/pitarena')->getContent()
        );
        $this->assertFalse($project->tags()->where('slug', 'rezervace')->exists());
    }

    public function test_barana_detail_is_about_the_website_not_the_ad_page(): void
    {
        $project = PortfolioProject::where('slug', 'barana')->firstOrFail();
        $body = $this->visibleText($this->get('/projekty/barana')->assertOk()->getContent());

        // „Recenze“ je v menu; zakázané je slibovat recenze na webu klienta.
        // „vede placená reklama“ v perexu je schválený doplněk; štítek „Placená reklama“ hlídá DB níž.
        foreach (['landing page', 'bez nutnosti volat', 'stejně rychle', 'referencí, recenzí'] as $phrase) {
            $this->assertStringNotContainsStringIgnoringCase($phrase, $body, "barana: „{$phrase}“");
        }
        $this->assertStringContainsString('Za pět intenzivních týdnů vznikl web', $body);
        $this->assertSame('5 týdnů', $project->duration);
        $this->assertFalse($project->tags()->where('slug', 'kampane')->exists());
    }

    public function test_result_source_line_sits_right_under_the_result_only_when_filled(): void
    {
        $expected = [
            'cs' => 'Stav k 28. 9. 2026. Zdroj: Collabim (pozice), Google Search Console (návštěvy 16. 5. 2025 – 13. 9. 2026).',
            'en' => 'As of 28 September 2026. Source: Collabim (rankings), Google Search Console (visits 16 May 2025 – 13 September 2026).',
            'de' => 'Stand: 28.09.2026. Quelle: Collabim (Positionen), Google Search Console (Besuche 16.05.2025 – 13.09.2026).',
        ];
        $project = PortfolioProject::where('slug', 'pitarena')->firstOrFail();

        foreach (self::LOCALES as $locale) {
            $flat = $this->flat($this->get($project->detailUrl($locale))->getContent());
            $this->assertMatchesRegularExpression(
                '#<h2 class="pd-step__title">' . preg_quote(__('projects.detail.result', [], $locale), '#') . '</h2> <p class="pd-step__text">[^<]+</p> <p class="pd-story__source">' . preg_quote(e($expected[$locale]), '#') . '</p>#u',
                $flat,
                "pitarena ({$locale})"
            );
        }

        // Bez data/zdroje řádek není (BARANA má „0,2 s“ bez zdroje — nahlášeno CEO).
        $this->assertStringNotContainsString('pd-story__source', $this->get('/projekty/barana')->getContent());
    }

    public function test_live_site_link_moved_from_detail_head_to_block_after_result(): void
    {
        foreach (PortfolioProject::published()->with('translations')->get() as $project) {
            foreach (self::LOCALES as $locale) {
                $body = $this->get($project->detailUrl($locale))->assertOk()->getContent();
                $label = "{$project->slug} ({$locale})";
                $this->assertStringNotContainsString('pd-page-head__actions', $body, $label);

                if (! $project->live_url) {
                    $this->assertStringNotContainsString('pd-story__live', $body, $label);
                    continue;
                }
                $hint = $project->translation($locale)?->live_hint ?: __('projects.detail.live_hint_default', [], $locale);
                $this->assertSame(1, substr_count($body, 'data-analytics="case_study_live_click"'), $label);
                $flat = $this->flat($body);
                $result = strpos($flat, '<h2 class="pd-step__title">' . __('projects.detail.result', [], $locale) . '</h2>');
                $live = strpos($flat, '<div class="pd-story__live">');
                $this->assertNotFalse($live, $label);
                $this->assertGreaterThan($result, $live, "{$label}: blok naživo až za výsledkem");
                $this->assertStringContainsString(
                    '<div class="pd-story__live"> <h2 class="pd-step__title">' . e(__('projects.detail.live_heading', [], $locale)) . '</h2> <p class="pd-step__text">' . e($hint) . '</p>',
                    $flat,
                    $label
                );
            }
        }

        // 13 publikovaných projektů s živým webem má vlastní větu ve všech jazycích.
        $this->assertSame(39, DB::table('portfolio_project_translations')->whereNotNull('live_hint')->count());
    }

    public function test_homepage_cards_have_no_live_site_link(): void
    {
        config(['site.features.show_portfolio_section' => true]);

        foreach (['/', '/en/', '/de/'] as $url) {
            $body = $this->get($url)->assertOk()->getContent();
            $this->assertStringNotContainsString('pd-case__live', $body, $url);
            $this->assertStringNotContainsString('showcase_site_click', $body, $url);
        }
    }

    public function test_card_image_is_the_detail_lead_for_every_published_project(): void
    {
        foreach (PortfolioProject::published()->with('screenshots')->get() as $project) {
            $lead = portfolio_lead_image($project->screenshots);
            $this->assertNotNull($lead, "{$project->slug}: projekt bez širokého snímku");
            $this->assertSame($lead->path, portfolio_card_thumbnail($project->screenshots)->path, $project->slug);
            $this->assertSame('wide', screenshot_gallery_role($lead->path), $project->slug);
        }

        // choccoboard: lead = přehled s KPI, ne přihlašovací obrazovka.
        $choc = PortfolioProject::where('slug', 'choccoboard')->with('screenshots')->firstOrFail();
        $this->assertSame('projects/choccoboard/gallery-1.webp', portfolio_lead_image($choc->screenshots)->path);
    }

    public function test_lightbox_captions_and_removed_screenshots(): void
    {
        $alt = fn (string $slug, string $path, string $locale) => DB::table('portfolio_project_screenshot_translations as t')
            ->join('portfolio_project_screenshots as s', 's.id', '=', 't.screenshot_id')
            ->join('portfolio_projects as p', 'p.id', '=', 's.project_id')
            ->where('p.slug', $slug)->where('s.path', $path)->where('t.locale', $locale)->where('s.type', '!=', 'thumbnail')
            ->value('t.alt');

        $this->assertSame('Interaktivní ukázka: návštěvník si natočí lamely podle počasí', $alt('barana', 'projects/barana/gallery-1.png', 'cs'));
        $this->assertSame('The campaign beyond the website: a poster in the same visual style', $alt('barana', 'projects/barana/gallery-4.png', 'en'));
        $this->assertSame('Die ursprüngliche Wix-Website vor der Überarbeitung', $alt('vanspedition', 'projects/vanspedition/gallery-2.webp', 'de'));
        $this->assertNull($alt('excel-tools', 'projects/excel-tools/gallery-3.jpg', 'cs'), '#VALUE! snímek pryč');
        $this->assertNull($alt('barana', 'projects/barana/gallery-5.png', 'cs'));

        // Popisek je titulek lightboxu.
        $this->assertStringContainsString(
            'data-glightbox="title: Merch ve stylu značky"',
            $this->get('/projekty/pitarena')->getContent()
        );

        // Každý snímek, na který DB ukazuje, v repu opravdu je.
        foreach (DB::table('portfolio_project_screenshots')->pluck('path') as $path) {
            $this->assertFileExists(resource_path('img/' . $path));
        }
    }

    public function test_barana_demo_video_is_data_driven_lazy_and_below_first_gallery_block(): void
    {
        $flat = $this->flat($this->get('/projekty/barana')->assertOk()->getContent());

        $this->assertSame(1, substr_count($flat, 'data-demo-video'));
        $this->assertStringContainsString('<video muted loop playsinline preload="none" aria-hidden="true" tabindex="-1" disablepictureinpicture disableremoteplayback>', $flat);
        foreach (['barana-demo-mobile.webm', 'barana-demo-mobile.mp4', 'barana-demo.webm', 'barana-demo.mp4', 'barana-demo-poster.jpg', 'barana-demo-mobile-poster.jpg'] as $file) {
            $this->assertStringContainsString('/videos/portfolio/' . $file . '?v=', $flat, $file);
            $this->assertFileExists(public_path('videos/portfolio/' . $file));
        }
        $this->assertMatchesRegularExpression('#<source media="\(max-width: 767px\)" src="[^"]+barana-demo-mobile\.webm\?v=[^"]+" type="video/webm; codecs=&quot;av01\.0\.05M\.08&quot;">#', $flat);

        // Pod prvním blokem galerie (band s ukázkou lamel), před druhým.
        $gallery = substr($flat, strpos($flat, 'data-pdd="project-gallery"'));
        $this->assertMatchesRegularExpression('#^[^>]*> <div class="container-site"> <figure class="pd-gallery__band">.*?</figure> <figure class="pd-gallery__band pd-demo" data-demo-video>#s', $gallery);

        // Kemp Veselka (případovka ceníku): video pod prvním blokem galerie, galerie beze změny.
        $veselka = $this->flat($this->get('/projekty/kemp-veselka')->assertOk()->getContent());
        $this->assertSame(1, substr_count($veselka, 'data-demo-video'));
        foreach (['kemp-veselka-demo.webm', 'kemp-veselka-demo.mp4', 'kemp-veselka-demo-mobile.webm', 'kemp-veselka-demo-mobile.mp4', 'kemp-veselka-demo-poster.jpg', 'kemp-veselka-demo-mobile-poster.jpg'] as $file) {
            $this->assertStringContainsString('/videos/portfolio/' . $file . '?v=', $veselka, $file);
        }
        // První blok galerie = trojice karet (poslední je logo), video za ním, před pásem gallery-4.
        $video = strpos($veselka, 'data-demo-video');
        $this->assertGreaterThan(strpos($veselka, 'alt="Nové logo autokempu"'), $video);
        $this->assertLessThan(strpos($veselka, 'projects/kemp-veselka/gallery-4') ?: strpos($veselka, 'Autokemp Veselka – sekce 4'), $video);
        $this->assertSame(6, DB::table('portfolio_project_screenshots')->where('project_id', PortfolioProject::where('slug', 'kemp-veselka')->value('id'))->count());

        // Video je jen tam, kde ho zapínají data.
        $this->assertStringNotContainsString('data-demo-video', $this->get('/projekty/pitarena')->getContent());
        PortfolioProject::where('slug', 'barana')->update(['demo_video' => null]);
        $this->assertStringNotContainsString('data-demo-video', $this->get('/projekty/barana')->getContent());
        // Zapnuté bez souborů na disku → nic (Veselka před dodáním videa).
        PortfolioProject::where('slug', 'kemp-veselka')->update(['demo_video' => 'neexistuje-demo']);
        $this->assertStringNotContainsString('data-demo-video', $this->get('/projekty/kemp-veselka')->getContent());
    }

    public function test_admin_form_round_trips_the_new_fields(): void
    {
        $project = PortfolioProject::where('slug', 'pitarena')->firstOrFail();
        $data = \App\Filament\Resources\PortfolioProjectResource::fillFormData($project, []);
        $this->assertSame('Projděte si výsledky a profily jezdců YCF Cup.', $data['translations']['cs']['live_hint']);
        $this->assertStringStartsWith('Collabim (pozice)', $data['translations']['cs']['result_source']);

        $data['translations']['cs']['live_hint'] = 'Ručně upravená věta.';
        $data['translations']['en']['result_source'] = '';
        \App\Filament\Resources\PortfolioProjectResource::persistProjectTranslations($project, $data['translations']);

        $this->assertSame('Ručně upravená věta.', $project->translation('cs')->fresh()->live_hint);
        $this->assertNull($project->translation('en')->fresh()->result_source, 'prázdné pole = NULL, řádek zdroje zmizí');
    }

    public function test_migration_down_and_up_round_trip_on_seeded_data(): void
    {
        $migration = require database_path('migrations/' . self::MIGRATION);
        $after = $this->fingerprint();

        $migration->down();
        $before = $this->fingerprint();
        $this->assertNotSame($after, $before, 'down() nic nevrátil — seed neodpovídá stavu po migraci');
        $pitarena = PortfolioProject::where('slug', 'pitarena')->first();
        $this->assertTrue($pitarena->tags()->where('slug', 'rezervace')->exists());
        $this->assertNull($pitarena->result_as_of);
        $this->assertSame('projects/choccoboard/hero-1.webp', portfolio_lead_image(PortfolioProject::where('slug', 'choccoboard')->first()->screenshots)->path);

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'up() po down() nedal stav ze seedu');

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'druhý běh up() není no-op');
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
            DB::table('portfolio_projects')->orderBy('id')->get(['slug', DB::raw('substr(result_as_of, 1, 10) as as_of'), 'demo_video'])->all(),
            DB::table('portfolio_project_screenshots as s')->join('portfolio_project_screenshot_translations as t', 't.screenshot_id', '=', 's.id')
                ->orderBy('s.project_id')->orderBy('s.path')->orderBy('s.type')->orderBy('t.locale')
                ->get(['s.project_id', 's.path', 's.type', 't.locale', 't.alt', 't.caption'])->all(),
            DB::table('portfolio_project_outcomes as o')->join('portfolio_project_outcome_translations as t', 't.outcome_id', '=', 'o.id')
                ->orderBy('o.project_id')->orderBy('o.sort_order')->orderBy('t.locale')
                ->get(['o.project_id', 'o.sort_order', 't.locale', 't.label'])->all(),
            DB::table('portfolio_project_tag')->orderBy('project_id')->orderBy('tag_id')->get()->all(),
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
