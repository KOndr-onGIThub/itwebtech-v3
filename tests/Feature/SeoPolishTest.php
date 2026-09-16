<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
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

    /**
     * F5 — article <meta description> je trimnutá pod ~160 znaků.
     *
     * OND-216: test si článek zakládá sám místo `EnsureArticlesSeededSeeder`.
     * Ten na prázdné DB volá `ImportOldDataSeeder`, který je psaný výhradně pro
     * MySQL (mysqlovské escapování apostrofů v dumpech, `INSERT IGNORE`) a pod
     * testovacím sqlite spadne. Vlastní fixture je navíc silnější pokrytí:
     * description je záměrně delší než 160 znaků, takže trim v article.blade.php
     * se opravdu vykoná — na seedovaných datech by assert prošel i bez něj.
     */
    public function test_article_meta_description_under_160_chars(): void
    {
        // Perex-style description, jaké reálně chodí z DB (237-268 znaků).
        $longDescription = 'Weby dělám na míru a bez šablon, takže cena vychází z rozsahu, '
            .'ne z ceníku hotového řešení. V článku rozepisuju tři cenová pásma, co v nich '
            .'je obsažené, co cenu posouvá nahoru a kdy se vám naopak vyplatí zvolit někoho '
            .'jiného než mě.';

        $this->assertGreaterThan(160, mb_strlen($longDescription), 'Fixture musí být delší než limit, jinak test nic netestuje.');

        $article = Article::create([
            'slug'      => 'kolik-stoji-webove-stranky',
            'published' => true,
        ]);

        $article->translations()->create([
            'locale'      => 'cs',
            'active'      => true,
            'title'       => 'Kolik stojí web na míru',
            'description' => $longDescription,
            'perex'       => '<p>Perex.</p>',
        ]);

        ArticleSlug::create([
            'article_id' => $article->id,
            'locale'     => 'cs',
            'slug'       => 'kolik-stoji-webove-stranky',
            'active'     => true,
        ]);

        $response = $this->get('/jak-na-to/kolik-stoji-webove-stranky');

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
