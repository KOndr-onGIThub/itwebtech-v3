<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RobotsTest extends TestCase
{
    public function test_robots_txt_returns_plain_text(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));

        $body = $response->getContent();

        $this->assertStringContainsString('User-agent: *', $body);
        $this->assertStringContainsString('Disallow:', $body);
        $this->assertStringContainsString('Sitemap:', $body);
    }

    public function test_robots_txt_advertises_sitemap_on_current_host(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();

        $expected = route('sitemap');

        $this->assertStringContainsString("Sitemap: {$expected}", $response->getContent());
    }

    /**
     * OND-85: pod proxy terminujícím TLS musí robots.txt inzerovat https
     * sitemap URL — i když interní request přijde jako http.
     */
    public function test_robots_txt_advertises_https_sitemap_when_scheme_forced(): void
    {
        URL::forceScheme('https');

        $response = $this->get('/robots.txt');

        $response->assertOk();

        $body = $response->getContent();

        // Najít řádek "Sitemap: ..."
        $this->assertMatchesRegularExpression('#Sitemap:\s+https://#', $body);
        $this->assertDoesNotMatchRegularExpression('#Sitemap:\s+http://#', $body);
    }

    /**
     * OND-342: `<meta name="robots">` byl do OND-306 přepisovatelný z view
     * (proměnná `$robots`) kvůli `noindex` na zmrazené staré homepage. Ta
     * stránka je pryč, mechanika taky a hodnota je zpátky natvrdo. Tenhle
     * test hlídal indexovatelnost `/` už v mazaném HomeLegacyPageTest —
     * přestěhoval se sem, aby se s lešením neztratil.
     */
    public function test_public_pages_are_indexable(): void
    {
        foreach (['/', '/en/', '/de/'] as $url) {
            $this->assertStringContainsString(
                '<meta name="robots" content="index, follow">',
                $this->get($url)->getContent(),
                "Stránka {$url} není indexovatelná."
            );
        }
    }
}
