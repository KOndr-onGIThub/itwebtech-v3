<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-503 — případovky ZOOMORAVA (OND-498), MAKOplast (OND-499) a ExHot
 * (OND-500). Projekty zakládá jedna datová migrace na naplněné DB
 * a PortfolioSeeder z YAMLu na prázdné; obě cesty musí dát totéž.
 */
class Ond503CaseStudiesTest extends TestCase
{
    use RefreshDatabase;

    private const MIGRATION = '2026_10_01_210000_ond503_exhot_zoomorava_makoplast.php';

    /** slug => [recenze, obor, počet snímků, štítky] */
    private const PROJECTS = [
        'zoomorava' => ['marie-mikova', 'vyroba', 5, ['b2b', 'copywriting', 'galerie', 'logistika', 'seo', 'vicejazycny', 'web']],
        'makoplast' => ['radka-lanikova-ourednikova', 'vyroba', 4, ['b2b', 'copywriting', 'logo', 'pdf', 'prumysl', 'seo', 'vicejazycny', 'vyroba', 'web']],
        'exhot'     => ['ales-horky', 'remeslo', 5, ['copywriting', 'galerie', 'logo', 'remeslo', 'vicejazycny', 'web']],
    ];

    private const DETAIL = [
        'cs' => '/projekty/',
        'en' => '/en/projects/',
        'de' => '/de/projekte/',
    ];

    /** Zadání OND-503: ceny ani čísla ze Search Console nikde. */
    private const FORBIDDEN_ALL = ['Kč', '€', 'proklik', 'clicks', 'Klicks', 'impressions', 'Impressionen', 'Search Console'];

    /** ExHot stojí na koupené šabloně; MAKOplast nechce na webu výrobu v zahraničí ani obchodní čísla. */
    private const FORBIDDEN = [
        'exhot'     => ['od základu', 'vlastní kód', 'from scratch', 'von Grund auf'],
        'makoplast' => ['Polsk', 'Slovensk', 'Víd', 'Poland', 'Slovakia', 'Vienna', 'Polen', 'Slowakei', 'Wien', 'odběratel', 'obrat'],
        'zoomorava' => [],
    ];

    private const TEXT_FIELDS = ['title', 'subtitle', 'summary', 'description', 'challenge', 'solution', 'result',
        'live_hint', 'meta_title', 'meta_description'];

    private function migration(): object
    {
        return require database_path('migrations/' . self::MIGRATION);
    }

    /** Otisk projektu: vlastnosti, překlady, snímky s alt texty a štítky. */
    private function snapshot(string $slug): array
    {
        $project = PortfolioProject::where('slug', $slug)
            ->with(['translations', 'screenshots.translations', 'tags'])
            ->firstOrFail();

        return [
            'project' => $project->only(['category', 'client_name', 'live_url', 'year', 'duration', 'featured', 'sort_order']),
            'published' => $project->published_at !== null,
            'translations' => $project->translations->sortBy('locale')
                ->map(fn ($t) => $t->only(array_merge(['locale'], self::TEXT_FIELDS)))
                ->values()->all(),
            'screenshots' => $project->screenshots
                ->map(fn ($s) => [$s->sort_order, $s->type, $s->path,
                    $s->translations->sortBy('locale')->pluck('alt', 'locale')->all()])
                ->all(),
            'tags' => $project->tags->pluck('slug')->sort()->values()->all(),
        ];
    }

    public function test_migration_creates_the_same_projects_as_the_seeder(): void
    {
        $this->seed(PortfolioSeeder::class);
        $fromYaml = array_map(fn ($slug) => $this->snapshot($slug), array_combine(array_keys(self::PROJECTS), array_keys(self::PROJECTS)));

        // Stav produkce před nasazením: 25 projektů, tři nové chybí.
        PortfolioProject::whereIn('slug', array_keys(self::PROJECTS))->delete();
        $this->assertSame(25, PortfolioProject::count());

        $this->migration()->up();

        foreach (self::PROJECTS as $slug => [, , $shots, $tags]) {
            $this->assertSame($fromYaml[$slug], $this->snapshot($slug), $slug);
            $this->assertTrue($fromYaml[$slug]['published'], $slug);
            $this->assertCount(3, $fromYaml[$slug]['translations'], $slug);
            $this->assertCount($shots, $fromYaml[$slug]['screenshots'], $slug);
            $this->assertSame($tags, $fromYaml[$slug]['tags'], $slug);
            $this->assertSame('hero', $fromYaml[$slug]['screenshots'][0][1], $slug);
            foreach ($fromYaml[$slug]['screenshots'] as [, , $path, $alts]) {
                $this->assertFileExists(resource_path('img/' . $path));
                $this->assertSame(['cs', 'de', 'en'], array_keys(array_filter($alts)), $path);
            }
        }
    }

    public function test_second_run_and_existing_project_are_left_alone(): void
    {
        $this->seed(PortfolioSeeder::class);
        DB::table('portfolio_project_translations')
            ->where('project_id', PortfolioProject::where('slug', 'exhot')->value('id'))
            ->where('locale', 'cs')
            ->update(['subtitle' => 'Ručně upravený podtitulek']);
        PortfolioProject::where('slug', 'makoplast')->delete();

        $this->migration()->up();

        // ExHot zůstal ručně upravený, chybějící MAKOplast doplněný.
        $this->assertSame(28, PortfolioProject::count());
        $this->assertDatabaseHas('portfolio_project_translations', ['subtitle' => 'Ručně upravený podtitulek']);
        $this->assertTrue(PortfolioProject::where('slug', 'makoplast')->exists());
    }

    public function test_migration_is_inert_on_empty_database_and_down_removes_projects(): void
    {
        $this->migration()->up();
        $this->assertSame(0, PortfolioProject::count());

        $this->seed(PortfolioSeeder::class);
        $this->migration()->down();

        $this->assertSame(25, PortfolioProject::count());
        $this->assertSame(0, DB::table('portfolio_project_screenshots')
            ->whereNotIn('project_id', DB::table('portfolio_projects')->pluck('id'))->count());
    }

    public function test_details_render_in_all_locales_with_client_review(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PROJECTS as $slug => [$reviewId]) {
            $project = PortfolioProject::where('slug', $slug)->with(['translations', 'screenshots.translations'])->firstOrFail();

            foreach (self::DETAIL as $locale => $prefix) {
                $url = $prefix . $slug;
                $html = $this->get($url)->assertOk()->getContent();
                $t = $project->translation($locale);

                $this->assertSame(url($url), $project->detailUrl($locale));
                $this->assertStringContainsString(e($t->subtitle), $html, $url);
                // Hlavní snímek (alt nezávisí na tom, jestli je `public/build`).
                $this->assertStringContainsString(e($project->screenshots->firstWhere('type', 'hero')->translation($locale)->alt), $html, $url);
                // Recenze klienta u projektu (testimonials `project` → slug).
                $review = collect(trans('testimonials.items', [], $locale))->firstWhere('id', $reviewId);
                $this->assertStringContainsString(e($review['name']), $html, $url);

                foreach (array_merge(self::FORBIDDEN_ALL, self::FORBIDDEN[$slug]) as $word) {
                    foreach (self::TEXT_FIELDS as $field) {
                        $this->assertStringNotContainsStringIgnoringCase($word, (string) $t->{$field}, "{$slug}.{$locale}.{$field}");
                    }
                }
            }
        }
    }

    public function test_listing_shows_all_three_projects(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::DETAIL as $locale => $prefix) {
            $html = $this->get(rtrim($prefix, '/'))->assertOk()->getContent();
            foreach (array_keys(self::PROJECTS) as $slug) {
                $this->assertStringContainsString(url($prefix . $slug), $html, "{$locale} {$slug}");
            }
        }
    }

    public function test_reviews_and_sectors_point_to_projects(): void
    {
        foreach (self::PROJECTS as $slug => [$reviewId, $sector]) {
            foreach (array_keys(self::DETAIL) as $locale) {
                $review = collect(trans('testimonials.items', [], $locale))->firstWhere('id', $reviewId);
                $this->assertSame($slug, $review['project'] ?? null, "{$locale} {$reviewId}");
            }
            $this->assertContains($slug, config("portfolio.sectors.{$sector}"), $slug);
        }
    }
}
