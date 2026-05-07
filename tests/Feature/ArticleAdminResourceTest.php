<?php

namespace Tests\Feature;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleAdminResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_split_moves_translations_aside(): void
    {
        $data = [
            'slug'         => 'demo-clanek',
            'is_published' => true,
            'author'       => 'Ondra',
            'translations' => ['cs' => ['title' => 'Demo']],
        ];

        [$articleData, $side] = ArticleResource::splitFormData($data);

        $this->assertArrayNotHasKey('translations', $articleData);
        $this->assertArrayNotHasKey('is_published', $articleData);
        $this->assertSame('demo-clanek', $articleData['slug']);
        $this->assertTrue($articleData['published']);
        $this->assertSame(['cs' => ['title' => 'Demo']], $side['translations']);
    }

    public function test_split_does_not_mutate_unchanged_slug_during_edit(): void
    {
        $article = Article::create([
            'slug'         => 'jak-vybrat-perfektni-domenove-jmeno',
            'published'    => true,
            'published_at' => now(),
        ]);

        // Editor opens form a uloží beze změny.
        $data = [
            'slug'         => 'jak-vybrat-perfektni-domenove-jmeno',
            'is_published' => true,
            'translations' => [],
        ];

        [$articleData] = ArticleResource::splitFormData($data, $article);

        $this->assertSame(
            'jak-vybrat-perfektni-domenove-jmeno',
            $articleData['slug'],
            'No-op save MUSÍ zachovat slug byte-identický (SEO requirement).',
        );
    }

    public function test_split_normalizes_only_when_user_actually_changed_slug(): void
    {
        $article = Article::create(['slug' => 'puvodni-slug', 'published' => true]);

        $data = [
            'slug'         => 'NOVÝ Slug s mezerou',
            'is_published' => true,
            'translations' => [],
        ];

        [$articleData] = ArticleResource::splitFormData($data, $article);

        $this->assertSame('novy-slug-s-mezerou', $articleData['slug']);
    }

    public function test_translation_round_trip_preserves_html_attributes(): void
    {
        $article = Article::create(['slug' => 'seo-test', 'published' => true]);

        $bodyHtml = '<p>Někdy se setkávám s tím, že klient má relativně dlouhý název firmy. <a href="https://www.mojesidlo.cz/jak-vybrat-nazev-firmy/" target="_blank" rel="noopener" title="Jak vybrat název firmy">Mojesidlo.cz</a> má skvělý průvodce.</p>';

        ArticleResource::persistTranslations($article, [
            'cs' => [
                'title'       => 'SEO test',
                'description' => 'Lead.',
                'content_1'   => $bodyHtml,
            ],
        ]);

        $stored = $article->translations()->where('locale', 'cs')->first()->content_1;
        $this->assertSame($bodyHtml, $stored, 'Round-trip uložení HTML musí být byte-identické (SEO).');

        // Druhý průchod (no-op save).
        ArticleResource::persistTranslations($article->fresh(['translations']), [
            'cs' => [
                'title'       => 'SEO test',
                'description' => 'Lead.',
                'content_1'   => $bodyHtml,
            ],
        ]);
        $stored2 = $article->translations()->where('locale', 'cs')->first()->content_1;
        $this->assertSame($bodyHtml, $stored2);
    }

    public function test_slug_history_preserves_old_slug_on_change(): void
    {
        $article = Article::create(['slug' => 'puvodni', 'published' => true]);
        $article->slugs()->create(['slug' => 'puvodni', 'locale' => 'cs', 'active' => true]);

        // Slug se mění na nový — original je zachycen ve splitFormData→syncSlugHistory.
        $article->slug = 'novy';
        $article->save();
        ArticleResource::syncSlugHistory($article, 'puvodni');

        $cs = $article->slugs()->where('locale', 'cs')->get();

        $this->assertCount(2, $cs);
        $this->assertTrue(
            $cs->where('slug', 'novy')->first()?->active,
            'Nový slug musí být aktivní.',
        );
        $this->assertFalse(
            (bool) $cs->where('slug', 'puvodni')->first()?->active,
            'Starý slug musí zůstat v tabulce, ale neaktivní (pro 301 lookup).',
        );
    }

    public function test_translation_persistence_removes_empty_optional_locales(): void
    {
        $article = Article::create(['slug' => 'demo', 'published' => true]);

        ArticleResource::persistTranslations($article, [
            'cs' => ['title' => 'CS title', 'description' => 'CS desc', 'content_1' => '<p>...</p>'],
            'en' => ['title' => 'EN title'],
            'de' => ['title' => 'DE title'],
        ]);
        $this->assertCount(3, $article->fresh()->translations);

        ArticleResource::persistTranslations($article->fresh(['translations']), [
            'cs' => ['title' => 'CS title', 'description' => 'CS desc', 'content_1' => '<p>...</p>'],
            'en' => ['title' => null],
            'de' => ['title' => ''],
        ]);
        $remaining = $article->fresh()->translations;
        $this->assertCount(1, $remaining);
        $this->assertSame('cs', $remaining->first()->locale);
    }

    public function test_revision_snapshot_recorded_only_on_actual_changes(): void
    {
        $article = Article::create(['slug' => 'rev', 'published' => true]);

        $first = ArticleResource::persistTranslations($article, [
            'cs' => ['title' => 'First', 'description' => 'd', 'content_1' => '<p>1</p>'],
        ]);
        $this->assertNotEmpty($first, 'První ulození má alespoň title.cs jako change.');

        $second = ArticleResource::persistTranslations($article->fresh(['translations']), [
            'cs' => ['title' => 'First', 'description' => 'd', 'content_1' => '<p>1</p>'],
        ]);
        $this->assertEmpty($second, 'No-op save nesmí generovat changed_fields.');
    }

    public function test_hero_image_url_accessor_handles_storage_path_and_legacy(): void
    {
        $a1 = Article::create(['slug' => 'a1', 'image_url' => 'articles/a1/hero.webp']);
        $this->assertStringContainsString('articles/a1/hero.webp', (string) $a1->hero_image_url);

        $a2 = Article::create(['slug' => 'a2', 'image_url' => 'https://legacy.example/foo.jpg']);
        $this->assertSame('https://legacy.example/foo.jpg', $a2->hero_image_url);

        $a3 = Article::create(['slug' => 'a3']);
        $this->assertNull($a3->hero_image_url);
    }
}
