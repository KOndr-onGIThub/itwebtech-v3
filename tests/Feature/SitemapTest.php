<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(config('sitemap.cache_key'));
    }

    public function test_sitemap_xml_endpoint_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $body = $response->getContent();

        $this->assertStringContainsString('<?xml', $body);
        $this->assertStringContainsString('xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $body);
        $this->assertStringContainsString('xmlns:xhtml="http://www.w3.org/1999/xhtml"', $body);

        // Statické URL × 3 lokality (alespoň home pro každý jazyk).
        $this->assertStringContainsString(route('cs.home'), $body);
        $this->assertStringContainsString(route('en.home'), $body);
        $this->assertStringContainsString(route('de.home'), $body);

        // hreflang alternates + x-default.
        $this->assertStringContainsString('hreflang="cs"', $body);
        $this->assertStringContainsString('hreflang="en"', $body);
        $this->assertStringContainsString('hreflang="de"', $body);
        $this->assertStringContainsString('hreflang="x-default"', $body);
    }

    public function test_sitemap_includes_published_portfolio_projects(): void
    {
        $this->seed(PortfolioSeeder::class);

        $project = PortfolioProject::published()->first();
        $this->assertNotNull($project, 'Test potřebuje alespoň jeden publikovaný projekt.');

        Cache::forget(config('sitemap.cache_key'));

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $body = $response->getContent();

        $this->assertStringContainsString(route('cs.project', ['url' => $project->slug]), $body);
        $this->assertStringContainsString(route('en.project', ['url' => $project->slug]), $body);
        $this->assertStringContainsString(route('de.project', ['url' => $project->slug]), $body);
    }

    public function test_sitemap_response_is_cached(): void
    {
        $this->get('/sitemap.xml')->assertOk();

        $this->assertNotNull(Cache::get(config('sitemap.cache_key')));
    }

    public function test_sitemap_uses_https_scheme_when_forced(): void
    {
        URL::forceScheme('https');
        Cache::forget(config('sitemap.cache_key'));

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $body = $response->getContent();

        // Žádný <loc> ani hreflang odkaz nesmí použít http:// (OND-84).
        $this->assertMatchesRegularExpression('#<loc>https://#', $body);
        $this->assertDoesNotMatchRegularExpression('#<loc>http://[^/]#', $body);
        $this->assertDoesNotMatchRegularExpression('#xhtml:link[^>]+href="http://[^/]#', $body);
    }

    public function test_artisan_command_invalidates_cache(): void
    {
        $this->get('/sitemap.xml')->assertOk();
        $this->assertNotNull(Cache::get(config('sitemap.cache_key')));

        $this->artisan('sitemap:generate')->assertExitCode(0);

        $this->assertNull(Cache::get(config('sitemap.cache_key')));
    }
}
