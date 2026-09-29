<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-468 — B-07 V7 A: z galerií 6 projektů mizí 10 slabých snímků.
 *
 * Čerstvá DB = `docs/portfolio-data.yaml`, produkce = datová migrace
 * `2026_09_29_190000_ond468_…`. Seed musí být přesně stav „po migraci“.
 */
class Ond468GalleryWeakShotsTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    private const MIGRATION = '2026_09_29_190000_ond468_vyrazeni_slabych_snimku.php';

    /** slug => [snímků v galerii detailu před, po, vyřazené soubory, lead] */
    private const PROJECTS = [
        'barana'              => [5, 5, [], 'projects/barana/hero-1.png'],
        'cyklocentrum'        => [6, 4, ['gallery-1.png', 'gallery-2.png'], 'projects/cyklocentrum/gallery-4.png'],
        'excel-tools'         => [4, 3, ['gallery-4.jpg'], 'projects/excel-tools/hero-1.jpg'],
        'hcms'                => [7, 5, ['gallery-1.jpg', 'gallery-4.jpg'], 'projects/hcms/hero-1.jpg'],
        'kemp-veselka'        => [6, 4, ['gallery-4.webp', 'gallery-5.webp'], 'projects/kemp-veselka/hero-1.webp'],
        'picker'              => [6, 4, ['gallery-2.jpg', 'gallery-4.jpg'], 'projects/picker/hero-1.jpg'],
        'zubni-provazek'      => [6, 5, ['gallery-3.png'], 'projects/zubni-provazek/gallery-1.png'],
        'clanek-motorkari-cz' => [3, 3, [], 'projects/clanek-motorkari-cz/hero-1.jpg'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_gallery_counts_and_lead_after_removal(): void
    {
        foreach (self::PROJECTS as $slug => [, $after, $removed, $lead]) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();

            $this->assertSame($after, $this->galleryCount($slug), $slug);
            $this->assertGreaterThanOrEqual(3, $after, "{$slug}: pod 3 snímky (lead + 2)");
            $this->assertSame($lead, portfolio_lead_image($project->screenshots)?->path, "{$slug}: lead");
            $this->assertSame($lead, portfolio_card_thumbnail($project->screenshots)?->path, "{$slug}: karta");

            foreach ($removed as $file) {
                $this->assertFalse(
                    $project->screenshots()->where('path', "projects/{$slug}/{$file}")->exists(),
                    "{$slug}/{$file} je pořád v DB"
                );
                // Soubor zůstává, vyřazení je vratné.
                $this->assertFileExists(resource_path("img/projects/{$slug}/{$file}"));
            }
        }
    }

    public function test_detail_pages_render_without_removed_shots(): void
    {
        foreach (self::PROJECTS as $slug => [, $after, $removed]) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();
            // OND-470: odpublikovaný projekt (článek na Motorkáři) detail nemá.
            if ($project->published_at === null) {
                continue;
            }
            $alts = DB::table('portfolio_project_screenshots as s')
                ->join('portfolio_project_screenshot_translations as t', 't.screenshot_id', '=', 's.id')
                ->where('s.project_id', $project->id)->where('s.type', '!=', 'thumbnail')
                ->get(['t.locale', 't.alt']);

            foreach (self::LOCALES as $locale) {
                $label = "{$slug} ({$locale})";
                $html = $this->get($project->detailUrl($locale))->assertOk()->getContent();

                $this->assertSame($after, substr_count($html, 'class="pd-gallery__frame"'), $label);
                foreach ($alts->where('locale', $locale) as $row) {
                    $this->assertStringContainsString(e($row->alt), $html, $label);
                }
                foreach ($removed as $file) {
                    $this->assertStringNotContainsString("projects/{$slug}/{$file}", $html, $label);
                }
            }
        }

        // Kemp Veselka: video zůstává pod prvním blokem galerie (trojice karet).
        $veselka = preg_replace('/\s+/', ' ', $this->get('/projekty/kemp-veselka')->assertOk()->getContent());
        $this->assertSame(1, substr_count($veselka, 'data-demo-video'));
        $this->assertGreaterThan(strpos($veselka, 'alt="Nové logo autokempu"'), strpos($veselka, 'data-demo-video'));
    }

    public function test_migration_down_and_up_round_trip_on_seeded_data(): void
    {
        $migration = require database_path('migrations/' . self::MIGRATION);
        $after = $this->fingerprint();

        $migration->down();
        foreach (self::PROJECTS as $slug => [$before]) {
            $this->assertSame($before, $this->galleryCount($slug), "down(): {$slug}");
        }
        $this->assertSame(
            'Rezervace po telefonu: sekce „Pro rezervaci volejte“ s číslem jako tlačítkem',
            $this->alt('kemp-veselka', 'projects/kemp-veselka/gallery-4.webp', 'cs')
        );
        $this->assertSame(
            'Problem melden: Teil nicht gefunden, Teil oder Box beschädigt, eigene Nachricht',
            $this->alt('picker', 'projects/picker/gallery-4.jpg', 'de')
        );
        $before = $this->fingerprint();

        $migration->down();
        $this->assertSame($before, $this->fingerprint(), 'druhý běh down() není no-op');

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'up() po down() nedal stav ze seedu');

        $migration->up();
        $this->assertSame($after, $this->fingerprint(), 'druhý běh up() není no-op');
    }

    public function test_migration_leaves_replaced_image_and_thumbnail_rows_alone(): void
    {
        $migration = require database_path('migrations/' . self::MIGRATION);
        $migration->down();

        // Ve Filamentu někdo u snímku vyměnil obrázek → řádek už není ten slabý.
        $picker = PortfolioProject::where('slug', 'picker')->firstOrFail();
        $picker->screenshots()->where('path', 'projects/picker/gallery-2.jpg')->update(['path' => 'projects/picker/gallery-2-new.jpg']);
        // Náhled karty na stejném souboru jako vyřazovaný snímek.
        DB::table('portfolio_project_screenshots')->insert([
            'project_id' => PortfolioProject::where('slug', 'hcms')->value('id'),
            'path'       => 'projects/hcms/gallery-4.jpg',
            'type'       => 'thumbnail',
            'sort_order' => 9,
        ]);

        $migration->up();

        $this->assertTrue($picker->screenshots()->where('path', 'projects/picker/gallery-2-new.jpg')->exists());
        $this->assertFalse($picker->screenshots()->where('path', 'projects/picker/gallery-4.jpg')->exists());
        $hcms = DB::table('portfolio_project_screenshots')->where('path', 'projects/hcms/gallery-4.jpg');
        $this->assertSame(['thumbnail'], $hcms->pluck('type')->all());
    }

    public function test_migration_is_inert_on_empty_database(): void
    {
        DB::table('portfolio_projects')->delete();
        $migration = require database_path('migrations/' . self::MIGRATION);
        $migration->up();
        $migration->down();
        $this->assertSame(0, DB::table('portfolio_projects')->count());
        $this->assertSame(0, DB::table('portfolio_project_screenshots')->count());
    }

    private function galleryCount(string $slug): int
    {
        return DB::table('portfolio_project_screenshots')
            ->where('project_id', PortfolioProject::where('slug', $slug)->value('id'))
            ->where('type', '!=', 'thumbnail')
            ->count();
    }

    private function alt(string $slug, string $path, string $locale): ?string
    {
        return DB::table('portfolio_project_screenshots as s')
            ->join('portfolio_project_screenshot_translations as t', 't.screenshot_id', '=', 's.id')
            ->where('s.project_id', PortfolioProject::where('slug', $slug)->value('id'))
            ->where('s.path', $path)->where('t.locale', $locale)
            ->value('t.alt');
    }

    /** Pořadí ze `sort_order` (ne jeho hodnota — seed čísluje bez mezer, migrace mezery nechává). */
    private function fingerprint(): string
    {
        return md5(json_encode(
            DB::table('portfolio_project_screenshots as s')
                ->join('portfolio_project_screenshot_translations as t', 't.screenshot_id', '=', 's.id')
                ->orderBy('s.project_id')->orderBy('s.sort_order')->orderBy('s.path')->orderBy('s.type')->orderBy('t.locale')
                ->get(['s.project_id', 's.path', 's.type', 't.locale', 't.alt', 't.caption'])->all()
        ));
    }
}
