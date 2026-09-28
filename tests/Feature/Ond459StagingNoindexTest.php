<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-459 — testovací web (`itwebtech.ondrejkriska.cz`) mimo vyhledávače,
 * jakmile ostrý web poběží na Webglobe. Řídí to env SEO_NOINDEX
 * (`site.noindex`), produkce ho nenastavuje a zůstává indexovatelná.
 */
class Ond459StagingNoindexTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = ['/', '/en/', '/de/', '/lp/webove-stranky-na-miru'];

    public function test_noindex_robots_txt_disallows_everything(): void
    {
        config(['site.noindex' => true]);

        $response = $this->get('https://itwebtech.ondrejkriska.cz/robots.txt')->assertOk();

        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
        $this->assertSame("User-agent: *\nDisallow: /\n", $response->getContent());
    }

    public function test_noindex_pages_send_header_and_meta(): void
    {
        config(['site.noindex' => true]);

        foreach (self::PAGES as $url) {
            $response = $this->get($url)->assertOk();

            $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
            $this->assertStringContainsString(
                '<meta name="robots" content="noindex, nofollow">',
                $response->getContent(),
                "Stránka {$url} nemá meta noindex."
            );
        }
    }

    public function test_noindex_header_is_on_redirects_too(): void
    {
        config(['site.noindex' => true]);

        $this->get('/blog')->assertRedirect()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_production_default_stays_indexable(): void
    {
        $this->assertFalse(config('site.noindex'));

        foreach (self::PAGES as $url) {
            $response = $this->get($url)->assertOk();

            $response->assertHeaderMissing('X-Robots-Tag');
            $this->assertStringContainsString(
                '<meta name="robots" content="index, follow">',
                $response->getContent(),
                "Stránka {$url} není indexovatelná."
            );
        }

        $body = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString('Allow: /', $body);
        $this->assertStringContainsString('Sitemap:', $body);
        $this->assertStringNotContainsString("Disallow: /\n", $body);
    }
}
