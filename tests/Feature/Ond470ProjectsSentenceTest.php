<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-470 — přehled /projekty: věta „Potřebuju [web] pro [obor].“ místo
 * záložek (vítězná varianta V1 z OND-471) a čtyři odpublikované projekty.
 *
 * Věta je jen ovládání nad mřížkou: všechny publikované projekty musí
 * zůstat v HTML jako normální odkazy (SEO, bez JS), každá karta nese obor
 * a nabídnou se jen volby, za kterými je aspoň jeden projekt.
 */
class Ond470ProjectsSentenceTest extends TestCase
{
    use RefreshDatabase;

    private const PATHS = ['cs' => '/projekty', 'en' => '/en/projects', 'de' => '/de/projekte'];

    private const UNPUBLISHED = ['pitarena-cedule', 'clanek-motorkari-cz', 'elektro-srnak', 'yolk'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_sentence_renders_above_full_grid_in_all_locales(): void
    {
        $published = PortfolioProject::published()->count();

        foreach (self::PATHS as $locale => $path) {
            $body = $this->get($path)->assertOk()->getContent();

            $this->assertStringContainsString('<form class="pd-intent" data-intent', $body, "Věta {$locale}.");
            $this->assertStringContainsString('<select id="intent-co" name="co"', $body, "Výběr „co“ {$locale}.");
            $this->assertStringContainsString('<select id="intent-pro" name="pro"', $body, "Výběr „pro“ {$locale}.");
            $count = str_replace(':n', $published, __('projects.catalog.count', [], $locale)['other']);
            $this->assertStringContainsString('role="status" data-intent-status>' . $count . '</span>', $body, "Počet {$locale}.");
            $this->assertStringContainsString(__('projects.catalog.sentence.what.all', [], $locale), $body, "Výchozí znění {$locale}.");

            preg_match_all('/<a href="([^"]+)" class="pd-work__link"/', $body, $m);
            $this->assertCount($published, array_unique($m[1]), "Všechny projekty jako odkazy ({$locale}).");

            // Záložky ani prototypy OND-471 na stránce nezůstaly.
            $this->assertStringNotContainsString('pd-filter', $body);
            $this->assertStringNotContainsString('portfolioFilter', $body);
            $this->assertStringNotContainsString('data-index', $body);
            $this->assertStringNotContainsString('data-show', $body);
        }
    }

    public function test_options_offer_only_what_has_published_projects(): void
    {
        $body = $this->get('/projekty')->assertOk()->getContent();

        preg_match('/<select id="intent-co".*?<\/select>/s', $body, $co);
        preg_match_all('/<option value="([a-z]+)"/', $co[0], $what);
        $categories = PortfolioProject::published()->distinct()->pluck('category')->all();
        $this->assertEqualsCanonicalizing(['all', ...$categories], $what[1]);
        $this->assertNotContains('other', $what[1], 'Po odpublikování nezbyl žádný projekt „Ostatní“.');

        preg_match('/<select id="intent-pro".*?<\/select>/s', $body, $pro);
        preg_match_all('/<option value="([a-z]+)"/', $pro[0], $for);
        $this->assertSame('all', $for[1][0]);

        // Každý nabídnutý obor má aspoň jednu kartu, každá karta nese obory.
        preg_match_all('/<article class="pd-work" data-category="[a-z]+" data-sectors="([a-z ]*)"/', $body, $cards);
        $this->assertCount(PortfolioProject::published()->count(), $cards[1]);
        $onCards = array_unique(array_merge(...array_map(fn ($s) => array_filter(explode(' ', $s)), $cards[1])));
        foreach (array_slice($for[1], 1) as $sector) {
            $this->assertContains($sector, $onCards, "Obor {$sector} bez jediné karty.");
        }
    }

    public function test_query_state_does_not_change_canonical_or_markup(): void
    {
        $plain = $this->get('/projekty')->assertOk()->getContent();
        $body = $this->get('/projekty?co=application&pro=vyroba')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="http://localhost/projekty">', $body);
        // Výběr z URL obnovuje až skript — HTML je pro vyhledávače stejné.
        preg_match_all('/<a href="([^"]+)" class="pd-work__link"/', $plain, $a);
        preg_match_all('/<a href="([^"]+)" class="pd-work__link"/', $body, $b);
        $this->assertSame($a[1], $b[1]);
    }

    public function test_four_projects_are_unpublished_everywhere(): void
    {
        $this->assertSame(17, PortfolioProject::published()->count());

        foreach (self::UNPUBLISHED as $slug) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();
            $this->assertNull($project->published_at, "{$slug} má být neveřejný.");

            // Detail už nic nezobrazí; adresa, kterou zná Google, jde
            // dočasně (302) na PitArenu nebo na výpis — ne na 404.
            foreach (array_keys(self::PATHS) as $locale) {
                $response = $this->get($project->detailUrl($locale))->assertStatus(302);
                $this->get($response->headers->get('Location'))->assertOk();
            }
        }

        $body = $this->get('/projekty')->assertOk()->getContent();
        foreach (self::UNPUBLISHED as $slug) {
            $this->assertStringNotContainsString('/projekty/' . $slug . '"', $body);
        }
    }

    public function test_related_projects_on_detail_keep_shared_card(): void
    {
        $project = PortfolioProject::published()->where('slug', 'pitarena')->firstOrFail();
        $body = $this->get($project->detailUrl('cs'))->assertOk()->getContent();

        // „Další projekty“ používají tutéž kartu bez věty a bez oborů.
        $this->assertMatchesRegularExpression('/<article class="pd-work" data-category="[a-z]+" >/', $body);
        $this->assertStringNotContainsString('data-intent', $body);
    }
}
