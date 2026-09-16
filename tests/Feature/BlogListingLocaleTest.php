<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-217: výpis blogu smí odkazovat jen na články, které mají aktivní slug
 * v aktuální locale. Předtím spadl `Article::slug()` na cs fallback a DE výpis
 * linkoval /de/blog/{cs-slug} — pět odkazů, všechny 404 (OND-160 check).
 */
class BlogListingLocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Článek se slugem v cs + en, bez de varianty — stav produkční DB.
        $article = Article::create([
            'slug'      => 'how-much-does-a-website-cost',
            'published' => true,
        ]);

        foreach (['cs' => 'Kolik stojí web', 'en' => 'How much does a website cost'] as $locale => $title) {
            $article->translations()->create([
                'locale'      => $locale,
                'active'      => true,
                'title'       => $title,
                'description' => 'Desc '.$locale,
                'perex'       => 'Perex '.$locale,
            ]);

            ArticleSlug::create([
                'article_id' => $article->id,
                'locale'     => $locale,
                'slug'       => $locale === 'cs' ? 'kolik-stoji-webove-stranky' : 'how-much-does-a-website-cost',
                'active'     => true,
            ]);
        }
    }

    public function test_listing_shows_article_in_locale_with_active_slug(): void
    {
        $this->get('/jak-na-to')
            ->assertOk()
            ->assertSee('/jak-na-to/kolik-stoji-webove-stranky', false);

        $this->get('/en/blog')
            ->assertOk()
            ->assertSee('/en/blog/how-much-does-a-website-cost', false);
    }

    public function test_listing_hides_article_without_slug_in_current_locale(): void
    {
        $this->get('/de/blog')
            ->assertOk()
            ->assertDontSee('/de/blog/kolik-stoji-webove-stranky', false)
            ->assertDontSee('/de/blog/how-much-does-a-website-cost', false)
            ->assertSee(__('blog.empty', [], 'de'), false);
    }

    /**
     * Pojistka proti regresi: každý odkaz z výpisu musí reálně odpovědět 200,
     * ne 404 ani 301. Kontroluje výstup, ne implementaci filtru.
     */
    public function test_every_link_in_listing_resolves(): void
    {
        // cs + en mají článek, de je po fixu prázdné
        $expectedLinks = ['/jak-na-to' => 1, '/en/blog' => 1, '/de/blog' => 0];

        foreach ($expectedLinks as $listing => $expectedCount) {
            $html = $this->get($listing)->assertOk()->getContent();

            // href je absolutní (lroute() vrací plnou URL), bereme path část
            preg_match_all('#href="[^"]*?(/(?:jak-na-to|en/blog|de/blog)/[^"]+)"#', $html, $matches);
            $links = array_values(array_unique($matches[1]));

            $this->assertCount($expectedCount, $links, "Špatný počet odkazů na článek ve výpisu {$listing}");

            foreach ($links as $href) {
                $this->get($href)->assertOk();
            }
        }
    }

    public function test_inactive_slug_does_not_put_article_in_listing(): void
    {
        $deOnlyInactive = Article::create([
            'slug'      => 'entwurf',
            'published' => true,
        ]);
        $deOnlyInactive->translations()->create([
            'locale'      => 'de',
            'active'      => true,
            'title'       => 'Entwurf',
            'description' => '',
            'perex'       => '',
        ]);
        ArticleSlug::create([
            'article_id' => $deOnlyInactive->id,
            'locale'     => 'de',
            'slug'       => 'entwurf',
            'active'     => false,
        ]);

        $this->get('/de/blog')
            ->assertOk()
            ->assertDontSee('/de/blog/entwurf', false);
    }
}
