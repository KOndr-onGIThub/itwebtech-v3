<?php

namespace Tests\Feature;

use App\Http\Controllers\PageController;
use App\Models\Article;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Slugs\ArticleSlug;
use App\Services\SitemapGenerator;
use DOMDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * OND-485: sitemapa
 *  - URL statických stránek přesně jako canonical (`/en/`, `/de/` s lomítkem),
 *  - statické stránky se berou z rout, nová routa se objeví bez úpravy generátoru,
 *  - uložení článku/projektu hned zahodí cache,
 *  - XSL styl pro čitelné zobrazení v prohlížeči.
 */
class Ond485SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(config('sitemap.cache_key'));
    }

    /**
     * @return array<int, string>
     */
    private function sitemapLocs(): array
    {
        Cache::forget(config('sitemap.cache_key'));
        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        preg_match_all('#<loc>([^<]+)</loc>#', $body, $m);

        return array_map('html_entity_decode', $m[1]);
    }

    /**
     * Pojmenované GET routy `{locale}.{page}` bez parametrů — nezávisle na generátoru.
     *
     * @return array<int, array{0: string, 1: string}>
     */
    private function localeStaticRoutes(): array
    {
        $routes = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            if (preg_match('/^(cs|en|de)\.([\w-]+)$/', (string) $route->getName(), $m)
                && in_array('GET', $route->methods(), true)
                && $route->parameterNames() === []) {
                $routes[] = [$m[1], $m[2]];
            }
        }

        return $routes;
    }

    public function test_static_urls_match_page_canonical_exactly(): void
    {
        $locs   = $this->sitemapLocs();
        $routes = $this->localeStaticRoutes();

        $this->assertGreaterThanOrEqual(27, count($routes), 'Čekám 9 stránek × 3 jazyky.');

        foreach ($routes as [$locale, $page]) {
            $html = $this->get(lroute($page, $locale))->assertOk()->getContent();

            $this->assertMatchesRegularExpression('#<link rel="canonical" href="([^"]+)">#', $html);
            preg_match('#<link rel="canonical" href="([^"]+)">#', $html, $m);
            $canonical = html_entity_decode($m[1]);

            $this->assertContains($canonical, $locs, "{$locale}.{$page}: canonical {$canonical} v sitemapě chybí.");
        }
    }

    public function test_locale_homes_have_trailing_slash(): void
    {
        $locs = $this->sitemapLocs();
        $base = rtrim(url('/'), '/');

        $this->assertContains($base . '/', $locs);
        $this->assertContains($base . '/en/', $locs);
        $this->assertContains($base . '/de/', $locs);
        $this->assertNotContains($base . '/en', $locs);
        $this->assertNotContains($base . '/de', $locs);
    }

    /**
     * Guard: každá GET routa bez parametrů v locale skupině je v sitemapě.
     */
    public function test_every_parameterless_locale_route_is_in_sitemap(): void
    {
        $locs = $this->sitemapLocs();

        foreach ($this->localeStaticRoutes() as [$locale, $page]) {
            $this->assertContains(lroute($page, $locale), $locs, "Routa {$locale}.{$page} v sitemapě chybí.");
        }
    }

    public function test_new_route_appears_in_sitemap_without_touching_generator(): void
    {
        Route::middleware('web')->get('/nova-stranka-ond485', [PageController::class, 'about'])->name('cs.novaStrankaOnd485');
        Route::middleware('web')->get('/en/new-page-ond485', [PageController::class, 'about'])->name('en.novaStrankaOnd485');
        Route::getRoutes()->refreshNameLookups();

        $this->assertContains('novaStrankaOnd485', app(SitemapGenerator::class)->staticPages());

        $xml = app(SitemapGenerator::class)->build();

        $this->assertStringContainsString('<loc>' . url('/nova-stranka-ond485') . '</loc>', $xml);
        $this->assertStringContainsString('<loc>' . url('/en/new-page-ond485') . '</loc>', $xml);
        // DE varianta neexistuje → žádná adresa ani alternate pro de.
        $this->assertStringNotContainsString('ond485" hreflang="de"', $xml);
    }

    public function test_parametric_routes_are_not_static_pages(): void
    {
        $pages = app(SitemapGenerator::class)->staticPages();

        $this->assertNotContains('project', $pages);
        $this->assertNotContains('article', $pages);
        $this->assertContains('home', $pages);
        $this->assertContains('reviews', $pages);
    }

    public function test_publishing_article_flushes_sitemap_cache(): void
    {
        $this->get('/sitemap.xml')->assertOk();
        $this->assertNotNull(Cache::get(config('sitemap.cache_key')));

        $article = Article::create(['slug' => 'ond485', 'published' => false]);
        $this->assertNull(Cache::get(config('sitemap.cache_key')), 'Uložení článku cache nezahodilo.');

        ArticleSlug::create(['article_id' => $article->id, 'locale' => 'cs', 'slug' => 'ond485-clanek', 'active' => true]);

        $this->get('/sitemap.xml')->assertOk();
        $article->update(['published' => true]);

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString(route('cs.article', ['slug' => 'ond485-clanek']), $body);

        // Odpublikování zmizí hned, ne až po 10 minutách.
        $article->update(['published' => false]);
        $body = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringNotContainsString('ond485-clanek', $body);
    }

    public function test_publishing_project_flushes_sitemap_cache(): void
    {
        $project = PortfolioProject::create(['slug' => 'ond485-projekt', 'category' => 'website', 'client_name' => 'Demo']);

        $this->assertStringNotContainsString('ond485-projekt', $this->get('/sitemap.xml')->assertOk()->getContent());

        $project->update(['published_at' => now()->subMinute()]);
        $this->assertStringContainsString('ond485-projekt', $this->get('/sitemap.xml')->assertOk()->getContent());

        $project->update(['published_at' => null]);
        $this->assertStringNotContainsString('ond485-projekt', $this->get('/sitemap.xml')->assertOk()->getContent());

        $this->get('/sitemap.xml')->assertOk();
        $project->delete();
        $this->assertNull(Cache::get(config('sitemap.cache_key')), 'Smazání projektu cache nezahodilo.');
    }

    public function test_sitemap_links_stylesheet_and_stylesheet_is_valid_xslt(): void
    {
        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>', $body);
        // Pro vyhledávače se obsah nemění: pořád validní urlset.
        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($body));
        $this->assertSame('urlset', $doc->documentElement->localName);

        $xsl = $this->get('/sitemap.xsl')->assertOk();
        $this->assertStringContainsString('text/xsl', $xsl->headers->get('Content-Type'));
        $this->assertStringStartsWith('<?xml', $xsl->getContent());

        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($xsl->getContent()), 'XSL není well-formed XML.');
        $this->assertSame('http://www.w3.org/1999/XSL/Transform', $doc->documentElement->namespaceURI);

        // Skupiny podle prefixů z config/slugs.
        $content = $xsl->getContent();
        $this->assertStringContainsString("starts-with(s:loc, '" . url('/projekty/') . "/')", $content);
        $this->assertStringContainsString("starts-with(s:loc, '" . url('/en/blog') . "/')", $content);
        $this->assertStringContainsString("starts-with(s:loc, '" . url('/de/') . "/')", $content);
    }
}
