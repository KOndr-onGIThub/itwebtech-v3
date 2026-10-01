<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-501 — případovka Vinařství Antoš (texty z OND-494, dokument
 * `pripadovka` v4). Projekt zakládá datová migrace na naplněné DB
 * a PortfolioSeeder z YAMLu na prázdné; obě cesty musí dát totéž.
 */
class Ond501VinarstviAntosTest extends TestCase
{
    use RefreshDatabase;

    private const MIGRATION = '2026_10_01_200000_ond501_vinarstvi_antos.php';
    private const SLUG = 'vinarstvi-antos';

    private const DETAIL = [
        'cs' => '/projekty/vinarstvi-antos',
        'en' => '/en/projects/vinarstvi-antos',
        'de' => '/de/projekte/vinarstvi-antos',
    ];

    /** Q8 z OND-494 + interní data klienta: nikde v textech projektu. */
    private const FORBIDDEN = ['WordPress', 'Shoptet', 'Elementor', 'od základu', 'vlastní kód', 'obrat', 'CTR'];

    private function migration(): object
    {
        return require database_path('migrations/' . self::MIGRATION);
    }

    /** Otisk projektu: vlastnosti, překlady, snímky s alt texty a štítky. */
    private function snapshot(): array
    {
        $project = PortfolioProject::where('slug', self::SLUG)
            ->with(['translations', 'screenshots.translations', 'tags'])
            ->firstOrFail();

        return [
            'project' => $project->only(['category', 'client_name', 'live_url', 'year', 'duration', 'featured', 'sort_order']),
            'published' => $project->published_at !== null,
            'translations' => $project->translations->sortBy('locale')
                ->map(fn ($t) => $t->only(['locale', 'title', 'subtitle', 'summary', 'description', 'challenge',
                    'solution', 'result', 'live_hint', 'meta_title', 'meta_description']))
                ->values()->all(),
            'screenshots' => $project->screenshots
                ->map(fn ($s) => [$s->sort_order, $s->type, $s->path,
                    $s->translations->sortBy('locale')->pluck('alt', 'locale')->all()])
                ->all(),
            'tags' => $project->tags->pluck('slug')->sort()->values()->all(),
        ];
    }

    public function test_migration_creates_the_same_project_as_the_seeder(): void
    {
        $this->seed(PortfolioSeeder::class);
        $fromYaml = $this->snapshot();

        // Stav produkce před nasazením: Antoš chybí (OND-503: dataset má 28).
        PortfolioProject::where('slug', self::SLUG)->delete();
        $this->assertSame(27, PortfolioProject::count());

        $this->migration()->up();

        $this->assertSame($fromYaml, $this->snapshot());
        $this->assertTrue($fromYaml['published']);
        $this->assertCount(3, $fromYaml['translations']);
        $this->assertCount(6, $fromYaml['screenshots']);
        $this->assertSame(['copywriting', 'logo', 'redesign', 'ubytovani', 'web'], $fromYaml['tags']);
        foreach ($fromYaml['screenshots'] as [, , $path, $alts]) {
            $this->assertFileExists(resource_path('img/' . $path));
            $this->assertSame(['cs', 'de', 'en'], array_keys(array_filter($alts)), $path);
        }
    }

    public function test_second_run_and_existing_project_are_left_alone(): void
    {
        $this->seed(PortfolioSeeder::class);
        DB::table('portfolio_project_translations')
            ->where('project_id', PortfolioProject::where('slug', self::SLUG)->value('id'))
            ->where('locale', 'cs')
            ->update(['subtitle' => 'Ručně upravený podtitulek']);

        $this->migration()->up();

        $this->assertSame(28, PortfolioProject::count());
        $this->assertDatabaseHas('portfolio_project_translations', ['subtitle' => 'Ručně upravený podtitulek']);
    }

    public function test_migration_is_inert_on_empty_database_and_down_removes_project(): void
    {
        $this->migration()->up();
        $this->assertSame(0, PortfolioProject::count());

        $this->seed(PortfolioSeeder::class);
        $this->migration()->down();

        $this->assertSame(27, PortfolioProject::count());
        $this->assertSame(0, DB::table('portfolio_project_screenshots')
            ->whereNotIn('project_id', DB::table('portfolio_projects')->pluck('id'))->count());
    }

    public function test_detail_renders_in_all_locales_with_client_review(): void
    {
        $this->seed(PortfolioSeeder::class);
        $project = PortfolioProject::where('slug', self::SLUG)->with(['translations', 'screenshots.translations'])->firstOrFail();

        foreach (self::DETAIL as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $t = $project->translation($locale);

            $this->assertSame(url($url), $project->detailUrl($locale));
            $this->assertStringContainsString(e($t->subtitle), $html, $url);
            // Hlavní snímek (alt nezávisí na tom, jestli je `public/build`).
            $this->assertStringContainsString(e($project->screenshots->firstWhere('type', 'hero')->translation($locale)->alt), $html, $url);
            // Recenze klienta u projektu (testimonials `project` → vinarstvi-antos).
            $this->assertStringContainsString('Roman Antoš', $html, $url);

            foreach (self::FORBIDDEN as $word) {
                foreach (['summary', 'description', 'challenge', 'solution', 'result', 'live_hint', 'meta_description'] as $field) {
                    $this->assertStringNotContainsStringIgnoringCase($word, (string) $t->{$field}, "{$locale}.{$field}");
                }
            }
        }
    }

    public function test_review_points_to_project_in_all_locales(): void
    {
        foreach (array_keys(self::DETAIL) as $locale) {
            $review = collect(trans('testimonials.items', [], $locale))->firstWhere('id', 'roman-antos');
            $this->assertSame(self::SLUG, $review['project'] ?? null, $locale);
        }
        $this->assertContains(self::SLUG, config('portfolio.sectors.sluzby'));
    }
}
