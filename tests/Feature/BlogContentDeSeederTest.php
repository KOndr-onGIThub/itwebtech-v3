<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Database\Seeders\BlogContentDeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-219: DE překlady a DE slugy 5 blogových článků.
 *
 * Fixture napodobuje produkční stav po OND-204: články 3, 4, 6, 10, 13 mají
 * CS překlad a CS slug, DE nic. Test pouští reálný seeder, ne kopii dat —
 * kdyby se v něm rozbily slugy nebo texty, spadne to tady.
 */
class BlogContentDeSeederTest extends TestCase
{
    use RefreshDatabase;

    /** `<loc>` detailu článku v libovolné locale (ne výpis, ten nemá další segment). */
    private const ARTICLE_LOC_PATTERN = '#<loc>[^<]*/(?:jak-na-to|en/blog|de/blog)/[^<]+</loc>#';

    /** Články, které seeder překládá (id → [cs slug, en slug] po OND-204). */
    private const FIXTURE_ARTICLES = [
        3  => ['kolik-stoji-webove-stranky', 'how-much-does-a-website-cost'],
        4  => ['jak-se-pripravit-na-novy-web', 'how-to-define-website-development-requirements'],
        6  => ['kdy-se-vyplati-aplikace-na-miru', 'how-simple-web-application-can-save-your-business-millions'],
        10 => ['potrebuje-vase-firma-webovou-stranku', 'does-your-company-need-a-website'],
        13 => ['redesign-webovych-stranek-duvody-signaly-a-jak-na-to', 'website-redesign-reasons-signals-and-how-to'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(config('sitemap.cache_key'));

        // Produkční stav po OND-204: cs + en překlad a slug, de nic.
        foreach (self::FIXTURE_ARTICLES as $id => [$csSlug, $enSlug]) {
            $article = new Article(['slug' => $csSlug, 'published' => true]);
            $article->id = $id;
            $article->save();

            $article->translations()->create([
                'locale'      => 'cs',
                'active'      => true,
                'title'       => 'CS titulek '.$id,
                'description' => 'CS popis '.$id,
                'perex'       => '<p>CS perex.</p>',
                'img_preview' => "preview-{$id}.jpg",
                'img_main'    => "main-{$id}.jpg",
            ]);

            $article->translations()->create([
                'locale'      => 'en',
                'active'      => true,
                'title'       => 'EN title '.$id,
                'description' => 'EN desc '.$id,
                'perex'       => '<p>EN perex.</p>',
            ]);

            foreach (['cs' => $csSlug, 'en' => $enSlug] as $locale => $slug) {
                ArticleSlug::create([
                    'article_id' => $id,
                    'locale'     => $locale,
                    'slug'       => $slug,
                    'active'     => true,
                ]);
            }
        }
    }

    public function test_de_listing_shows_five_articles_and_every_link_resolves(): void
    {
        // Před seederem je DE výpis prázdný (filtr z OND-217).
        $this->get('/de/blog')
            ->assertOk()
            ->assertSee(__('blog.empty', [], 'de'), false);

        $this->seed(BlogContentDeSeeder::class);

        $html = $this->get('/de/blog')->assertOk()->getContent();

        preg_match_all('#href="[^"]*?(/de/blog/[^"]+)"#', $html, $matches);
        $links = array_values(array_unique($matches[1]));

        $this->assertCount(5, $links, 'DE výpis musí odkazovat na všech 5 přeložených článků.');

        foreach ($links as $href) {
            $this->get($href)->assertOk();
        }
    }

    public function test_seeder_fills_de_translation_and_keeps_cs_images(): void
    {
        $this->seed(BlogContentDeSeeder::class);

        foreach (array_keys(self::FIXTURE_ARTICLES) as $id) {
            $de = DB::table('article_translations')
                ->where('article_id', $id)
                ->where('locale', 'de')
                ->first();

            $this->assertNotNull($de, "Článek {$id} nemá DE překlad.");
            $this->assertEquals(1, $de->active);
            $this->assertNotEmpty($de->title);
            $this->assertNotEmpty($de->perex);
            $this->assertNotEmpty($de->content_1);
            $this->assertNotEmpty($de->content_2);
            $this->assertNull($de->bonus);
            $this->assertNull($de->extra);

            // Obrázky se nepřekládají, berou se z CS řádku.
            $this->assertSame("preview-{$id}.jpg", $de->img_preview);
            $this->assertSame("main-{$id}.jpg", $de->img_main);

            // Limit z SeoPolishTest platí i pro DE meta description.
            $this->assertLessThanOrEqual(160, mb_strlen($de->description), "DE description článku {$id} je nad 160 znaků.");

            // CS text zůstal nedotčený.
            $cs = DB::table('article_translations')
                ->where('article_id', $id)
                ->where('locale', 'cs')
                ->first();
            $this->assertSame('CS titulek '.$id, $cs->title);
        }
    }

    public function test_de_slugs_are_unique_across_whole_table(): void
    {
        $this->seed(BlogContentDeSeeder::class);

        // PageController::article() hledá slug bez ohledu na locale, takže
        // kolize s cs/en slugem by přesměrovávala na cizí článek.
        $slugs = DB::table('article_slugs')->pluck('slug');

        $this->assertSame($slugs->count(), $slugs->unique()->count(), 'Slugy se napříč locale nesmí opakovat.');

        $deSlugs = DB::table('article_slugs')->where('locale', 'de')->where('active', 1)->get();
        $this->assertCount(5, $deSlugs);
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(BlogContentDeSeeder::class);

        $before = DB::table('article_translations')->where('locale', 'de')->count();
        $createdAt = DB::table('article_translations')->where('article_id', 3)->where('locale', 'de')->value('created_at');

        $this->seed(BlogContentDeSeeder::class);

        $this->assertSame($before, DB::table('article_translations')->where('locale', 'de')->count());
        $this->assertSame(5, DB::table('article_slugs')->where('locale', 'de')->count());
        $this->assertSame($createdAt, DB::table('article_translations')->where('article_id', 3)->where('locale', 'de')->value('created_at'));
    }

    public function test_sitemap_contains_de_article_urls(): void
    {
        $this->seed(BlogContentDeSeeder::class);
        Cache::forget(config('sitemap.cache_key'));

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        $deSlugs = DB::table('article_slugs')->where('locale', 'de')->pluck('slug');

        foreach ($deSlugs as $slug) {
            $this->assertStringContainsString('/de/blog/'.$slug, $body, "Sitemapě chybí DE článek {$slug}.");
        }

        // 5 článků × 3 locale = 15 URL detailu článku (před seederem jen 10).
        $this->assertSame(15, preg_match_all(self::ARTICLE_LOC_PATTERN, $body));
    }

    public function test_sitemap_had_only_cs_and_en_article_urls_before_seeder(): void
    {
        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertSame(10, preg_match_all(self::ARTICLE_LOC_PATTERN, $body), 'Fixture musí startovat na 10 URL, jinak test o 15 nic neměří.');
    }

    /**
     * Deploy cesta: na stagingu i produkci se obsah dostane ven jen migrací,
     * `EnsureArticlesSeededSeeder` běží pouze do prázdné tabulky.
     */
    public function test_migration_seeds_existing_database(): void
    {
        $this->migration()->up();

        $this->assertSame(5, DB::table('article_slugs')->where('locale', 'de')->where('active', 1)->count());
        $this->assertSame(5, DB::table('article_translations')->where('locale', 'de')->count());
    }

    public function test_migration_is_noop_on_empty_database(): void
    {
        foreach (['article_slugs', 'article_translations', 'articles'] as $table) {
            DB::table($table)->delete();
        }

        $this->migration()->up();

        $this->assertSame(0, DB::table('article_translations')->count());
    }

    private function migration(): object
    {
        return require database_path('migrations/2026_09_16_120000_seed_de_blog_content.php');
    }

    public function test_seeder_skips_missing_article(): void
    {
        foreach (['article_slugs', 'article_translations'] as $table) {
            DB::table($table)->where('article_id', 6)->delete();
        }
        DB::table('articles')->where('id', 6)->delete();

        $this->seed(BlogContentDeSeeder::class);

        $this->assertSame(4, DB::table('article_translations')->where('locale', 'de')->count());
        $this->assertSame(4, DB::table('article_slugs')->where('locale', 'de')->count());
    }
}
