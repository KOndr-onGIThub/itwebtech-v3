<?php

namespace Tests\Feature;

use Database\Seeders\EnsureArticlesSeededSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-162: SEO polish — /cookies hreflang, home trailing slash a article
 * meta description length. QA findings F3/F4/F5 ze parent OND-138.
 */
class SeoPolishTest extends TestCase
{
    use RefreshDatabase;

    /**
     * F3 — /cookies hreflang nesmí ukazovat na homepage variants.
     *
     * OND-168 (2026-05-22): /cookies je nově lokalizovaný (cs/en/de variant),
     * takže hreflang nyní směřuje na per-locale URL místo sdíleného /cookies.
     * Původní OND-162 F3 vyžadoval všechny tři hreflangy === /cookies, což byl
     * workaround kvůli CS-only stránce. Po lokalizaci je správné chování
     * hreflang per locale, stejně jako u ostatních lokalizovaných stránek.
     */
    public function test_cookies_hreflang_points_to_self_not_homepage(): void
    {
        $response = $this->get('/cookies');

        $response->assertOk();

        $body = $response->getContent();

        // hreflang block musí všechny tři lokály mapovat na svou per-locale
        // /cookies variantu — nikoli na homepage.
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="cs" href="'.url('/cookies').'">',
            $body,
        );
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="en" href="'.url('/en/cookies').'">',
            $body,
        );
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="de" href="'.url('/de/cookies').'">',
            $body,
        );

        // Žádný hreflang link nesmí mířit na homepage variants.
        $this->assertStringNotContainsString('hreflang="cs" href="'.url('/').'"', $body);
        $this->assertStringNotContainsString('hreflang="en" href="'.url('/en').'"', $body);
        $this->assertStringNotContainsString('hreflang="de" href="'.url('/de').'"', $body);
    }

    /**
     * OND-168 — /cookies localized variants render the correct language.
     *
     * Smoke: page must NOT contain Czech-only strings when fetched in EN/DE.
     */
    public function test_cookies_en_page_renders_english(): void
    {
        $response = $this->get('/en/cookies');

        $response->assertOk();

        $body = $response->getContent();

        $this->assertStringContainsString('What I measure', $body);
        $this->assertStringNotContainsString('Co měřím', $body);
        $this->assertStringNotContainsString('Doba uchování', $body);
    }

    public function test_cookies_de_page_renders_german(): void
    {
        $response = $this->get('/de/cookies');

        $response->assertOk();

        $body = $response->getContent();

        $this->assertStringContainsString('Was ich messe', $body);
        $this->assertStringNotContainsString('Co měřím', $body);
        $this->assertStringNotContainsString('Doba uchování', $body);
    }

    /** F4 — home hreflang má trailing slash konzistentně s canonical. */
    public function test_home_hreflang_has_trailing_slash_matching_canonical(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $body = $response->getContent();

        // cs home je root → URL končí trailing slashem.
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="cs" href="'.rtrim(url('/'), '/').'/">',
            $body,
        );
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="en" href="'.rtrim(url('/'), '/').'/en/">',
            $body,
        );
        $this->assertStringContainsString(
            '<link rel="alternate" hreflang="de" href="'.rtrim(url('/'), '/').'/de/">',
            $body,
        );

        // Canonical pro home musí mít stejný formát jako cs hreflang (trailing slash).
        $this->assertStringContainsString(
            '<link rel="canonical" href="'.rtrim(url('/'), '/').'/">',
            $body,
        );
    }

    /** F5 — article <meta description> je trimnutá pod ~160 znaků. */
    public function test_article_meta_description_under_160_chars(): void
    {
        $this->seed(EnsureArticlesSeededSeeder::class);

        // Najdeme libovolný publikovaný CS článek s aktivním slugem (CS = /jak-na-to/{slug}).
        $article = \App\Models\Article::query()
            ->where('published', true)
            ->with('slugs')
            ->get()
            ->first(fn ($a) => $a->slug('cs') !== null);

        if ($article === null) {
            $this->markTestSkipped('Žádný publikovaný CS článek se seedovaným slugem – test neaplikovatelný.');
        }

        $response = $this->get('/jak-na-to/'.$article->slug('cs'));

        $response->assertOk();

        $body = $response->getContent();

        if (preg_match('#<meta name="description" content="([^"]*)"#', $body, $m) !== 1) {
            $this->fail('Article stránka nevyrenderovala <meta name="description">.');
        }

        $metaLength = mb_strlen(html_entity_decode($m[1], ENT_QUOTES, 'UTF-8'));

        $this->assertLessThanOrEqual(
            160,
            $metaLength,
            "Article meta description by měla být ≤ 160 znaků, je {$metaLength}.",
        );
    }
}
