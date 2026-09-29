<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-471 — prototypy přehledu /projekty (`?v=1|2|3`).
 *
 * Každá varianta musí mít všechny publikované projekty jako normální
 * odkazy v HTML (SEO, bez JS), v cs/en/de, a jména přechodů (OND-438)
 * nesmí být na stránce dvakrát — jinak prohlížeč přechod tiše vzdá.
 */
class Ond471ProjectsVariantsTest extends TestCase
{
    use RefreshDatabase;

    private const PATHS = ['cs' => '/projekty', 'en' => '/en/projects', 'de' => '/de/projekte'];

    private const LINK_CLASS = [1 => 'pd-work__link', 2 => 'pd-index__link', 3 => 'pd-show__link'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_every_variant_links_every_published_project_in_all_locales(): void
    {
        $published = PortfolioProject::published()->count();

        foreach (self::PATHS as $locale => $path) {
            foreach (self::LINK_CLASS as $v => $class) {
                $body = $this->get("{$path}?v={$v}")->assertOk()->getContent();

                preg_match_all('/<a href="([^"]+)" class="' . $class . '"/', $body, $m);

                $this->assertCount($published, array_unique($m[1]), "v{$v} {$locale}: počet odkazů na detail.");
                $this->assertStringContainsString('<link rel="canonical" href="http://localhost' . $path . '">', $body, "v{$v} {$locale}: canonical bez ?v.");
            }
        }
    }

    public function test_transition_names_are_unique_per_variant(): void
    {
        foreach (array_keys(self::LINK_CLASS) as $v) {
            $body = $this->get("/projekty?v={$v}")->assertOk()->getContent();

            preg_match_all('/view-transition-name:\s*(project-[a-z0-9-]+)/', $body, $m);

            $this->assertNotEmpty($m[1], "v{$v}: žádná jména přechodů.");
            $this->assertSame(array_unique($m[1]), $m[1], "v{$v}: duplicitní jméno přechodu.");
        }
    }

    public function test_without_parameter_page_keeps_original_filter(): void
    {
        $body = $this->get('/projekty')->assertOk()->getContent();

        $this->assertStringContainsString('class="pd-filter"', $body);
        $this->assertStringNotContainsString('data-intent', $body);
        $this->assertStringNotContainsString('data-index', $body);
        $this->assertStringNotContainsString('data-show', $body);
    }
}
