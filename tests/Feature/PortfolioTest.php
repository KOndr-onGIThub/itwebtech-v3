<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Naplníme DB skutečnými projekty z docs/portfolio-data.yaml.
        // Seeder je idempotentní a běží i bez stažených screenshotů.
        $this->seed(PortfolioSeeder::class);
    }

    public function test_projects_listing_renders_all_published_projects(): void
    {
        $expectedCount = PortfolioProject::published()->count();

        $this->assertSame(23, $expectedCount, 'Seeder by měl vytvořit 23 publikovaných projektů.');

        $response = $this->get('/projekty');

        $response->assertOk();

        // Každý projekt má v gridu vlastní kartu s data-category atributem.
        $body = $response->getContent();
        $cardCount = substr_count($body, 'class="portfolio-card"');

        $this->assertSame(
            $expectedCount,
            $cardCount,
            "Listing měl vykreslit {$expectedCount} portfolio karet, vykreslil {$cardCount}."
        );
    }

    public function test_project_detail_returns_200_for_existing_slug(): void
    {
        $project = PortfolioProject::published()->first();

        $this->assertNotNull($project, 'Pro test je potřeba alespoň jeden publikovaný projekt.');

        $response = $this->get('/projekty/'.$project->slug);

        $response->assertOk();
    }

    public function test_project_detail_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/projekty/tento-projekt-neexistuje-12345');

        $response->assertNotFound();
    }

    public function test_project_detail_emits_hreflang_for_each_locale(): void
    {
        $project = PortfolioProject::published()->first();
        $slug = $project->slug;

        $response = $this->get('/projekty/'.$slug);

        $response->assertOk();

        // Slug je jazyk-neutrální → stejný slug v každé jazykové variantě URL.
        $response->assertSee('hreflang="cs"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="de"', false);
        $response->assertSee('/projekty/'.$slug, false);
        $response->assertSee('/en/projects/'.$slug, false);
        $response->assertSee('/de/projekte/'.$slug, false);
    }

    public function test_project_listing_available_in_all_locales(): void
    {
        $this->get('/projekty')->assertOk();
        $this->get('/en/projects')->assertOk();
        $this->get('/de/projekte')->assertOk();
    }

    public function test_deleting_project_cascades_to_all_children(): void
    {
        // Najdi projekt, který má co nejvíc potomků (zaručí pokrytí všech tabulek).
        $project = PortfolioProject::query()
            ->withCount(['translations', 'screenshots', 'outcomes', 'tags'])
            ->orderByDesc('translations_count')
            ->orderByDesc('screenshots_count')
            ->orderByDesc('outcomes_count')
            ->orderByDesc('tags_count')
            ->first();

        $this->assertNotNull($project, 'Seeder musí vytvořit alespoň jeden projekt.');
        $this->assertGreaterThan(0, $project->translations()->count(), 'Projekt potřebuje překlady pro test cascade.');

        $projectId = $project->id;
        $screenshotIds = $project->screenshots()->pluck('id')->all();
        $outcomeIds = $project->outcomes()->pluck('id')->all();
        $slug = $project->slug;

        // Delete přes model (Filament DeleteAction volá totéž).
        $project->delete();

        // 1) Projekt fyzicky pryč.
        $this->assertSame(0, PortfolioProject::where('id', $projectId)->count());

        // 2) Přímí potomci pryč (FK cascadeOnDelete).
        $this->assertSame(0, DB::table('portfolio_project_translations')->where('project_id', $projectId)->count());
        $this->assertSame(0, DB::table('portfolio_project_screenshots')->where('project_id', $projectId)->count());
        $this->assertSame(0, DB::table('portfolio_project_outcomes')->where('project_id', $projectId)->count());
        $this->assertSame(0, DB::table('portfolio_project_tag')->where('project_id', $projectId)->count());

        // 3) Vnoučata (translations dětí) pryč.
        if ($screenshotIds) {
            $this->assertSame(
                0,
                DB::table('portfolio_project_screenshot_translations')
                    ->whereIn('screenshot_id', $screenshotIds)
                    ->count()
            );
        }
        if ($outcomeIds) {
            $this->assertSame(
                0,
                DB::table('portfolio_project_outcome_translations')
                    ->whereIn('outcome_id', $outcomeIds)
                    ->count()
            );
        }

        // 4) Public detail vrací 404.
        $this->get('/projekty/'.$slug)->assertNotFound();
    }
}
