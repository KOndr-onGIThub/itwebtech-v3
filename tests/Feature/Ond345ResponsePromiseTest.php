<?php

namespace Tests\Feature;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-345 — slib odpovědi „nejpozději následující pracovní den".
 *
 * Dvě různé věci, proto dva bloky testů:
 *  1. `lang/` — myšlenka nesmí nikde zůstat v původním znění („do 24 hodin").
 *     `home_legacy.php` je z toho vyjmutý, ty soubory mizí v OND-342.
 *  2. Migrace `article_translations.content_2` — tatáž věta žije i v DB
 *     (článek „Kolik stojí web na míru") a seeder ji na produkci nepřepíše.
 *     Fixture napodobí produkční stav, testuje se reálná migrace.
 */
class Ond345ResponsePromiseTest extends TestCase
{
    use RefreshDatabase;

    /** Původní znění, které po OND-345 nesmí nikde v `lang/` zůstat. */
    private const OLD_PHRASES = ['24 hodin', '24 hours', '24 Stunden'];

    /** locale => [původní věta v článku, nová věta v článku] */
    private const ARTICLE_SENTENCES = [
        'cs' => [
            'Ozvu se do 24 hodin v pracovní dny a probereme to.',
            'Ozvu se nejpozději následující pracovní den a probereme to.',
        ],
        'de' => [
            'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir gehen es durch.',
            'Ich melde mich spätestens am nächsten Arbeitstag und wir gehen es durch.',
        ],
        'en' => [
            'I will get back to you within 24 hours on business days and we will go through it.',
            'I will get back to you by the next business day and we will go through it.',
        ],
    ];

    // ------------------------------------------------------------------
    // lang/
    // ------------------------------------------------------------------

    public function test_no_lang_file_still_promises_a_reply_within_24_hours(): void
    {
        $offenders = [];

        foreach ($this->langFiles() as $file) {
            $contents = file_get_contents($file);

            foreach (self::OLD_PHRASES as $phrase) {
                if (str_contains($contents, $phrase)) {
                    $offenders[] = str_replace(base_path().'/', '', $file)." — „{$phrase}\"";
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Staré znění slibu odpovědi zůstalo v lang souborech:\n".implode("\n", $offenders),
        );
    }

    public function test_the_new_promise_is_present_in_all_three_locales(): void
    {
        $expected = [
            'cs' => 'Ozvu se nejpozději následující pracovní den.',
            'en' => 'I\'ll get back to you by the next business day.',
            'de' => 'Ich melde mich spätestens am nächsten Arbeitstag.',
        ];

        foreach ($expected as $locale => $sentence) {
            $this->assertStringContainsString(
                $sentence,
                __('contact.hero.subline', [], $locale),
                "Nový slib chybí v `lang/{$locale}/contact.php` (hero.subline).",
            );

            $this->assertStringContainsString(
                $sentence,
                __('contact.open_hours', [], $locale),
                "Nový slib chybí v `lang/{$locale}/contact.php` (open_hours).",
            );
        }
    }

    // ------------------------------------------------------------------
    // Migrace textu článku
    // ------------------------------------------------------------------

    public function test_article_migration_rewrites_the_promise_in_all_three_locales(): void
    {
        foreach (self::ARTICLE_SENTENCES as $locale => [$old]) {
            $this->makeArticle($locale, "<p>Nadpis. {$old} Konec odstavce.</p>");
        }

        $this->articleMigration()->up();

        foreach (self::ARTICLE_SENTENCES as $locale => [$old, $new]) {
            $content = $this->articleContent($locale);
            $this->assertStringContainsString($new, $content, "{$locale}: nové znění chybí");
            $this->assertStringNotContainsString($old, $content, "{$locale}: staré znění zůstalo");
            // Zbytek odstavce zůstal nedotčený.
            $this->assertStringContainsString('<p>Nadpis. ', $content, "{$locale}: migrace sáhla i jinam");
            $this->assertStringContainsString(' Konec odstavce.</p>', $content, "{$locale}: migrace sáhla i jinam");
        }
    }

    public function test_article_migration_is_idempotent_and_reversible(): void
    {
        foreach (self::ARTICLE_SENTENCES as $locale => [$old]) {
            $this->makeArticle($locale, "<p>{$old}</p>");
        }

        $migration = $this->articleMigration();
        $migration->up();
        $migration->up(); // druhý běh nesmí nic dalšího změnit

        foreach (self::ARTICLE_SENTENCES as $locale => [, $new]) {
            $this->assertStringContainsString($new, $this->articleContent($locale));
        }

        $migration->down();

        foreach (self::ARTICLE_SENTENCES as $locale => [$old, $new]) {
            $this->assertStringContainsString($old, $this->articleContent($locale));
            $this->assertStringNotContainsString($new, $this->articleContent($locale));
        }
    }

    /**
     * Na nenaplněné DB musí být migrace inertní — jinak by shodila ochranu
     * seederů a na čerstvé instalaci by se články nenaseedovaly.
     */
    public function test_article_migration_is_inert_on_an_empty_database(): void
    {
        $this->assertSame(0, DB::table('articles')->count());

        $migration = $this->articleMigration();
        $migration->up();
        $migration->down();

        $this->assertSame(0, DB::table('articles')->count());
        $this->assertSame(0, DB::table('article_translations')->count());
    }

    // ------------------------------------------------------------------
    // Helpery
    // ------------------------------------------------------------------

    /** @return list<string> */
    private function langFiles(): array
    {
        $files = [];

        foreach (['cs', 'en', 'de'] as $locale) {
            foreach (glob(base_path("lang/{$locale}/*.php")) as $file) {
                // OND-342 tyhle soubory odstraní, zadání na ně výslovně nesahá.
                if (str_contains(basename($file), 'home_legacy')) {
                    continue;
                }

                $files[] = $file;
            }
        }

        $this->assertNotEmpty($files, 'Nenašel jsem žádné lang soubory — test by jinak prošel naprázdno.');

        return $files;
    }

    private function articleMigration(): Migration
    {
        return require database_path('migrations/2026_09_26_100000_ond345_slib_odpovedi_v_clancich.php');
    }

    private function makeArticle(string $locale, string $content2): void
    {
        $articleId = DB::table('articles')->insertGetId([
            'published'  => 1,
            'slug'       => 'ond345-test-'.$locale,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('article_translations')->insert([
            'article_id' => $articleId,
            'locale'     => $locale,
            'active'     => 1,
            'title'      => 'OND-345 fixture',
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
