<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-256 — tři data migrace vlny 1.
 *
 * Na stagingu i produkci se `PortfolioSeeder` po prvním naplnění DB přeskakuje
 * a texty článků se nepřepisují vůbec, takže změny textů a obrázků se tam
 * dostanou jedině migrací. Fixture proto vždy napodobí produkční stav
 * (data v DB v původní podobě) a testuje se reálná migrace, ne kopie dat.
 *
 * Druhá polovina testů hlídá to, co u těchhle migrací rozbilo už starší task:
 * na nenaplněné DB musí být migrace inertní, jinak shodí ochranu
 * `PortfolioSeeder` a na čerstvé instalaci se nenaseedují ostatní projekty.
 */
class Ond256MigrationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Opravy z bodu 9: slug => [pole, typo, správně].
     *
     * Migrace jich má šest, tady je jich pět. Chybí `josefopa.challenge`
     * („visačku" → „vizitku"): vlna 2 (OND-267, redline OND-261 položka jo-1)
     * tu větu nahradila celou — místo „Klient potřeboval vizitku stavební
     * firmy, která bude působit…" tam dnes stojí „Klient potřeboval, aby jeho
     * stavební firma v Německu působila…". Slovo „vizitku" v tom poli už není,
     * takže ho fixture nemá jak vrátit na „visačku" a není co testovat.
     *
     * Záměrně se tím **nemění migrace ani data** — migrace 2026_09_22_110000
     * na produkci proběhla ještě nad starým zněním a je podmíněná, takže dnes
     * je její šestá položka trvale no-op. Zdrojem pravdy je redline, ne test.
     */
    private const TYPO_FIXES = [
        'clanek-motorkari-cz' => ['result', 'motopotálu', 'motoportálu'],
        'barana'              => ['description', 'Postavil jsem premiové', 'Postavil jsem prémiové'],
        'frl-creator'         => ['challenge', 'chybovo a se zbytečnou', 'chybově a se zbytečnou'],
        'choccoboard'         => ['solution', 'se k ní dostaneš odkudkoli', 'se k ní dostanete odkudkoli'],
        'nove-interiery'      => ['result', 'si zákazníci dopředu vědomí, jak', 'zákazníci dopředu vědí, jak'],
    ];

    private const ESHOP_SLUG = 'pitarena-eshop';
    private const ESHOP_PATH = 'projects/pitarena-eshop/hero-1.webp';

    // ------------------------------------------------------------------
    // Bod 9 — textové opravy případovek
    // ------------------------------------------------------------------

    public function test_typo_migration_fixes_the_case_study_texts_it_still_owns(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToTypos();

        $this->typoMigration()->up();

        foreach (self::TYPO_FIXES as $slug => [$field, $typo, $fixed]) {
            $text = $this->translationField($slug, $field);
            $this->assertStringContainsString($fixed, $text, "{$slug}.{$field}");
            $this->assertStringNotContainsString($typo, $text, "{$slug}.{$field} — typo zůstalo");
        }
    }

    public function test_typo_migration_is_idempotent_and_reversible(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToTypos();

        $migration = $this->typoMigration();
        $migration->up();
        $migration->up(); // druhý běh nesmí nic dalšího změnit

        foreach (self::TYPO_FIXES as $slug => [$field, , $fixed]) {
            $this->assertStringContainsString($fixed, $this->translationField($slug, $field));
        }

        $migration->down();

        foreach (self::TYPO_FIXES as $slug => [$field, $typo, $fixed]) {
            $text = $this->translationField($slug, $field);
            $this->assertStringContainsString($typo, $text, "{$slug}.{$field} — down neobnovil původní tvar");
            $this->assertStringNotContainsString($fixed, $text);
        }
    }

    public function test_typo_migration_leaves_manually_edited_text_alone(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->revertToTypos();

        // Ruční úprava z Filamentu — původní věta už v DB nestojí.
        $this->setTranslationField('barana', 'description', 'Ručně přepsaný text bez překlepu.');

        $this->typoMigration()->up();

        $this->assertSame(
            'Ručně přepsaný text bez překlepu.',
            $this->translationField('barana', 'description'),
        );
    }

    // ------------------------------------------------------------------
    // Bod 6 — screenshot e-shopu
    // ------------------------------------------------------------------

    public function test_eshop_screenshot_migration_adds_hero_with_alt_in_all_locales(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->removeEshopScreenshots();

        $this->eshopMigration()->up();

        $shots = DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::ESHOP_SLUG))
            ->get();

        $this->assertCount(1, $shots);
        $this->assertSame(self::ESHOP_PATH, $shots->first()->path);
        $this->assertSame('hero', $shots->first()->type);

        $alts = DB::table('portfolio_project_screenshot_translations')
            ->where('screenshot_id', $shots->first()->id)
            ->pluck('alt', 'locale');

        foreach (['cs', 'en', 'de'] as $locale) {
            $this->assertNotEmpty($alts[$locale] ?? null, "alt pro {$locale}");
        }
    }

    public function test_eshop_screenshot_migration_is_idempotent_and_reversible(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->removeEshopScreenshots();

        $migration = $this->eshopMigration();
        $migration->up();
        $migration->up();

        $this->assertSame(1, $this->eshopScreenshotCount());

        $altsBefore = DB::table('portfolio_project_screenshot_translations')->count();

        $migration->down();

        $this->assertSame(0, $this->eshopScreenshotCount());

        // Down smaže jen ty tři alt řádky, co migrace sama založila —
        // překlady snímků ostatních projektů zůstávají.
        $this->assertSame(
            $altsBefore - 3,
            DB::table('portfolio_project_screenshot_translations')->count(),
        );
        $this->assertGreaterThan(0, DB::table('portfolio_project_screenshot_translations')->count());
    }

    public function test_eshop_project_detail_renders_the_screenshot(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->removeEshopScreenshots();
        $this->eshopMigration()->up();

        // Zdrojový soubor musí existovat, jinak by se obrázek nevykreslil.
        $this->assertFileExists(resource_path('img/'.self::ESHOP_PATH));

        $this->get('/projekty/'.self::ESHOP_SLUG)
            ->assertOk()
            ->assertSee('PitAréna – e-shop s pitbike motorkami YCF a náhradními díly', false);
    }

    // ------------------------------------------------------------------
    // Bod 3 — slib reakční doby uvnitř textu článku
    // ------------------------------------------------------------------

    public function test_article_migration_rewrites_the_response_time_sentence(): void
    {
        $this->makeArticle('cs', '<p>Napište mi. Ozvu se do dvou pracovních dnů a probereme to.</p>');
        $this->makeArticle('de', '<p>Ich melde mich innerhalb von zwei Werktagen und wir gehen es durch.</p>');

        $this->articleMigration()->up();

        $this->assertStringContainsString(
            'Ozvu se do 24 hodin v pracovní dny a probereme to.',
            $this->articleContent('cs'),
        );
        $this->assertStringContainsString(
            'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir gehen es durch.',
            $this->articleContent('de'),
        );

        // Zbytek odstavce zůstal nedotčený.
        $this->assertStringContainsString('<p>Napište mi. ', $this->articleContent('cs'));
    }

    public function test_article_migration_is_idempotent_and_reversible(): void
    {
        $this->makeArticle('cs', '<p>Ozvu se do dvou pracovních dnů a probereme to.</p>');

        $migration = $this->articleMigration();
        $migration->up();
        $migration->up();

        $this->assertStringContainsString('24 hodin v pracovní dny', $this->articleContent('cs'));

        $migration->down();

        $this->assertStringContainsString('dvou pracovních dnů', $this->articleContent('cs'));
        $this->assertStringNotContainsString('24 hodin', $this->articleContent('cs'));
    }

    // ------------------------------------------------------------------
    // Všechny tři musí být inertní na nenaplněné DB
    // ------------------------------------------------------------------

    public function test_all_migrations_are_inert_on_an_empty_database(): void
    {
        $this->assertSame(0, DB::table('portfolio_projects')->count());
        $this->assertSame(0, DB::table('articles')->count());

        foreach ([$this->typoMigration(), $this->eshopMigration(), $this->articleMigration()] as $migration) {
            $migration->up();
            $migration->down();
        }

        // Nic nevzniklo — jinak by migrace shodila ochranu PortfolioSeederu
        // a na čerstvé instalaci by se ostatní projekty nenaseedovaly.
        $this->assertSame(0, DB::table('portfolio_projects')->count());
        $this->assertSame(0, DB::table('portfolio_project_screenshots')->count());
        $this->assertSame(0, DB::table('articles')->count());

        // A seeder pořád doplní plné portfolio.
        $this->seed(PortfolioSeeder::class);
        $this->assertGreaterThan(1, DB::table('portfolio_projects')->count());
    }

    // ------------------------------------------------------------------
    // Helpery
    // ------------------------------------------------------------------

    private function typoMigration(): Migration
    {
        return require database_path('migrations/2026_09_22_110000_ond256_opravy_textu_pripadovek.php');
    }

    private function eshopMigration(): Migration
    {
        return require database_path('migrations/2026_09_22_120000_ond256_screenshot_pitarena_eshop.php');
    }

    private function articleMigration(): Migration
    {
        return require database_path('migrations/2026_09_22_100000_ond256_reakcni_doba_v_clancich.php');
    }

    /**
     * Vrátí texty do stavu před opravou (YAML už má správné znění).
     *
     * Ta první asercí je schválně — je to pojistka proti tichému rozejití
     * fixture a `docs/portfolio-data.yaml`. Když někdo přepíše text, který
     * si tenhle test drží, spadne to tady s jasnou hláškou, a ne až na
     * nesrozumitelné aserci o kus dál. Přesně tohle se stalo u `josefopa`
     * po vlně 2 (OND-267) — viz komentář u TYPO_FIXES.
     */
    private function revertToTypos(): void
    {
        foreach (self::TYPO_FIXES as $slug => [$field, $typo, $fixed]) {
            $text = $this->translationField($slug, $field);
            $this->assertStringContainsString(
                $fixed,
                $text,
                "Fixture: {$slug}.{$field} — YAML by měl mít opravené znění.",
            );
            $this->setTranslationField($slug, $field, str_replace($fixed, $typo, $text));
        }
    }

    private function removeEshopScreenshots(): void
    {
        $ids = DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::ESHOP_SLUG))
            ->pluck('id');

        DB::table('portfolio_project_screenshot_translations')->whereIn('screenshot_id', $ids)->delete();
        DB::table('portfolio_project_screenshots')->whereIn('id', $ids)->delete();
    }

    private function eshopScreenshotCount(): int
    {
        return DB::table('portfolio_project_screenshots')
            ->where('project_id', $this->projectId(self::ESHOP_SLUG))
            ->count();
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    private function translationField(string $slug, string $field): string
    {
        return (string) DB::table('portfolio_project_translations')
            ->where('project_id', $this->projectId($slug))
            ->where('locale', 'cs')
            ->value($field);
    }

    private function setTranslationField(string $slug, string $field, string $value): void
    {
        DB::table('portfolio_project_translations')
            ->where('project_id', $this->projectId($slug))
            ->where('locale', 'cs')
            ->update([$field => $value]);
    }

    private function makeArticle(string $locale, string $content2): void
    {
        $articleId = DB::table('articles')->insertGetId([
            'published'  => 1,
            'slug'       => 'ond256-test-'.$locale,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('article_translations')->insert([
            'article_id' => $articleId,
            'locale'     => $locale,
            'active'     => 1,
            'title'      => 'OND-256 fixture',
            'content_2'  => $content2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function articleContent(string $locale): string
    {
        return (string) DB::table('article_translations')
            ->where('locale', $locale)
            ->value('content_2');
    }
}
