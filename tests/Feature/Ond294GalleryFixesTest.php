<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-294 — obrazové vady v galeriích případovek.
 *
 * Data žijí na dvou místech a obě musí říkat totéž:
 *  - `docs/portfolio-data.yaml` → čerstvá instalace (PortfolioSeeder),
 *  - migrace 2026_09_23_140000 → staging a produkce, kde se seeder po
 *    prvním naplnění DB přeskakuje.
 *
 * Testy proto jedou obě větve: jednou nad seedem z YAMLu a jednou nad
 * fixture, která napodobí produkční stav (původní řádky zpátky, popisky
 * pryč) a spustí skutečnou migraci.
 */
class Ond294GalleryFixesTest extends TestCase
{
    use RefreshDatabase;

    private const REMOVED = [
        ['realitacky-v-akci', 'projects/realitacky-v-akci/gallery-3.webp', 3],
        ['vp-industry', 'projects/vp-industry/gallery-1.webp', 1],
    ];

    private const CAPTIONED = [
        ['choccoboard', 'projects/choccoboard/gallery-1.webp'],
        ['choccoboard', 'projects/choccoboard/gallery-2.webp'],
        ['excel-tools', 'projects/excel-tools/gallery-5.jpg'],
    ];

    private const THUMB_SLUG = 'zubni-provazek';
    private const THUMB_PATH = 'projects/zubni-provazek/gallery-2.png';

    private const LOCALES = ['cs', 'en', 'de'];

    // ------------------------------------------------------------------
    // Větev A — YAML / PortfolioSeeder (čerstvá instalace)
    // ------------------------------------------------------------------

    public function test_seeder_drops_the_two_rejected_gallery_images(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::REMOVED as [$slug, $path]) {
            $this->assertNull($this->screenshot($slug, $path), "{$path} má být pryč");
        }
    }

    public function test_seeder_keeps_the_remaining_paths_stable_after_the_removals(): void
    {
        $this->seed(PortfolioSeeder::class);

        // Bez explicitního `path` v YAMLu by seeder zbylé snímky přečísloval
        // a ukázal by na soubory, které na disku nejsou.
        foreach ([
            'realitacky-v-akci' => ['gallery-1.webp', 'gallery-2.webp', 'gallery-4.webp', 'gallery-6.webp'],
            'vp-industry'       => ['gallery-2.webp', 'gallery-3.webp', 'gallery-4.webp', 'gallery-5.webp'],
        ] as $slug => $files) {
            foreach ($files as $file) {
                $path = "projects/{$slug}/{$file}";
                $this->assertNotNull($this->screenshot($slug, $path), "chybí {$path}");
                $this->assertFileExists(resource_path("img/{$path}"), $path);
            }
        }
    }

    public function test_seeder_gives_zubni_provazek_a_thumbnail_that_is_not_the_admin_screen(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->assertThumbnailIsTheWebsiteShot();
    }

    public function test_seeder_writes_the_anonymisation_captions(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->assertCaptionsArePresent();
    }

    public function test_touched_project_pages_still_render(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (['choccoboard', 'excel-tools', 'realitacky-v-akci', 'vp-industry', self::THUMB_SLUG] as $slug) {
            $this->get("/projekty/{$slug}")->assertOk();
        }

        // Popisek se vykresluje jako `figcaption` pod snímkem.
        $this->get('/projekty/choccoboard')
            ->assertSee('Konkrétní tržby jsou na přání klienta zakryté.', false);
    }

    public function test_thumbnail_row_is_not_rendered_twice_in_the_detail_gallery(): void
    {
        $this->seed(PortfolioSeeder::class);

        $html = $this->get('/projekty/'.self::THUMB_SLUG)->assertOk()->getContent();

        // Snímek je v DB dvakrát (gallery + thumbnail), v galerii smí být
        // jednou — `detail-gallery.blade.php` řádky `thumbnail` vynechává.
        // Počítáme `alt="…"`, ne holý text: `<x-responsive-image>` tentýž
        // řetězec vypisuje ještě jednou do `data-glightbox="title: …"`.
        $alt = 'Zubní Provázek – web ordinace na tabletu, logo a tým v ordinaci';
        $this->assertSame(
            1,
            substr_count($html, 'alt="'.$alt.'"'),
            'snímek se v galerii objevil dvakrát',
        );
    }

    // ------------------------------------------------------------------
    // Větev B — migrace nad produkčním stavem
    // ------------------------------------------------------------------

    public function test_migration_reaches_the_same_state_as_the_seeder(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToProductionState();

        $this->migration()->up();

        foreach (self::REMOVED as [$slug, $path]) {
            $this->assertNull($this->screenshot($slug, $path), "{$path} má být pryč");
        }

        $this->assertThumbnailIsTheWebsiteShot();
        $this->assertCaptionsArePresent();

        $this->assertSame(
            'Choccoboard – přehledová obrazovka s KPI tabulkou a TOP 10 položek na notebooku',
            $this->alt('choccoboard', 'projects/choccoboard/gallery-1.webp'),
        );
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToProductionState();

        $migration = $this->migration();
        $migration->up();
        $shotsAfterFirstRun = DB::table('portfolio_project_screenshots')->count();

        $migration->up();

        $this->assertSame($shotsAfterFirstRun, DB::table('portfolio_project_screenshots')->count());
        $this->assertThumbnailIsTheWebsiteShot();
    }

    public function test_migration_down_removes_the_thumbnail_and_the_captions(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToProductionState();

        $migration = $this->migration();
        $migration->up();
        $migration->down();

        $this->assertSame(0, $this->thumbnailRowCount());

        foreach (self::CAPTIONED as [$slug, $path]) {
            $this->assertNull($this->caption($slug, $path), "{$path} — popisek zůstal");
        }

        // Galerijní řádek snímku, na který `thumbnail` ukazoval, zůstává.
        $this->assertNotNull($this->screenshot(self::THUMB_SLUG, self::THUMB_PATH));
    }

    public function test_migration_is_inert_on_an_empty_database(): void
    {
        DB::table('portfolio_project_screenshot_translations')->delete();
        DB::table('portfolio_project_screenshots')->delete();
        DB::table('portfolio_project_translations')->delete();
        DB::table('portfolio_projects')->delete();

        $migration = $this->migration();
        $migration->up();
        $migration->down();

        $this->assertSame(0, DB::table('portfolio_projects')->count());
    }

    // ------------------------------------------------------------------
    // Pomocné
    // ------------------------------------------------------------------

    private function assertThumbnailIsTheWebsiteShot(): void
    {
        $this->assertSame(1, $this->thumbnailRowCount(), 'thumbnail řádek chybí nebo je duplicitní');

        $thumb = DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::THUMB_SLUG))
            ->where('type', 'thumbnail')
            ->first();

        $this->assertSame(self::THUMB_PATH, $thumb->path);
        $this->assertFileExists(resource_path('img/'.self::THUMB_PATH));

        // Administrace ordinačních hodin (`hero-1`) musí zůstat mimo kartu.
        $project = \App\Models\Portfolio\PortfolioProject::where('slug', self::THUMB_SLUG)->firstOrFail();
        $this->assertSame(self::THUMB_PATH, portfolio_card_thumbnail($project->screenshots)->path);

        $alts = DB::table('portfolio_project_screenshot_translations')
            ->where('screenshot_id', $thumb->id)
            ->pluck('alt', 'locale');

        foreach (self::LOCALES as $locale) {
            $this->assertNotEmpty($alts[$locale] ?? null, "alt pro {$locale}");
        }
    }

    private function assertCaptionsArePresent(): void
    {
        foreach (self::CAPTIONED as [$slug, $path]) {
            $id = $this->screenshot($slug, $path)?->id;
            $this->assertNotNull($id, "{$path} v DB chybí");

            $captions = DB::table('portfolio_project_screenshot_translations')
                ->where('screenshot_id', $id)
                ->pluck('caption', 'locale');

            foreach (self::LOCALES as $locale) {
                $this->assertNotEmpty($captions[$locale] ?? null, "{$path} — popisek pro {$locale}");
            }
        }
    }

    /**
     * Napodobí stav produkční DB před migrací: oba zamítnuté snímky zpátky,
     * `thumbnail` řádek pryč, popisky prázdné, alt v původním generickém
     * znění.
     */
    private function revertToProductionState(): void
    {
        foreach (self::REMOVED as [$slug, $path, $sortOrder]) {
            $id = DB::table('portfolio_project_screenshots')->insertGetId([
                'project_id' => $this->projectId($slug),
                'path'       => $path,
                'type'       => 'gallery',
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (self::LOCALES as $locale) {
                DB::table('portfolio_project_screenshot_translations')->insert([
                    'screenshot_id' => $id,
                    'locale'        => $locale,
                    'alt'           => 'sekce',
                    'caption'       => null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        $thumbIds = DB::table('portfolio_project_screenshots')
            ->where('type', 'thumbnail')
            ->where('path', self::THUMB_PATH)
            ->pluck('id');
        DB::table('portfolio_project_screenshot_translations')->whereIn('screenshot_id', $thumbIds)->delete();
        DB::table('portfolio_project_screenshots')->whereIn('id', $thumbIds)->delete();

        foreach (self::CAPTIONED as [$slug, $path]) {
            DB::table('portfolio_project_screenshot_translations')
                ->where('screenshot_id', $this->screenshot($slug, $path)->id)
                ->update(['caption' => null, 'alt' => 'sekce']);
        }
    }

    private function migration(): Migration
    {
        return require database_path('migrations/2026_09_23_140000_ond294_obrazove_vady_galerii.php');
    }

    private function thumbnailRowCount(): int
    {
        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::THUMB_SLUG))
            ->where('type', 'thumbnail')
            ->count();
    }

    private function screenshot(string $slug, string $path): ?object
    {
        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId($slug))
            ->where('path', $path)
            ->where('type', '!=', 'thumbnail')
            ->orderBy('id')
            ->first();
    }

    private function alt(string $slug, string $path): ?string
    {
        return DB::table('portfolio_project_screenshot_translations')
            ->where('screenshot_id', $this->screenshot($slug, $path)->id)
            ->where('locale', 'cs')
            ->value('alt');
    }

    private function caption(string $slug, string $path): ?string
    {
        return DB::table('portfolio_project_screenshot_translations')
            ->where('screenshot_id', $this->screenshot($slug, $path)->id)
            ->where('locale', 'cs')
            ->value('caption');
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }
}
