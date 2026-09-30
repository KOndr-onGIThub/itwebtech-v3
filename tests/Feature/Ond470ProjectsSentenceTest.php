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

    /**
     * OND-478 — nápověda pro první návštěvu je jen bublina (varianta A):
     * skrytá do spuštění z projekty.js, čtečce nic navíc. Prototypové
     * varianty B/C a přepínač `?napoveda` na stránce nezůstaly.
     * OND-477 — zvýrazněná část H1 na /projekty je bez podtržení
     * (`pd-heading--plain`): podtržení tu znamená jen „dá se kliknout“.
     */
    public function test_first_visit_hint_is_single_bubble_and_heading_is_plain(): void
    {
        foreach (self::PATHS as $locale => $path) {
            $body = $this->get($path . '?napoveda=b')->assertOk()->getContent();
            $bubble = '<span class="pd-intent__bubble" data-intent-hint aria-hidden="true" hidden>'
                . e(__('projects.catalog.sentence.hint', [], $locale)) . '</span>';

            $this->assertSame(1, substr_count($body, 'data-intent-hint'), "Jediná nápověda ({$locale}).");
            $this->assertStringContainsString($bubble, $body, "Bublina {$locale}.");
            $this->assertLessThanOrEqual(35, mb_strlen(__('projects.catalog.sentence.hint', [], $locale)), "Text bubliny {$locale} se musí vejít na 320 px.");
            $this->assertStringNotContainsString('pd-intent__line', $body);
            $this->assertStringNotContainsString('pd-intent__hand', $body);
            $this->assertStringNotContainsString('data-hint=', $body);
            $this->assertStringContainsString('<h1 class="pd-heading pd-heading--sub pd-heading--plain">', $body, "H1 bez podtržení {$locale}.");
        }

        // Jinde se H1 nemění.
        $this->assertStringNotContainsString('pd-heading--plain', $this->get('/cenik')->assertOk()->getContent());
    }

    /**
     * OND-482/483 — na počítači kreslí seznam JS (ARIA combobox), zdrojem
     * pravdy zůstávají oba `<select>`. Combobox i listbox čtou popisek přes
     * `aria-labelledby`, proto popisky nesou id rovnou v HTML. Bublina je
     * v HTML skrytá, spouští ji až skript.
     */
    public function test_selects_carry_labels_with_ids_and_bubble(): void
    {
        foreach (self::PATHS as $locale => $path) {
            $body = $this->get($path)->assertOk()->getContent();

            foreach (['co' => 'what_label', 'pro' => 'for_label'] as $name => $key) {
                $label = '<label class="sr-only" id="intent-' . $name . '-label" for="intent-' . $name . '">'
                    . e(__('projects.catalog.sentence.' . $key, [], $locale)) . '</label>';
                $this->assertSame(1, substr_count($body, 'id="intent-' . $name . '-label"'), "Jedno id popisku {$name} ({$locale}).");
                $this->assertStringContainsString($label, $body, "Popisek {$name} ({$locale}).");
                $this->assertMatchesRegularExpression(
                    '/<select id="intent-' . $name . '" name="' . $name . '" class="pd-intent__select"[^>]*>\s*<option value="all">/',
                    $body,
                    "Select {$name} ({$locale})."
                );
            }

            $this->assertStringContainsString('<span class="pd-intent__bubble" data-intent-hint aria-hidden="true" hidden>', $body, "Bublina {$locale}.");
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
