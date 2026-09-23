<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Database\Seeders\PortfolioTagNamesSeeder;
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

        // OND-208: 24. projekt je `pitarena-eshop` (shop.pitarena.cz).
        // OND-282: tři projekty (logo-realitacky, video-pitbike-akademie,
        // animace-delejme) zůstávají v datasetu, ale nejsou publikované.
        $this->assertSame(21, $expectedCount, 'Seeder by měl vytvořit 21 publikovaných projektů z 24 v datasetu.');

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
        // `pitarena` je značka → slug zůstává jazyk-neutrální ve všech locale.
        $slug = 'pitarena';

        $response = $this->get('/projekty/'.$slug);

        $response->assertOk();

        $response->assertSee('hreflang="cs"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="de"', false);
        $response->assertSee('/projekty/'.$slug, false);
        $response->assertSee('/en/projects/'.$slug, false);
        $response->assertSee('/de/projekte/'.$slug, false);
    }

    /* ================================================================== */
    /*  OND-209 — slug per locale                                         */
    /* ================================================================== */

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function localizedSlugProvider(): array
    {
        // [cs slug, de slug, en slug]
        // OND-282: `animace-delejme` tu byl taky, ale od odpublikování
        // vrací 404 ve všech locale — pokrývá ho `unpublishedProjectProvider`.
        return [
            'pitarena-cedule'     => ['pitarena-cedule', 'pitarena-werbeschild', 'pitarena-outdoor-sign'],
            'clanek-motorkari-cz' => ['clanek-motorkari-cz', 'artikel-motorkari-cz', 'article-motorkari-cz'],
            'pitarena-eshop'      => ['pitarena-eshop', 'pitarena-onlineshop', 'pitarena-online-shop'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('localizedSlugProvider')]
    public function test_localized_slug_serves_project_detail(string $cs, string $de, string $en): void
    {
        $this->get('/projekty/'.$cs)->assertOk();
        $this->get('/de/projekte/'.$de)->assertOk();
        $this->get('/en/projects/'.$en)->assertOk();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('localizedSlugProvider')]
    public function test_old_czech_slug_redirects_301_to_localized_slug(string $cs, string $de, string $en): void
    {
        $this->get('/de/projekte/'.$cs)
            ->assertStatus(301)
            ->assertRedirect(url('/de/projekte/'.$de));

        $this->get('/en/projects/'.$cs)
            ->assertStatus(301)
            ->assertRedirect(url('/en/projects/'.$en));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('localizedSlugProvider')]
    public function test_localized_slug_is_not_reachable_in_other_locale(string $cs, string $de, string $en): void
    {
        // DE slug nesmí fungovat na české ani anglické adrese (a naopak).
        $this->get('/projekty/'.$de)->assertNotFound();
        $this->get('/en/projects/'.$de)->assertNotFound();
        $this->get('/de/projekte/'.$en)->assertNotFound();
    }

    public function test_project_detail_hreflang_uses_localized_slugs(): void
    {
        $response = $this->get('/de/projekte/pitarena-werbeschild');

        $response->assertOk();
        $response->assertSee('/projekty/pitarena-cedule', false);
        $response->assertSee('/de/projekte/pitarena-werbeschild', false);
        $response->assertSee('/en/projects/pitarena-outdoor-sign', false);
    }

    public function test_project_listing_links_to_localized_slug(): void
    {
        $this->get('/de/projekte')
            ->assertOk()
            ->assertSee('/de/projekte/pitarena-werbeschild', false)
            ->assertDontSee('/de/projekte/pitarena-cedule', false);

        $this->get('/projekty')
            ->assertOk()
            ->assertSee('/projekty/pitarena-cedule', false);
    }

    public function test_sitemap_contains_localized_project_slugs(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee('/de/projekte/pitarena-werbeschild', false);
        $response->assertSee('/en/projects/pitarena-outdoor-sign', false);
        $response->assertDontSee('/de/projekte/pitarena-cedule', false);
    }

    /* ================================================================== */
    /*  OND-282 — tři odpublikované projekty                              */
    /* ================================================================== */

    /**
     * @return array<string, array{0: string, 1: array<int, string>}>
     */
    public static function unpublishedProjectProvider(): array
    {
        // [neutrální slug, všechny URL, na kterých projekt kdy byl]
        return [
            'logo-realitacky' => ['logo-realitacky', [
                '/projekty/logo-realitacky',
                '/en/projects/logo-realitacky',
                '/de/projekte/logo-realitacky',
            ]],
            'video-pitbike-akademie' => ['video-pitbike-akademie', [
                '/projekty/video-pitbike-akademie',
                '/en/projects/video-pitbike-akademie',
                '/de/projekte/video-pitbike-akademie',
            ]],
            'animace-delejme' => ['animace-delejme', [
                '/projekty/animace-delejme',
                '/en/projects/attention-grabbing-animation',
                '/de/projekte/aufmerksamkeits-animation',
            ]],
        ];
    }

    /**
     * Data zůstávají v DB — mazat se nesmí, jen odpublikovat.
     *
     * @param  array<int, string>  $urls
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unpublishedProjectProvider')]
    public function test_unpublished_project_stays_in_database(string $slug, array $urls): void
    {
        $project = PortfolioProject::where('slug', $slug)->first();

        $this->assertNotNull($project, "Projekt `{$slug}` se nesmí mazat, jen odpublikovat.");
        $this->assertNull($project->published_at, "Projekt `{$slug}` má být odpublikovaný.");
        $this->assertTrue($project->translations()->exists(), "Překlady `{$slug}` mají zůstat.");
    }

    /**
     * Detail je 404 ve všech locale — přímá URL nikam nevede.
     *
     * @param  array<int, string>  $urls
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unpublishedProjectProvider')]
    public function test_unpublished_project_detail_returns_404(string $slug, array $urls): void
    {
        foreach ($urls as $url) {
            $this->get($url)->assertNotFound();
        }
    }

    /**
     * Na 404 nesmí vést žádný odkaz z webu: ani výpis, ani dlaždice
     * „Další projekty" v detailu jiného projektu, ani homepage, ani sitemapa.
     *
     * @param  array<int, string>  $urls
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unpublishedProjectProvider')]
    public function test_nothing_on_the_site_links_to_unpublished_project(string $slug, array $urls): void
    {
        $pages = ['/', '/projekty', '/en/projects', '/de/projekte', '/sitemap.xml'];

        // Detaily ostatních projektů ze stejné kategorie („Další projekty").
        foreach (PortfolioProject::published()->where('category', 'other')->get() as $sibling) {
            $pages[] = '/projekty/'.$sibling->slug;
        }

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertOk();

            foreach ($urls as $url) {
                $response->assertDontSee($url, false);
            }
        }
    }

    /**
     * Kolize by tiše ukradla detail jinému projektu — resolving bere
     * lokalizovaný slug dřív než jazyk-neutrální.
     */
    public function test_resolved_slugs_are_unique_within_each_locale(): void
    {
        $projects = PortfolioProject::published()->with('translations')->get();

        foreach (['cs', 'en', 'de'] as $locale) {
            $slugs = $projects->map(fn (PortfolioProject $p) => $p->slugFor($locale))->all();

            $this->assertSame(
                count($slugs),
                count(array_unique($slugs)),
                "Duplicitní slug v locale {$locale}: ".implode(', ', array_diff_assoc($slugs, array_unique($slugs)))
            );
        }

        // Lokalizovaný slug nesmí kolidovat ani s jazyk-neutrálním slugem
        // jiného projektu — jinak by ten projekt v dané locale zmizel.
        $neutral = $projects->pluck('slug', 'id');

        foreach ($projects as $project) {
            foreach (['en', 'de'] as $locale) {
                $localized = $project->translations->firstWhere('locale', $locale)?->slug;
                if (! filled($localized)) {
                    continue;
                }

                $owner = $neutral->search($localized);
                $this->assertTrue(
                    $owner === false || $owner === $project->id,
                    "Lokalizovaný slug `{$localized}` ({$locale}) koliduje s neutrálním slugem jiného projektu."
                );
            }
        }
    }

    public function test_project_listing_available_in_all_locales(): void
    {
        $this->get('/projekty')->assertOk();
        $this->get('/en/projects')->assertOk();
        $this->get('/de/projekte')->assertOk();
    }

    /**
     * OND-223: štítky se dřív vyráběly z humanizovaného slugu a jen česky,
     * takže na detailu svítilo „Seo" / „Dlouhodoba spoluprace" a na německé
     * stránce české popisky. Seeder názvů musí pokrýt každý použitý slug
     * ve všech třech jazycích.
     */
    public function test_every_used_tag_has_a_name_in_all_locales(): void
    {
        $usedSlugs = DB::table('portfolio_tags')
            ->whereIn('id', DB::table('portfolio_project_tag')->select('tag_id'))
            ->pluck('slug', 'id');

        $this->assertNotEmpty($usedSlugs, 'Seeder musí navěsit na projekty nějaké štítky.');

        foreach ($usedSlugs as $tagId => $slug) {
            // Název musí pocházet ze slovníku, ne z humanizovaného slugu.
            $this->assertArrayHasKey(
                $slug,
                PortfolioTagNamesSeeder::NAMES,
                "Štítek „{$slug}\" chybí v PortfolioTagNamesSeeder::NAMES, takže by se vykreslil jako slug."
            );

            $names = DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->pluck('name', 'locale');

            foreach (['cs', 'en', 'de'] as $locale) {
                $this->assertArrayHasKey(
                    $locale,
                    $names->all(),
                    "Štítek „{$slug}\" nemá název v jazyce {$locale}."
                );
                $this->assertSame(
                    PortfolioTagNamesSeeder::NAMES[$slug][$locale],
                    $names[$locale],
                    "Štítek „{$slug}\" má v jazyce {$locale} jiný název, než říká slovník."
                );
            }
        }
    }

    public function test_project_detail_shows_localized_tag_names(): void
    {
        // pitarena nese štítky, které byly vidět rozbité: `seo`, `dlouhodoba-spoluprace`.
        $this->get('/projekty/pitarena')
            ->assertOk()
            ->assertSee('Viditelnost ve vyhledávačích', false)
            ->assertSee('Dlouhodobá spolupráce', false)
            ->assertDontSee('Dlouhodoba spoluprace', false);

        $this->get('/de/projekte/pitarena')
            ->assertOk()
            ->assertSee('Sichtbarkeit in Suchmaschinen', false)
            ->assertSee('Langfristige Zusammenarbeit', false)
            ->assertDontSee('Dlouhodoba spoluprace', false);

        $this->get('/en/projects/pitarena')
            ->assertOk()
            ->assertSee('Search visibility', false)
            ->assertSee('Long-term partnership', false);
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
