<?php

namespace Tests\Feature;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-292 (2. vlna) — rozhodnutí boardu z 23. 9. 2026.
 *
 * a) náhledy tří článků ve výpisu /zapisky (screenshoty z případovek),
 * b) zrušená sekce „Kde sídlím" na /o-mne (adresa zůstává na /kontakt).
 */
class Ond292NahledyClankuTest extends TestCase
{
    use RefreshDatabase;

    /** slug => [nový náhled, starý stock náhled]. Musí sedět s migrací. */
    private const PREVIEWS = [
        'kolik-stoji-webove-stranky' => [
            'kolik-stoji-webove-stranky_nahled.webp',
            'kolik-stoji-webove-stranky_preview.jpg',
        ],
        'potrebuje-vase-firma-webovou-stranku' => [
            'potrebuje-vase-firma-webovou-stranku_nahled.webp',
            'potrebuje-vase-firma-webovou-stranku_preview.jpg',
        ],
        'jak-se-pripravit-na-novy-web' => [
            'jak-se-pripravit-na-novy-web_nahled.webp',
            'jak-definovat-pozadavky-na-vyvoj-webove-stranky_preview.jpg',
        ],
    ];

    // ------------------------------------------------------------------
    // Náhledy
    // ------------------------------------------------------------------

    public function test_preview_files_exist_and_are_square(): void
    {
        foreach (self::PREVIEWS as [$new]) {
            $path = resource_path('img/articles/'.$new);
            $this->assertFileExists($path, "chybí zdrojový soubor {$new}");

            [$w, $h] = getimagesize($path);
            $this->assertSame($w, $h, "{$new} není čtvercový ({$w}×{$h})");
        }
    }

    /**
     * Basename musí být unikátní napříč příponami — `responsive_image_srcsets()`
     * hledá varianty globem `assets/{basename}-*.{avif,webp}`, takže dva zdrojové
     * soubory se stejným basename smíchají varianty dvou různých obrázků.
     */
    public function test_preview_basenames_do_not_collide_with_other_sources(): void
    {
        foreach (self::PREVIEWS as [$new]) {
            $basename = pathinfo($new, PATHINFO_FILENAME);
            $matches = glob(resource_path('img/articles/'.$basename.'.*'));

            $this->assertCount(1, $matches, "basename {$basename} má víc zdrojových souborů: ".implode(', ', $matches));
        }
    }

    public function test_migration_fills_previews_for_every_locale(): void
    {
        $this->seedArticles(null);

        $this->migration()->up();

        foreach (self::PREVIEWS as $slug => [$new]) {
            foreach (['cs', 'en', 'de'] as $locale) {
                $this->assertSame($new, $this->preview($slug, $locale), "{$slug} / {$locale}");
            }
        }
    }

    public function test_migration_also_replaces_the_old_stock_preview(): void
    {
        // Čerstvá instalace: database/sql fixture nese ještě stock názvy.
        $this->seedArticles('stock');

        $this->migration()->up();

        foreach (self::PREVIEWS as $slug => [$new]) {
            $this->assertSame($new, $this->preview($slug, 'cs'), $slug);
        }
    }

    public function test_migration_is_idempotent_and_reversible(): void
    {
        $this->seedArticles(null);

        $migration = $this->migration();
        $migration->up();
        $migration->up();

        foreach (self::PREVIEWS as $slug => [$new]) {
            $this->assertSame($new, $this->preview($slug, 'cs'));
        }

        // down() vrací na NULL, ne na stock ilustraci — ta šla pryč rozhodnutím
        // boardu v OND-268 a vracet ji by bylo proti němu.
        $migration->down();

        foreach (self::PREVIEWS as $slug => [, $old]) {
            $this->assertNull($this->preview($slug, 'cs'));
            $this->assertNotSame($old, $this->preview($slug, 'cs'));
        }
    }

    public function test_migration_leaves_a_manually_set_preview_alone(): void
    {
        $this->seedArticles(null);
        $this->setPreview('kolik-stoji-webove-stranky', 'cs', 'rucne-vybrany.webp');

        $this->migration()->up();

        $this->assertSame('rucne-vybrany.webp', $this->preview('kolik-stoji-webove-stranky', 'cs'));
    }

    public function test_migration_is_inert_on_an_empty_database(): void
    {
        $this->assertSame(0, DB::table('articles')->count());

        $migration = $this->migration();
        $migration->up();
        $migration->down();

        $this->assertSame(0, DB::table('articles')->count());
        $this->assertSame(0, DB::table('article_translations')->count());
    }

    // ------------------------------------------------------------------
    // /o-mne — zrušená sekce „Kde sídlím"
    // ------------------------------------------------------------------

    public function test_about_page_no_longer_repeats_the_billing_address(): void
    {
        foreach (['/o-mne', '/en/about', '/de/ueber-mich'] as $url) {
            $response = $this->get($url);
            $response->assertOk();

            $response->assertDontSee('Dunajovská', false);
            $response->assertDontSee('19231407', false);
            $response->assertDontSee('Kde sídlím', false);
        }
    }

    public function test_contact_page_still_carries_the_address(): void
    {
        $this->get('/kontakt')
            ->assertOk()
            ->assertSee('Dunajovská 116', false)
            ->assertSee('19231407', false);
    }

    // ------------------------------------------------------------------
    // Helpery
    // ------------------------------------------------------------------

    private function migration(): Migration
    {
        return require database_path('migrations/2026_09_23_140000_ond292_nahledy_clanku.php');
    }

    /** @param  string|null  $preview  'stock' = stará ilustrace, null = po OND-268 */
    private function seedArticles(?string $preview): void
    {
        foreach (self::PREVIEWS as $slug => [, $old]) {
            $articleId = DB::table('articles')->insertGetId([
                'published'  => 1,
                'slug'       => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('article_slugs')->insert([
                'article_id' => $articleId,
                'locale'     => 'cs',
                'slug'       => $slug,
                'active'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (['cs', 'en', 'de'] as $locale) {
                DB::table('article_translations')->insert([
                    'article_id'  => $articleId,
                    'locale'      => $locale,
                    'active'      => 1,
                    'title'       => 'OND-292 fixture '.$slug,
                    'img_preview' => $preview === 'stock' ? $old : null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    private function preview(string $slug, string $locale): ?string
    {
        $articleId = DB::table('article_slugs')->where('slug', $slug)->value('article_id');

        return DB::table('article_translations')
            ->where('article_id', $articleId)
            ->where('locale', $locale)
            ->value('img_preview');
    }

    private function setPreview(string $slug, string $locale, string $value): void
    {
        $articleId = DB::table('article_slugs')->where('slug', $slug)->value('article_id');

        DB::table('article_translations')
            ->where('article_id', $articleId)
            ->where('locale', $locale)
            ->update(['img_preview' => $value]);
    }
}
