<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-400 — /projekty ve slovníku nové homepage (předloha OND-399).
 *
 * Každá kartička mřížky musí vést na svůj detail a detail musí vrátit 200,
 * ve všech třech jazycích. Tohle se na webu už jednou tiše rozbilo (OND-352).
 */
class Ond400ProjectsPageTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = [
        'cs' => ['/projekty', 'Projekty — Ondřej Kriška, ONDRAWEB'],
        'en' => ['/en/projects', 'Projects — Ondřej Kriška, ONDRAWEB'],
        'de' => ['/de/projekte', 'Projekte — Ondřej Kriška, ONDRAWEB'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_every_card_links_to_its_detail_and_detail_returns_200_in_all_locales(): void
    {
        $published = PortfolioProject::published()->count();

        foreach (self::PAGES as $locale => [$path, $title]) {
            $body = $this->get($path)->assertOk()->getContent();

            $this->assertStringContainsString("<title>{$title}</title>", $body, "Titulek {$locale}.");

            preg_match_all('/<a href="([^"]+)" class="pd-work__link"/', $body, $m);
            $hrefs = $m[1];

            $this->assertCount($published, $hrefs, "Počet kartiček {$locale}.");
            $this->assertCount($published, array_unique($hrefs), "Dvě kartičky vedou na tentýž detail ({$locale}).");

            foreach ($hrefs as $href) {
                $this->get($href)->assertOk();
            }
        }
    }

    public function test_filter_counts_match_rendered_cards(): void
    {
        $body = $this->get('/projekty')->assertOk()->getContent();

        preg_match_all('/<article class="pd-work" data-category="([a-z]+)"/', $body, $m);
        $byCategory = array_count_values($m[1]);

        preg_match_all('/data-category="([a-z]+)"\s*>\s*<span class="pd-filter__label">[^<]*<\/span>\s*<span class="pd-filter__count">(\d+)<\/span>/', $body, $f, PREG_SET_ORDER);
        $this->assertNotEmpty($f, 'Filtr se nevykreslil.');

        foreach ($f as [, $category, $count]) {
            $expected = $category === 'all' ? count($m[1]) : ($byCategory[$category] ?? 0);
            $this->assertSame($expected, (int) $count, "Počet ve filtru „{$category}\".");
            $this->assertGreaterThan(0, (int) $count, "Kategorie s nulou se nemá vykreslit ({$category}).");
        }
    }
}
