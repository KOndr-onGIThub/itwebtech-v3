<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\EnsurePortfolioSeededSeeder;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-352 — homepage bez sekce portfolia je konverzní ztráta, kterou nikdo
 * nenahlásí: stránka se vykreslí normálně, jen bez důkazů odvedené práce.
 * Tenhle test na sekci trvá na skutečně vykreslené homepage.
 *
 * Kontext regrese: 25. 9. 2026 byla produkční DB třikrát vymazána
 * (`DROP TABLE` všech tabulek, doloženo v binlogu). Deploy vrátil admina
 * i články, portfolio ne — pro něj guard seeder neexistoval.
 */
class Ond352HomepagePortfolioSectionTest extends TestCase
{
    use RefreshDatabase;

    /** Slugy, které `PageController::home()` drží whitelistem. */
    private const FEATURED = ['pitarena', 'barana', 'nove-interiery'];

    /**
     * Vykreslená homepage musí mít `section-projects`, nadpis
     * „Weby, které běží v praxi" a tři prokliky na detail projektu.
     */
    public function test_homepage_renders_portfolio_section(): void
    {
        $this->seed(PortfolioSeeder::class);

        $body = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString(
            'id="section-projects"',
            $body,
            'Homepage přišla o sekci portfolia (`section-projects`).'
        );
        $this->assertStringContainsString(__('home.portfolio.heading'), $body);

        foreach (self::FEATURED as $slug) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();

            $this->assertStringContainsString(
                $project->detailUrl('cs'),
                $body,
                "Na homepage chybí proklik na detail projektu {$slug}."
            );
        }
    }

    /** Ty tři prokliky musí vést na existující detail, ne na 404. */
    public function test_featured_project_details_respond_ok(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::FEATURED as $slug) {
            $project = PortfolioProject::where('slug', $slug)->firstOrFail();

            $this->get($project->detailUrl('cs'))->assertOk();
        }
    }

    /**
     * `docs/portfolio-data.yaml` je pro čerstvou DB zdrojem pravdy, ale
     * typografii uvozovek na produkci srovnala migrace
     * `2026_09_23_100100_ond267_uvozovky_v_datech`, která je na prázdné tabulce
     * inertní. YAML tím tichounce zůstala pozadu (OND-352). Na naseedovaných
     * datech nesmí za otevírací „ následovat rovná palcová uvozovka.
     */
    public function test_seeded_texts_use_typographic_closing_quotes(): void
    {
        $this->seed(PortfolioSeeder::class);

        $columns = ['title', 'subtitle', 'summary', 'description', 'challenge', 'solution', 'result', 'meta_title', 'meta_description'];

        $offenders = [];

        foreach (DB::table('portfolio_project_translations')->get() as $row) {
            foreach ($columns as $column) {
                $value = $row->{$column} ?? null;

                if (is_string($value) && preg_match('/„[^„“"<>]*"/u', $value)) {
                    $offenders[] = "#{$row->id} {$row->locale} {$column}";
                }
            }
        }

        $this->assertSame([], $offenders, 'Rovná zavírací uvozovka v naseedovaných textech: '.implode(', ', $offenders));
    }

    /**
     * Příznak je zapnutý i bez proměnné v prostředí — jinak by jedno
     * přegenerování config cache sekci potichu shodilo (OND-352, větev 1).
     */
    public function test_portfolio_section_flag_defaults_to_on(): void
    {
        // Proměnnou z prostředí (i tu z `tests/bootstrap.php`) musíme odklidit,
        // jinak by test měřil ji a ne default v `config/site.php`.
        $key = 'SHOW_PORTFOLIO_SECTION';
        $previous = $_SERVER[$key] ?? null;

        unset($_SERVER[$key], $_ENV[$key]);
        putenv($key);

        try {
            $config = require config_path('site.php');

            $this->assertTrue(
                $config['features']['show_portfolio_section'],
                'show_portfolio_section musí být zapnuté i bez SHOW_PORTFOLIO_SECTION v prostředí — '
                .'jinak jedno přegenerování config cache sekci potichu shodí (OND-352).'
            );
        } finally {
            if ($previous !== null) {
                $_SERVER[$key] = $previous;
                $_ENV[$key] = $previous;
                putenv("{$key}={$previous}");
            }
        }
    }

    /**
     * Guard seeder z entrypointu: na prázdné tabulce doplní projekty…
     */
    public function test_ensure_portfolio_seeder_fills_empty_table(): void
    {
        $this->assertSame(0, DB::table('portfolio_projects')->count());

        $this->seed(EnsurePortfolioSeededSeeder::class);

        $this->assertSame(24, DB::table('portfolio_projects')->count());
        $this->assertStringContainsString('id="section-projects"', $this->get('/')->getContent());
    }

    /**
     * …a na naplněné tabulce nesmí sáhnout na data (ruční úpravy z Filamentu).
     */
    public function test_ensure_portfolio_seeder_is_noop_when_projects_exist(): void
    {
        $this->seed(PortfolioSeeder::class);

        DB::table('portfolio_project_translations')
            ->where('locale', 'cs')
            ->whereIn('project_id', DB::table('portfolio_projects')->where('slug', 'pitarena')->pluck('id'))
            ->update(['title' => 'Ručně přepsaný titulek']);

        $this->seed(EnsurePortfolioSeededSeeder::class);

        $this->assertDatabaseHas('portfolio_project_translations', [
            'title' => 'Ručně přepsaný titulek',
        ]);
        $this->assertSame(24, DB::table('portfolio_projects')->count());
    }
}
