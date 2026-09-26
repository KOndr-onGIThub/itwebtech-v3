<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * OND-351 — dva nálezy z měření odkazového grafu (OND-348):
 *  1. /projekty na prázdné DB otevíralo filtrem s nulami a prázdným stavem
 *     „Momentálně nejsou k dispozici žádné projekty." — tedy stránka, která má
 *     dokazovat odvedenou práci, hlásila, že žádná není.
 *  2. /o-mne chybělo v sitemap.xml (jediná z osmi statických stránek).
 */
class Ond351ProjectsEmptyAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prázdná `portfolio_projects` → žádný filtr, žádná mřížka, žádný prázdný stav.
     * Stránka začíná případovkami („Čtyři projekty zblízka").
     */
    public function test_projects_page_hides_filter_and_grid_when_no_projects_in_db(): void
    {
        $body = $this->get('/projekty')->assertOk()->getContent();

        $this->assertStringNotContainsString(__('projects.empty'), $body);
        $this->assertStringNotContainsString('portfolio-grid__empty', $body);
        $this->assertStringNotContainsString('class="portfolio-filter"', $body);
        $this->assertStringNotContainsString('portfolio-filter__count', $body);

        // Zbytek stránky zůstává nedotčený — případovky se vykreslují dál.
        $this->assertStringContainsString(__('projects.snapshots.heading'), $body);
    }

    /**
     * Regrese: až se DB naplní, filtr i mřížka se musí vrátit samy.
     */
    public function test_projects_page_shows_filter_and_grid_when_db_has_projects(): void
    {
        $this->seed(PortfolioSeeder::class);

        $body = $this->get('/projekty')->assertOk()->getContent();

        $this->assertStringContainsString('class="portfolio-filter"', $body);
        $this->assertStringContainsString('class="portfolio-card"', $body);
        $this->assertStringNotContainsString(__('projects.empty'), $body);
    }

    /**
     * /o-mne, /en/about a /de/ueber-mich musí být v sitemapě.
     */
    public function test_sitemap_contains_about_page_in_all_locales(): void
    {
        Cache::forget(config('sitemap.cache_key'));

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach (['cs', 'en', 'de'] as $locale) {
            $this->assertStringContainsString(
                route("{$locale}.about"),
                $body,
                "V sitemapě chybí about pro locale {$locale}."
            );
        }
    }
}
