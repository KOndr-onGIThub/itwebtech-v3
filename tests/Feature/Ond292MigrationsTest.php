<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-292 — zbytky po závěrečném průchodu auditu.
 *
 * Stejná logika jako u Ond256MigrationsTest: na stagingu a produkci se
 * `PortfolioSeeder` po prvním naplnění přeskakuje a texty článků se
 * nepřepisují vůbec, takže se změna dostane ven jedině migrací. Fixture
 * proto vrátí data do produkčního stavu (staré znění) a testuje se reálná
 * migrace, ne kopie dat.
 *
 * Aserce „fixture" na začátku `revertTo*()` jsou schválně: hlídají, že se
 * `docs/portfolio-data.yaml` / `BlogContentSeeder` a tenhle test nerozejdou
 * potichu. Když někdo přepíše znění, spadne to tady s čitelnou hláškou.
 */
class Ond292MigrationsTest extends TestCase
{
    use RefreshDatabase;

    private const FRL_SLUG = 'frl-creator';
    private const FRL_HERO = 'projects/frl-creator/hero-1.jpg';

    /** Textové opravy v případovkách: [slug, pole, staré, nové]. */
    private const YOLK_META = [
        'YOLK — agenturní vývoj na klinikových portálech',
        'YOLK — agenturní vývoj na klinických portálech',
    ];

    /** Blog: [pole, staré znění, nové znění]. */
    private const BLOG_FIXES = [
        ['description', 'Osmnáct let jsem pracoval v logistice Toyoty a psal tam aplikace do provozu.', 'Excel firmě stačí, dokud v něm nepracuje víc lidí'],
        ['perex', 'skončil jako starší specialista v projektovém týmu', 'skončil jako senior specialista v projektovém týmu'],
        ['content_2', 'nechte to být a kupte si radši pořádnou tabulku.', 'nechte to být a zůstaňte u tabulky.'],
        ['title', 'Potřebuje vaše firma web? Někdy ne a řeknu vám kdy', 'Potřebuje vaše firma web? Někdy ne, a řeknu vám kdy'],
        ['content_1', 'srovnávají vás dolů.', 'hraje to proti vám.'],
    ];

    // ------------------------------------------------------------------
    // FRL Creator — duplicitní obrazovka v galerii
    // ------------------------------------------------------------------

    public function test_frl_hero_becomes_thumbnail_so_the_gallery_stops_repeating_itself(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertFrlHeroToGalleryRole();

        $this->migration()->up();

        $this->assertSame('thumbnail', $this->frlHeroType());

        // Galerie detailu (detail-gallery.blade.php) řádky `thumbnail` vynechává.
        $gallery = $this->frlGalleryPaths();
        $this->assertNotContains(self::FRL_HERO, $gallery, 'hero-1 zůstal v galerii jako duplikát gallery-1');
        $this->assertContains('projects/frl-creator/gallery-1.jpg', $gallery);
        $this->assertContains('projects/frl-creator/gallery-2.jpg', $gallery);
    }

    public function test_frl_card_thumbnail_does_not_change(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertFrlHeroToGalleryRole();

        $before = portfolio_card_thumbnail($this->frlScreens())?->path;

        $this->migration()->up();

        // `portfolio_card_thumbnail()` dává `thumbnail` přednost před hero,
        // takže miniatura karty v /projekty i v „Dalších projektech" zůstává
        // na témže souboru. Kdyby se změnila, vypadne z karty branding Toyoty.
        $this->assertSame(self::FRL_HERO, $before);
        $this->assertSame($before, portfolio_card_thumbnail($this->frlScreens())?->path);
    }

    public function test_frl_detail_page_renders_both_remaining_screenshots(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertFrlHeroToGalleryRole();
        $this->migration()->up();

        $this->assertFileExists(resource_path('img/'.self::FRL_HERO));

        $this->get('/projekty/'.self::FRL_SLUG)->assertOk();
    }

    // ------------------------------------------------------------------
    // YOLK — skloňování
    // ------------------------------------------------------------------

    public function test_yolk_declension_is_fixed_in_meta_title_and_alts(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertYolkDeclension();

        $this->migration()->up();

        $this->assertStringContainsString(self::YOLK_META[1], $this->yolkMetaTitle());

        foreach ($this->yolkAlts() as $alt) {
            $this->assertStringNotContainsString('klinikov', $alt, "alt „{$alt}“ má pořád špatný tvar");
        }
    }

    // ------------------------------------------------------------------
    // Blog
    // ------------------------------------------------------------------

    public function test_blog_texts_are_rewritten(): void
    {
        $this->makeBlogFixture();

        $this->migration()->up();

        foreach (self::BLOG_FIXES as [$field, $old, $new]) {
            $value = $this->blogField($field);
            $this->assertStringContainsString($new, $value, "{$field} — nové znění chybí");
            $this->assertStringNotContainsString($old, $value, "{$field} — staré znění zůstalo");
        }

        $this->assertStringContainsString(
            '<h2>Tři situace, kdy peníze nechat v kapse</h2>',
            $this->blogField('content_1'),
        );
    }

    public function test_blog_migration_touches_only_czech(): void
    {
        $this->makeBlogFixture();
        $this->makeBlogFixture('en');

        $this->migration()->up();

        // EN mutace má tytéž řetězce jen ve fixture; migrace je filtruje
        // podle `locale`, takže se jí nesmí dotknout.
        $this->assertStringContainsString(
            'Potřebuje vaše firma web? Někdy ne a řeknu vám kdy',
            $this->blogField('title', 'en'),
        );
    }

    // ------------------------------------------------------------------
    // Idempotence, reverzibilita, inertnost
    // ------------------------------------------------------------------

    public function test_migration_is_idempotent_and_reversible(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertFrlHeroToGalleryRole();
        $this->revertYolkDeclension();
        $this->makeBlogFixture();

        $migration = $this->migration();
        $migration->up();
        $migration->up(); // druhý běh nesmí nic dalšího změnit

        $this->assertSame('thumbnail', $this->frlHeroType());
        $this->assertStringContainsString(self::YOLK_META[1], $this->yolkMetaTitle());
        $this->assertStringContainsString('hraje to proti vám.', $this->blogField('content_1'));

        $migration->down();

        $this->assertSame('hero', $this->frlHeroType());
        $this->assertStringContainsString(self::YOLK_META[0], $this->yolkMetaTitle());
        foreach (self::BLOG_FIXES as [$field, $old, $new]) {
            $value = $this->blogField($field);
            $this->assertStringContainsString($old, $value, "{$field} — down neobnovil původní znění");
            $this->assertStringNotContainsString($new, $value);
        }
    }

    public function test_migration_leaves_manually_edited_text_alone(): void
    {
        $this->makeBlogFixture();

        // Ruční úprava z Filamentu — původní věta už v DB nestojí.
        $this->setBlogField('title', 'Ručně přepsaný titulek.');

        $this->migration()->up();

        $this->assertSame('Ručně přepsaný titulek.', $this->blogField('title'));
    }

    public function test_migration_is_inert_on_an_empty_database(): void
    {
        $this->assertSame(0, DB::table('portfolio_projects')->count());
        $this->assertSame(0, DB::table('articles')->count());

        $migration = $this->migration();
        $migration->up();
        $migration->down();

        $this->assertSame(0, DB::table('portfolio_projects')->count());
        $this->assertSame(0, DB::table('articles')->count());

        // A seeder pořád doplní plné portfolio.
        $this->seed(PortfolioSeeder::class);
        $this->assertGreaterThan(1, DB::table('portfolio_projects')->count());
    }

    // ------------------------------------------------------------------
    // Helpery
    // ------------------------------------------------------------------

    private function migration(): Migration
    {
        return require database_path('migrations/2026_09_23_130000_ond292_zbytky_po_c2.php');
    }

    /** YAML už seeduje `thumbnail`; fixture vrátí produkční stav před migrací. */
    private function revertFrlHeroToGalleryRole(): void
    {
        $this->assertSame(
            'thumbnail',
            $this->frlHeroType(),
            'Fixture: docs/portfolio-data.yaml by měl mít u frl-creator hero-1 jako thumbnail.',
        );

        DB::table('portfolio_project_screenshots')
            ->where('path', self::FRL_HERO)
            ->update(['type' => 'hero']);
    }

    private function revertYolkDeclension(): void
    {
        $this->assertStringContainsString(
            self::YOLK_META[1],
            $this->yolkMetaTitle(),
            'Fixture: docs/portfolio-data.yaml by měl mít u YOLK opravený tvar.',
        );

        DB::table('portfolio_project_translations')
            ->where('project_id', $this->projectId('yolk'))
            ->where('locale', 'cs')
            ->update(['meta_title' => self::YOLK_META[0]]);

        foreach ($this->yolkAltRows() as $row) {
            DB::table('portfolio_project_screenshot_translations')
                ->where('id', $row->id)
                ->update(['alt' => str_replace(['klinického', 'klinických'], ['klinikového', 'klinikových'], $row->alt)]);
        }
    }

    private function frlScreens()
    {
        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::FRL_SLUG))
            ->orderBy('sort_order')
            ->get();
    }

    private function frlHeroType(): ?string
    {
        return DB::table('portfolio_project_screenshots')->where('path', self::FRL_HERO)->value('type');
    }

    /** Co po filtru v detail-gallery.blade.php zbude pro galerii. */
    private function frlGalleryPaths(): array
    {
        return $this->frlScreens()
            ->reject(fn ($s) => $s->type === 'thumbnail')
            ->pluck('path')
            ->all();
    }

    private function yolkMetaTitle(): string
    {
        return (string) DB::table('portfolio_project_translations')
            ->where('project_id', $this->projectId('yolk'))
            ->where('locale', 'cs')
            ->value('meta_title');
    }

    private function yolkAltRows()
    {
        $ids = DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId('yolk'))
            ->pluck('id');

        return DB::table('portfolio_project_screenshot_translations')
            ->whereIn('screenshot_id', $ids)
            ->where('locale', 'cs')
            ->get(['id', 'alt']);
    }

    private function yolkAlts(): array
    {
        return $this->yolkAltRows()->pluck('alt')->all();
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    /**
     * Jeden fixture článek nese všechny sledované věty najednou — migrace
     * je adresuje podle obsahu pole, ne podle id článku, takže na rozdělení
     * do tří článků nezáleží.
     */
    private function makeBlogFixture(string $locale = 'cs'): void
    {
        $articleId = DB::table('articles')->insertGetId([
            'published'  => 1,
            'slug'       => 'ond292-fixture-'.$locale,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('article_translations')->insert([
            'article_id'  => $articleId,
            'locale'      => $locale,
            'active'      => 1,
            'title'       => 'Potřebuje vaše firma web? Někdy ne a řeknu vám kdy',
            'description' => 'Osmnáct let jsem pracoval v logistice Toyoty a psal tam aplikace do provozu. Píšu, podle čeho poznáte, že tabulka firmě přestala stačit.',
            'perex'       => '<blockquote><p>Začínal jsem jako dělník v logistice a skončil jako starší specialista v projektovém týmu.</p></blockquote>',
            'content_1'   => '<p>Když najdou jen profil na Firmy.cz z roku 2019, srovnávají vás dolů.</p><h2>Tři situace, kdy peníze nechte v kapse</h2>',
            'content_2'   => '<p>Když vyjde pár tisíc, nechte to být a kupte si radši pořádnou tabulku.</p>',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    private function blogField(string $field, string $locale = 'cs'): string
    {
        return (string) DB::table('article_translations')->where('locale', $locale)->value($field);
    }

    private function setBlogField(string $field, string $value, string $locale = 'cs'): void
    {
        DB::table('article_translations')->where('locale', $locale)->update([$field => $value]);
    }
}
