<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-457 (B-11 z OND-441) — pod větou „Nejčastěji mi píšou lidi, kterým se
 * daří…“ (sekce 02 homepage) stojí tři skutečné příklady z portfolia. Každý
 * vede na případovku s výsledkem a recenzí.
 *
 * Test hlídá obě strany jako OND-359 u ceníku: odkazy stojí a míří na
 * lokalizovaný detail, a nepublikovaný projekt se nevykreslí. Pod dva
 * příklady zmizí celý seznam.
 */
class Ond457SituationCasesTest extends TestCase
{
    use RefreshDatabase;

    /** Homepage a prefix detailu projektu pro každou locale. */
    private const PATHS = [
        'cs' => ['home' => '/',    'project' => '/projekty'],
        'en' => ['home' => '/en/', 'project' => '/en/projects'],
        'de' => ['home' => '/de/', 'project' => '/de/projekte'],
    ];

    // OND-490 (bod 8): VP Industry z pruhu pryč, místo něj Zubní Provázek.
    private const SLUGS = ['cyklocentrum', 'zubni-provazek', 'kemp-veselka'];

    /** Vykreslená sekce 02 (od `pd-situation` po konec sekce). */
    private function situationSection(string $body): string
    {
        $this->assertMatchesRegularExpression('~<section class="pd-section pd-situation">.*?</section>~s', $body,
            'Na homepage chybí sekce 02 (`pd-situation`).');
        preg_match('~<section class="pd-section pd-situation">.*?</section>~s', $body, $m);

        return $m[0];
    }

    private function unpublish(string ...$slugs): void
    {
        PortfolioProject::whereIn('slug', $slugs)->update(['published_at' => null]);
    }

    public function test_three_cases_link_to_localized_case_studies_in_all_locales(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PATHS as $locale => $paths) {
            $body = $this->get($paths['home'])->assertOk()->getContent();
            $section = $this->situationSection($body);
            $cases = trans('home.situation.cases', [], $locale);

            // Věta nad seznamem zůstává.
            $this->assertStringContainsString(e(trans('home.situation.text', [], $locale)), $section);
            $this->assertStringContainsString('class="pd-situation__cases"', $section);
            $this->assertSame(3, substr_count($section, 'data-analytics="situation_case_click"'),
                "Na {$paths['home']} nejsou právě tři příklady.");
            $this->assertSame(self::SLUGS, array_column($cases, 'slug'));

            foreach ($cases as $case) {
                $href = url($paths['project'] . '/' . $case['slug']);
                $aria = trans('home.situation.cases_link_aria', ['name' => $case['name']], $locale);

                $this->assertStringContainsString('href="' . $href . '"', $section,
                    "Na {$paths['home']} chybí odkaz na {$href}.");
                $this->assertStringContainsString('aria-label="' . e($aria) . '"', $section);
                $this->assertStringContainsString('data-analytics-props=\'{"slug":"' . $case['slug'] . '"}\'', $section);
                foreach (['name', 'field', 'text'] as $key) {
                    $this->assertStringContainsString(e($case[$key]), $section,
                        "Na {$paths['home']} chybí `{$key}` příkladu {$case['slug']}.");
                }
            }

            $this->assertSame(3, substr_count($section, e(trans('home.situation.cases_link', [], $locale))));
            // OND-490 (bod 8): VP Industry na homepage zmínit nechce.
            $this->assertStringNotContainsString('VP Industry', $body);
            $this->assertStringNotContainsString('vp-industry', $body);
            // Pravidla OND-438: related odkazy bez jména přechodu.
            $this->assertStringNotContainsString('view-transition-name', $section);
        }
    }

    /** Cíle odkazů jsou živé případovky, ne 404. */
    public function test_linked_case_studies_return_200(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PATHS as $paths) {
            foreach (self::SLUGS as $slug) {
                $this->get($paths['project'] . '/' . $slug)->assertOk();
            }
        }
    }

    public function test_unpublished_project_is_left_out(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->unpublish('zubni-provazek');

        foreach (self::PATHS as $locale => $paths) {
            $section = $this->situationSection($this->get($paths['home'])->assertOk()->getContent());

            $this->assertSame(2, substr_count($section, 'data-analytics="situation_case_click"'));
            $this->assertStringNotContainsString($paths['project'] . '/zubni-provazek', $section);
            $this->assertStringNotContainsString('Zubní Provázek', $section);
            $this->assertStringContainsString(url($paths['project'] . '/cyklocentrum'), $section);
            $this->assertStringContainsString(url($paths['project'] . '/kemp-veselka'), $section);
        }
    }

    /** Jeden osamělý příklad nestačí: seznam zmizí, věta zůstane. */
    public function test_list_is_omitted_below_two_published_cases(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->unpublish('zubni-provazek', 'kemp-veselka');

        foreach (self::PATHS as $locale => $paths) {
            $section = $this->situationSection($this->get($paths['home'])->assertOk()->getContent());

            $this->assertStringContainsString(e(trans('home.situation.text', [], $locale)), $section);
            $this->assertStringNotContainsString('pd-situation__cases', $section);
            $this->assertStringNotContainsString('situation_case_click', $section);
        }
    }

    /** Prázdná `portfolio_projects` → žádný seznam, homepage se vykreslí dál. */
    public function test_list_is_omitted_without_portfolio_data(): void
    {
        $section = $this->situationSection($this->get('/')->assertOk()->getContent());

        $this->assertStringNotContainsString('pd-situation__cases', $section);
    }

    /** Tvar klíče je sdílený mezi locale — jiný tvar v en/de by rozbil šablonu. */
    public function test_case_keys_have_the_same_shape_in_all_locales(): void
    {
        $shape = fn (string $locale) => array_map(
            fn (array $case) => [$case['slug'], array_keys($case)],
            trans('home.situation.cases', [], $locale)
        );

        $this->assertSame($shape('cs'), $shape('en'));
        $this->assertSame($shape('cs'), $shape('de'));

        foreach (array_keys(self::PATHS) as $locale) {
            // WCAG 2.5.3 (Label in Name): aria začíná viditelným textem odkazu bez šipky.
            $visible = rtrim(trans('home.situation.cases_link', [], $locale), ' →');
            $aria = trans('home.situation.cases_link_aria', [], $locale);
            $this->assertStringStartsWith($visible, $aria, "`cases_link_aria` v `{$locale}` nezačíná viditelným textem.");
            $this->assertStringContainsString(':name', $aria);
        }
    }
}
