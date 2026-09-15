<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-160: article detail route musí validate slug per locale.
 * Bez tohoto check byl jeden článek dostupný pod ~3 slug variantami
 * × 3 locale prefixy se self-canonical → duplicate content v Google.
 */
class ArticleSlugLocaleTest extends TestCase
{
    use RefreshDatabase;

    private Article $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = Article::create([
            'slug'      => 'how-much-does-a-website-cost',
            'published' => true,
        ]);

        foreach (['cs', 'en', 'de'] as $locale) {
            $this->article->translations()->create([
                'locale'      => $locale,
                'active'      => true,
                'title'       => 'Title '.$locale,
                'description' => 'Desc '.$locale,
                'perex'       => 'Perex '.$locale,
            ]);
        }

        ArticleSlug::create([
            'article_id' => $this->article->id,
            'locale'     => 'cs',
            'slug'       => 'kolik-stoji-webove-stranky',
            'active'     => true,
        ]);
        ArticleSlug::create([
            'article_id' => $this->article->id,
            'locale'     => 'en',
            'slug'       => 'how-much-does-a-website-cost',
            'active'     => true,
        ]);
        ArticleSlug::create([
            'article_id' => $this->article->id,
            'locale'     => 'de',
            'slug'       => 'wieviel-kostet-eine-webseite',
            'active'     => true,
        ]);
    }

    public function test_returns_200_when_slug_matches_current_locale(): void
    {
        $this->get('/en/blog/how-much-does-a-website-cost')->assertOk();
        $this->get('/jak-na-to/kolik-stoji-webove-stranky')->assertOk();
        $this->get('/de/blog/wieviel-kostet-eine-webseite')->assertOk();
    }

    public function test_returns_301_when_slug_belongs_to_different_locale(): void
    {
        // EN URL s CS slugem → 301 na EN slug
        $this->get('/en/blog/kolik-stoji-webove-stranky')
            ->assertRedirect('/en/blog/how-much-does-a-website-cost');

        // EN URL s DE slugem → 301 na EN slug
        $this->get('/en/blog/wieviel-kostet-eine-webseite')
            ->assertRedirect('/en/blog/how-much-does-a-website-cost');

        // CS URL s EN slugem → 301 na CS slug
        $this->get('/jak-na-to/how-much-does-a-website-cost')
            ->assertRedirect('/jak-na-to/kolik-stoji-webove-stranky');

        // DE URL s EN slugem → 301 na DE slug
        $this->get('/de/blog/how-much-does-a-website-cost')
            ->assertRedirect('/de/blog/wieviel-kostet-eine-webseite');
    }

    public function test_returns_404_when_slug_does_not_exist(): void
    {
        $this->get('/en/blog/non-existent-slug')->assertNotFound();
        $this->get('/jak-na-to/neexistuje')->assertNotFound();
        $this->get('/de/blog/gibt-es-nicht')->assertNotFound();
    }

    public function test_returns_404_when_article_has_no_slug_for_current_locale(): void
    {
        // Article s pouze CS slugem nesmí být dostupný pod /en/blog ani /de/blog,
        // ani když se v EN URL omylem objeví CS slug — 404, ne render cs obsahu.
        $csOnly = Article::create([
            'slug'      => 'pouze-cs',
            'published' => true,
        ]);
        $csOnly->translations()->create([
            'locale'      => 'cs',
            'active'      => true,
            'title'       => 'Pouze CS',
            'description' => '',
            'perex'       => '',
        ]);
        ArticleSlug::create([
            'article_id' => $csOnly->id,
            'locale'     => 'cs',
            'slug'       => 'pouze-cs',
            'active'     => true,
        ]);

        $this->get('/jak-na-to/pouze-cs')->assertOk();
        $this->get('/en/blog/pouze-cs')->assertNotFound();
        $this->get('/de/blog/pouze-cs')->assertNotFound();
    }

    public function test_returns_404_when_slug_inactive(): void
    {
        ArticleSlug::create([
            'article_id' => $this->article->id,
            'locale'     => 'en',
            'slug'       => 'old-en-slug',
            'active'     => false,
        ]);

        $this->get('/en/blog/old-en-slug')->assertNotFound();
    }
}
