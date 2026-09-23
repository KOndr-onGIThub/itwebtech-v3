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
        $this->get('/zapisky')
            ->assertOk()
            ->assertSee('/zapisky/kolik-stoji-webove-stranky', false);

        $this->get('/en/blog')
            ->assertOk()
            ->assertSee('/en/blog/how-much-does-a-website-cost', false);
    }

    /**
     * OND-266: CS slug sekce se změnil `jak-na-to` → `zapisky` (Ondřej 23. 9.).
     * Staré adresy jsou v indexu i v odkazech zvenčí, takže musí držet 301.
     */
    public function test_old_cs_blog_urls_redirect_permanently(): void
    {
        $this->get('/jak-na-to')->assertRedirect('/zapisky');
        $this->get('/jak-na-to/kolik-stoji-webove-stranky')
            ->assertRedirect('/zapisky/kolik-stoji-webove-stranky');

        // `/blog` míří dál na default CS slug, ne na `/en/blog`.
        $this->get('/blog')->assertRedirect('/zapisky');
        $this->get('/blog/kolik-stoji-webove-stranky')
            ->assertRedirect('/zapisky/kolik-stoji-webove-stranky');
    }

    /**
     * OND-266: `/blog` nesmí vzniknout řetěz `/blog → /jak-na-to → /zapisky`.
     * Test výš hlídá cíl prvního skoku; tenhle hlídá, že cíl je koncový —
     * tedy že odpoví 200 a ne dalším redirectem.
     */
    public function test_old_urls_redirect_in_a_single_hop(): void
    {
        foreach (['/blog', '/jak-na-to'] as $old) {
            $this->get($this->get($old)->headers->get('Location'))->assertOk();
        }
    }

    /**
     * OND-266: v sitemapě nesmí zůstat stará CS adresa sekce — jinak tam
     * posíláme roboty na 301 a `/zapisky` nemá vlastní záznam.
     */
    public function test_sitemap_carries_new_cs_blog_url_only(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString('/jak-na-to', $xml);
        $this->assertStringContainsString('/zapisky', $xml);
        $this->assertStringContainsString('/zapisky/kolik-stoji-webove-stranky', $xml);

        // EN/DE zůstávají na `/blog` — Ondřej řekl „v češtině".
        $this->assertStringContainsString('/en/blog', $xml);
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
        $expectedLinks = ['/zapisky' => 1, '/en/blog' => 1, '/de/blog' => 0];

        foreach ($expectedLinks as $listing => $expectedCount) {
            $html = $this->get($listing)->assertOk()->getContent();

            // href je absolutní (lroute() vrací plnou URL), bereme path část
            preg_match_all('#href="[^"]*?(/(?:zapisky|en/blog|de/blog)/[^"]+)"#', $html, $matches);
            $links = array_values(array_unique($matches[1]));

            $this->assertCount($expectedCount, $links, "Špatný počet odkazů na článek ve výpisu {$listing}");

            foreach ($links as $href) {
                $this->get($href)->assertOk();
            }
        }
    }

    /**
     * OND-219: jakmile článek dostane slug i v DE, musí se objevit ve všech
     * třech výpisech a odkaz musí odpovědět 200. Protějšek testu výš, který
     * hlídá, že se bez DE slugu neobjeví.
     */
    public function test_listing_shows_article_with_slug_in_all_three_locales(): void
    {
        $article = Article::firstWhere('slug', 'how-much-does-a-website-cost');

        $article->translations()->create([
            'locale'      => 'de',
            'active'      => true,
            'title'       => 'Was kostet eine Website',
            'description' => 'Desc de',
            'perex'       => 'Perex de',
        ]);

        ArticleSlug::create([
            'article_id' => $article->id,
            'locale'     => 'de',
            'slug'       => 'was-kostet-eine-website',
            'active'     => true,
        ]);

        foreach (['/zapisky' => '/zapisky/kolik-stoji-webove-stranky', '/en/blog' => '/en/blog/how-much-does-a-website-cost', '/de/blog' => '/de/blog/was-kostet-eine-website'] as $listing => $detail) {
            $this->get($listing)
                ->assertOk()
                ->assertSee($detail, false);

            $this->get($detail)->assertOk();
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
