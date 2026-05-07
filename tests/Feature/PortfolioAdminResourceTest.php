<?php

namespace Tests\Feature;

use App\Filament\Resources\PortfolioProjectResource;
use App\Filament\Resources\PortfolioTagResource;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Portfolio\PortfolioTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioAdminResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_translations_persist_for_all_locales(): void
    {
        $tag = PortfolioTag::create(['slug' => 'web', 'sort_order' => 0]);

        PortfolioTagResource::persistTranslations($tag, [
            'translations' => [
                'cs' => ['name' => 'Web'],
                'en' => ['name' => 'Web'],
                'de' => ['name' => 'Web'],
            ],
        ]);

        $tag->refresh()->load('translations');

        $this->assertCount(3, $tag->translations);
        $this->assertSame('Web', $tag->translation('en')?->name);
    }

    public function test_tag_persistence_removes_empty_optional_locales(): void
    {
        $tag = PortfolioTag::create(['slug' => 'app', 'sort_order' => 0]);

        // Nejdřív vyplníme všechny.
        PortfolioTagResource::persistTranslations($tag, [
            'translations' => [
                'cs' => ['name' => 'Aplikace'],
                'en' => ['name' => 'App'],
                'de' => ['name' => 'App'],
            ],
        ]);

        // Pak EN/DE smažeme (uložíme prázdné).
        PortfolioTagResource::persistTranslations($tag, [
            'translations' => [
                'cs' => ['name' => 'Aplikace'],
                'en' => ['name' => null],
                'de' => ['name' => ''],
            ],
        ]);

        $tag->refresh()->load('translations');
        $this->assertCount(1, $tag->translations);
        $this->assertSame('cs', $tag->translations->first()->locale);
    }

    public function test_project_split_moves_translations_outcomes_screenshots_aside(): void
    {
        $data = [
            'slug'         => 'demo',
            'category'     => 'website',
            'is_published' => false,
            'translations' => ['cs' => ['title' => 'Demo']],
            'outcomes'     => [['key' => 'a', 'translations' => []]],
            'screenshots'  => [],
        ];

        [$project, $side] = PortfolioProjectResource::splitFormData($data);

        $this->assertArrayNotHasKey('translations', $project);
        $this->assertArrayNotHasKey('outcomes', $project);
        $this->assertArrayNotHasKey('screenshots', $project);
        $this->assertArrayNotHasKey('is_published', $project);
        $this->assertNull($project['published_at']);

        $this->assertSame(['cs' => ['title' => 'Demo']], $side['translations']);
        $this->assertCount(1, $side['outcomes']);
    }

    public function test_publish_without_hero_screenshot_throws(): void
    {
        $this->expectException(\Filament\Support\Exceptions\Halt::class);

        PortfolioProjectResource::splitFormData([
            'slug'         => 'demo',
            'category'     => 'website',
            'is_published' => true,
            'translations' => ['cs' => ['title' => 'Demo']],
            'outcomes'     => [],
            'screenshots'  => [
                ['type' => 'gallery', 'path' => 'portfolio/demo/x.jpg'],
            ],
        ]);
    }

    public function test_publish_with_hero_screenshot_passes_validation(): void
    {
        [$project, $side] = PortfolioProjectResource::splitFormData([
            'slug'         => 'demo',
            'category'     => 'website',
            'is_published' => true,
            'translations' => ['cs' => ['title' => 'Demo']],
            'outcomes'     => [],
            'screenshots'  => [
                ['type' => 'hero', 'path' => 'portfolio/demo/hero.jpg'],
            ],
        ]);

        $this->assertNotNull($project['published_at']);
        $this->assertTrue($side['is_published']);
    }

    public function test_apply_side_effects_persists_full_project_tree(): void
    {
        $project = PortfolioProject::create([
            'slug'        => 'demo-tree',
            'category'    => 'website',
            'client_name' => 'Demo',
        ]);

        PortfolioProjectResource::applySideEffects($project, [
            'is_published' => false,
            'translations' => [
                'cs' => ['title' => 'Demo cs'],
                'en' => ['title' => 'Demo en'],
                'de' => ['title' => null],
            ],
            'outcomes' => [
                [
                    'key'          => 'conv',
                    'translations' => [
                        'cs' => ['label' => 'Konverze', 'value' => '+25 %', 'description' => null],
                        'en' => ['label' => 'Conversion', 'value' => '+25 %', 'description' => null],
                        'de' => ['label' => null, 'value' => null, 'description' => null],
                    ],
                ],
            ],
            'screenshots' => [
                [
                    'type'         => 'hero',
                    'path'         => 'portfolio/demo-tree/hero.jpg',
                    'translations' => [
                        'cs' => ['alt' => 'Hero cs', 'caption' => 'Cap cs'],
                        'en' => ['alt' => null, 'caption' => null],
                        'de' => ['alt' => null, 'caption' => null],
                    ],
                ],
            ],
        ]);

        $project->refresh()->load(['translations', 'outcomes.translations', 'screenshots.translations']);

        $this->assertCount(2, $project->translations); // CS + EN, DE smazán
        $this->assertCount(1, $project->outcomes);
        $this->assertCount(2, $project->outcomes->first()->translations); // CS + EN
        $this->assertCount(1, $project->screenshots);
        $this->assertSame('hero', $project->screenshots->first()->type);
        $this->assertCount(1, $project->screenshots->first()->translations); // jen CS
    }

    public function test_persisting_outcomes_removes_dropped_records(): void
    {
        $project = PortfolioProject::create([
            'slug'        => 'demo-outcomes',
            'category'    => 'website',
            'client_name' => 'Demo',
        ]);

        // První save — dva outcomes
        PortfolioProjectResource::persistOutcomes($project, [
            ['key' => 'a', 'translations' => ['cs' => ['label' => 'A', 'value' => '1']]],
            ['key' => 'b', 'translations' => ['cs' => ['label' => 'B', 'value' => '2']]],
        ]);

        $project->refresh();
        $this->assertCount(2, $project->outcomes);
        $first = $project->outcomes->first();

        // Druhý save — jen první (s id), druhý je odstraněn.
        PortfolioProjectResource::persistOutcomes($project, [
            ['id' => $first->id, 'key' => 'a', 'translations' => ['cs' => ['label' => 'A', 'value' => '1']]],
        ]);

        $project->refresh();
        $this->assertCount(1, $project->outcomes);
    }

    public function test_fill_form_data_loads_existing_project(): void
    {
        $project = PortfolioProject::create([
            'slug'         => 'demo-fill',
            'category'     => 'website',
            'client_name'  => 'Demo',
            'published_at' => now()->subMinute(),
        ]);

        PortfolioProjectResource::applySideEffects($project, [
            'is_published' => true,
            'translations' => ['cs' => ['title' => 'Demo']],
            'outcomes'     => [],
            'screenshots'  => [],
        ]);

        $data = PortfolioProjectResource::fillFormData($project->fresh(), []);

        $this->assertTrue($data['is_published']);
        $this->assertSame('Demo', $data['translations']['cs']['title']);
        $this->assertSame([], $data['outcomes']);
        $this->assertSame([], $data['screenshots']);
    }
}
