<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-438 — přechod projekt → detail a přednačtení (návrh 3 z OND-429).
 *
 * Přechod mezi stránkami se rozbije TIŠE: když na stránce stojí dvě stejná
 * `view-transition-name`, prohlížeč přechod vzdá bez chyby a stránka se jen
 * vymění naráz. Proto se tu prochází každá stránka s kartou projektu ve
 * všech jazycích a hlídá se, že jméno je na stránce jen jednou a že karta
 * a detail nesou stejné jméno (jinak se nespárují).
 */
class Ond438PageTransitionsTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_homepage_cards_carry_unique_names_matching_their_detail(): void
    {
        foreach (self::LOCALES as $locale) {
            $body = $this->get(lroute('home', $locale))->assertOk()->getContent();
            $names = $this->transitionNames($body);

            $this->assertUnique($names, "homepage ({$locale})");
            $this->assertContains('site-nav', $this->cssNames(), 'Horní lišta nemá vlastní jméno.');

            $slugs = $this->slugsLinkedFrom($body, $locale);
            $this->assertNotEmpty($slugs, "Homepage ({$locale}) nemá karty projektů.");
            foreach ($slugs as $slug) {
                $this->assertContains(project_transition_name($slug, 'img'), $names, "Obrázek karty {$slug} ({$locale}).");
                $this->assertContains(project_transition_name($slug, 'title'), $names, "Titulek karty {$slug} ({$locale}).");
            }
        }
    }

    public function test_projects_listing_names_every_card_once(): void
    {
        $published = PortfolioProject::published()->count();

        foreach (self::LOCALES as $locale) {
            $body = $this->get(lroute('projects', $locale))->assertOk()->getContent();
            $names = $this->transitionNames($body);

            $this->assertUnique($names, "/projekty ({$locale})");
            $this->assertCount($published, array_filter($names, fn ($n) => str_starts_with($n, 'project-img-')), "/projekty ({$locale}).");
            $this->assertCount($published, array_filter($names, fn ($n) => str_starts_with($n, 'project-title-')), "/projekty ({$locale}).");
        }
    }

    public function test_every_detail_names_its_head_and_nothing_else(): void
    {
        $projects = PortfolioProject::published()->get();
        $this->assertNotEmpty($projects);

        foreach ($projects as $project) {
            foreach (self::LOCALES as $locale) {
                $body = $this->get($project->detailUrl($locale))->assertOk()->getContent();
                $names = $this->transitionNames($body);
                $label = "{$project->slug} ({$locale})";

                $this->assertUnique($names, $label);
                $this->assertContains(project_transition_name($project->slug, 'title'), $names, "H1 {$label}.");

                // Karty „Další projekty" jméno nenesou — spárovaly by se
                // s kartami stránky, ze které člověk přišel.
                $foreign = array_filter($names, fn ($n) => ! str_ends_with($n, '-' . $project->slug));
                $this->assertSame([], array_values($foreign), "Cizí jména na detailu {$label}.");

                // Hlavní vizuál je jen tehdy, když má projekt široký snímek.
                if (str_contains($body, 'pd-gallery--lead')) {
                    $this->assertContains(project_transition_name($project->slug, 'img'), $names, "Hlavní vizuál {$label}.");
                }
            }
        }
    }

    public function test_speculation_rules_prerender_only_own_pages_without_query(): void
    {
        foreach (self::LOCALES as $locale) {
            $body = $this->get(lroute('projects', $locale))->assertOk()->getContent();

            $this->assertSame(1, preg_match_all('/<script type="speculationrules">(.*?)<\/script>/s', $body, $m), "Jedna sada pravidel ({$locale}).");
            $rules = json_decode($m[1][0], true, flags: JSON_THROW_ON_ERROR);

            $this->assertSame(['prerender'], array_keys($rules));
            $rule = $rules['prerender'][0];
            $this->assertSame('moderate', $rule['eagerness']);

            [$allow, $deny] = $rule['where']['and'];
            $patterns = array_column($allow['or'], 'href_matches');
            $paths = array_column($patterns, 'pathname');

            foreach ($patterns as $pattern) {
                $this->assertSame('', $pattern['search'], "Vzor {$pattern['pathname']} musí vyloučit `?` ({$locale}).");
                $this->assertStringStartsWith('/', $pattern['pathname'], 'Jen vlastní cesty.');
            }

            $this->assertContains(parse_url(lroute('projects', $locale), PHP_URL_PATH) . '/*', $paths, "Detail projektu ({$locale}).");
            foreach (['cookies', 'privacy', 'home'] as $excluded) {
                $this->assertNotContains(parse_url(lroute($excluded, $locale), PHP_URL_PATH) ?: '/', $paths, "{$excluded} se nesmí přednačítat ({$locale}).");
            }
            $this->assertStringContainsString('[hreflang]', $deny['not']['selector_matches'], 'Přepínač jazyka.');
        }
    }

    public function test_detail_url_is_matched_by_the_detail_pattern(): void
    {
        $project = PortfolioProject::published()->firstOrFail();

        foreach (self::LOCALES as $locale) {
            $path = parse_url($project->detailUrl($locale), PHP_URL_PATH);
            $this->assertStringStartsWith(parse_url(lroute('projects', $locale), PHP_URL_PATH) . '/', $path, "Detail {$locale} leží pod výpisem.");
        }
    }

    /** Jména z `style` atributů vykreslené stránky. */
    private function transitionNames(string $body): array
    {
        preg_match_all('/view-transition-name:\s*([a-z0-9-]+)/', $body, $m);

        return $m[1];
    }

    /** Jména, která dává CSS (lišty) — ve zdroji, build se v testech nedělá. */
    private function cssNames(): array
    {
        preg_match_all('/view-transition-name:\s*([a-z0-9-]+)/', file_get_contents(resource_path('css/prechody.css')), $m);

        return $m[1];
    }

    private function assertUnique(array $names, string $label): void
    {
        $dupes = array_keys(array_filter(array_count_values($names), fn ($c) => $c > 1));
        $this->assertSame([], $dupes, "Duplicitní view-transition-name na {$label}: přechod by se tiše nekonal.");
    }

    /** Slugy projektů, na jejichž detail stránka odkazuje (URL detailu je lokalizovaná, slug ne). */
    private function slugsLinkedFrom(string $body, string $locale): array
    {
        return PortfolioProject::published()->get()
            ->filter(fn ($p) => str_contains($body, 'href="' . $p->detailUrl($locale) . '"'))
            ->pluck('slug')
            ->all();
    }
}
