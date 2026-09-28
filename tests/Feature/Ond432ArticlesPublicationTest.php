<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-432 — publikace článků 1, 5, 7, 9, 11, 12 v CS/EN/DE, článek 8 sloučený do 11.
 *
 * Fixture napodobuje produkční stav z 28. 9. 2026: publikované 3, 4, 6, 10, 13,
 * ostatní stažené, CS a EN řádky se starým importem v `bonus`, DE řádky
 * u nových článků neexistují. Testuje se reálná migrace a reálné seedery.
 * Že se u článků mimo rozsah nic nezměnilo, je ověřené na kopii produkční DB
 * (popis PR), tady by to fixture nedokázala.
 */
class Ond432ArticlesPublicationTest extends TestCase
{
    use RefreshDatabase;

    private const NEW_IDS = [1, 5, 7, 9, 11, 12];

    /** id => [published, cs slug, en slug, de slug|null] — stav před OND-432. */
    private const FIXTURE = [
        1  => [false, 'jak-vybrat-perfektni-domenove-jmeno', 'how-to-choose-the-perfect-domain-name', null],
        3  => [true, 'kolik-stoji-webove-stranky', 'how-much-does-a-website-cost', 'was-kostet-eine-website'],
        4  => [true, 'jak-se-pripravit-na-novy-web', 'how-to-prepare-for-a-new-website', 'vorbereitung-auf-die-neue-website'],
        5  => [false, 'co-je-seo-a-proc-je-tak-dulezite', 'what-is-seo-and-why-is-it-so-important', null],
        6  => [true, 'kdy-se-vyplati-aplikace-na-miru', 'when-a-custom-app-beats-a-spreadsheet', 'wann-sich-eine-eigene-anwendung-lohnt'],
        7  => [false, 'co-je-dulezitejsi-design-nebo-obsah-webovych-stranek', 'which-is-more-important-design-or-content', null],
        8  => [false, 'web-ktery-prevadi-navstevniky-na-zakazniky', 'website-that-converts-visitors-to-customers', null],
        9  => [false, 'jak-na-analyzu-klicovych-slov-krok-za-krokem', 'how-to-do-keyword-analysis-step-by-step', null],
        10 => [true, 'potrebuje-vase-firma-webovou-stranku', 'does-your-business-need-a-website', 'braucht-ihre-firma-eine-website'],
        11 => [false, 'jak-vytvorit-uspesnou-webovou-stranku', 'how-to-create-a-successful-website', null],
        12 => [false, 'zakladni-krok-pro-uspesny-webdesign-analyza-konkurence', 'basic-step-for-successful-web-design-competitive-analysis', null],
        13 => [true, 'redesign-webovych-stranek-duvody-signaly-a-jak-na-to', 'website-redesign-reasons-signals-and-how-to-do-it', 'wann-website-relaunch-sinn-ergibt'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (self::FIXTURE as $id => [$published, $cs, $en, $de]) {
            $article = new Article(['slug' => $cs, 'published' => $published]);
            $article->id = $id;
            $article->save();

            foreach (array_filter(['cs' => $cs, 'en' => $en, 'de' => $de]) as $locale => $slug) {
                $article->translations()->create([
                    'locale'      => $locale,
                    'active'      => true,
                    'title'       => "Starý titulek {$id} {$locale}",
                    'perex'       => '<p>Starý perex.</p>',
                    'content_1'   => '<p>Starý text.</p>',
                    'content_2'   => '<p>Starý text.</p>',
                    'img_preview' => "preview-{$id}.jpg",
                    'img_main'    => "main-{$id}.jpg",
                    'img_mid'     => 'SEO.webp',
                    'bonus'       => '<p>Blok ze starého importu.</p>',
                    'extra'       => '<p>Blok ze starého importu.</p>',
                ]);

                ArticleSlug::create(['article_id' => $id, 'locale' => $locale, 'slug' => $slug, 'active' => true]);
            }
        }
    }

    public function test_migration_publishes_the_six_articles_and_keeps_8_unpublished(): void
    {
        $this->migration()->up();

        foreach (self::NEW_IDS as $id) {
            $this->assertTrue((bool) Article::find($id)->published, "článek {$id} není publikovaný");
        }

        $this->assertFalse((bool) Article::find(8)->published);
    }

    public function test_migration_writes_seeder_texts_creates_de_rows_and_clears_old_blocks(): void
    {
        $this->migration()->up();

        $seeders = ['cs' => new BlogContentSeeder, 'en' => new BlogContentEnSeeder, 'de' => new BlogContentDeSeeder];

        foreach ($seeders as $locale => $seeder) {
            foreach (self::NEW_IDS as $id) {
                $row = DB::table('article_translations')->where('article_id', $id)->where('locale', $locale)->first();

                $this->assertNotNull($row, "{$locale} {$id}: řádek chybí");
                $this->assertSame($seeder->articles()[$id]['content_1'], $row->content_1, "{$locale} {$id}");
                $this->assertNull($row->bonus, "{$locale} {$id}: bonus by se vykreslil pod novým textem");
                $this->assertNull($row->extra, "{$locale} {$id}");
                $this->assertSame(1, (int) $row->active);
            }
        }

        // Obrázky s cizojazyčným textem v EN/DE nejsou, CS hlavní obrázek zůstává.
        $en1 = DB::table('article_translations')->where('article_id', 1)->where('locale', 'en')->first();
        $this->assertNull($en1->img_mid);
        $this->assertSame('main-1.jpg', $en1->img_main);
        $de12 = DB::table('article_translations')->where('article_id', 12)->where('locale', 'de')->first();
        $this->assertNull($de12->img_preview);
        $this->assertSame('analyza_konkurence.webp', $de12->img_mid);
    }

    public function test_migration_adds_link_sentences_to_published_articles(): void
    {
        $this->migration()->up();

        $content = DB::table('article_translations')->where('article_id', 3)->where('locale', 'cs')->value('content_2');
        $this->assertStringContainsString('href="/zapisky/co-je-dulezitejsi-design-nebo-obsah-webovych-stranek"', $content);

        // Pole bez vložené věty migrace nechala být.
        $this->assertSame('<p>Starý text.</p>', DB::table('article_translations')->where('article_id', 3)->where('locale', 'cs')->value('content_1'));
        $this->assertSame('<p>Starý text.</p>', DB::table('article_translations')->where('article_id', 6)->where('locale', 'cs')->value('content_2'));
    }

    public function test_new_articles_render_in_all_three_locales(): void
    {
        $this->migration()->up();

        $this->get('/zapisky/jak-vybrat-perfektni-domenove-jmeno')
            ->assertOk()
            ->assertSee('href="https://www.mojesidlo.cz/jak-vybrat-nazev-firmy/"', false);
        $this->get('/en/blog/how-to-do-keyword-research-step-by-step')->assertOk();
        $this->get('/de/blog/wettbewerbsanalyse-website')->assertOk();
    }

    public function test_old_en_slugs_redirect_to_new_ones(): void
    {
        $this->migration()->up();

        $this->get('/en/blog/how-to-do-keyword-analysis-step-by-step')
            ->assertStatus(301)
            ->assertRedirect('/en/blog/how-to-do-keyword-research-step-by-step');
        $this->get('/en/blog/basic-step-for-successful-web-design-competitive-analysis')
            ->assertStatus(301)
            ->assertRedirect('/en/blog/website-competitor-analysis');
    }

    public function test_article_8_redirects_to_article_11(): void
    {
        $this->migration()->up();

        $this->get('/zapisky/web-ktery-prevadi-navstevniky-na-zakazniky')
            ->assertStatus(301)
            ->assertRedirect('/zapisky/jak-vytvorit-uspesnou-webovou-stranku');
        $this->get('/en/blog/website-that-converts-visitors-to-customers')
            ->assertStatus(301)
            ->assertRedirect('/en/blog/how-to-create-a-successful-website');
    }

    public function test_migration_is_idempotent(): void
    {
        $migration = $this->migration();
        $migration->up();
        $snapshot = $this->snapshot();
        $migration->up();

        $this->assertSame($snapshot, $this->snapshot());
    }

    /**
     * `run()` seederů se na produkci nepouští, ale na čerstvé DB ano
     * (EnsureArticlesSeededSeeder). Nové články nesmí odpublikovat.
     */
    public function test_seeder_run_keeps_new_articles_published(): void
    {
        $this->migration()->up();

        $this->seed(BlogContentSeeder::class);
        $this->seed(BlogContentEnSeeder::class);
        $this->seed(BlogContentDeSeeder::class);

        foreach (self::NEW_IDS as $id) {
            $this->assertTrue((bool) Article::find($id)->published, "run() odpublikoval článek {$id}");
        }

        $this->assertSame([8], BlogContentSeeder::UNPUBLISHED_ARTICLE_IDS);
    }

    public function test_migration_is_inert_on_an_empty_database(): void
    {
        DB::table('article_slugs')->delete();
        DB::table('article_translations')->delete();
        DB::table('articles')->delete();

        $this->migration()->up();

        $this->assertSame(0, DB::table('articles')->count());
        $this->assertSame(0, DB::table('article_translations')->count());
        $this->assertSame(0, DB::table('article_slugs')->count());
    }

    /**
     * R1 z `review-preklady` (OND-431): předsunutá vedlejší věta převzatá
     * z češtiny („What to watch for, I write about in the article…“).
     */
    public function test_en_texts_have_no_czech_word_order_calque(): void
    {
        $pattern = '/(?:^|[>.] )(?:What|Why|How|Who|Whether|When|Which)\b[^.<]*?, (?:I|you) (?:write|explain|describe|go|cover|have|answered|will|find|can)\b/m';

        foreach ((new BlogContentEnSeeder)->articles() as $id => $fields) {
            foreach ($fields as $column => $value) {
                $this->assertDoesNotMatchRegularExpression($pattern, (string) $value, "EN článek {$id}, {$column}");
            }
        }
    }

    private function migration(): Migration
    {
        return require database_path('migrations/2026_09_28_100000_ond432_clanky_texty_publikace.php');
    }

    /** @return array<string, mixed> */
    private function snapshot(): array
    {
        $strip = fn ($rows) => $rows->map(fn ($row) => collect((array) $row)->except(['updated_at', 'created_at'])->all())->all();

        return [
            'articles' => $strip(DB::table('articles')->orderBy('id')->get()),
            'tr'       => $strip(DB::table('article_translations')->orderBy('id')->get()),
            'slugs'    => $strip(DB::table('article_slugs')->orderBy('id')->get()),
        ];
    }
}
