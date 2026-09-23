<?php

namespace Tests\Feature;

use App\Models\Article;
use Database\Seeders\BlogLegacyDomainLinksSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-286 — úklid odkazů na `itwebtech.cz/jak-na-to/...` v textech článků.
 *
 * Fixture je doslovné HTML z produkční DB (23. 9.), ne přepsaná náhražka —
 * včetně `target`/`rel` atributů, vnořených `<strong>`/`<em>` a `&nbsp;`,
 * protože právě na nich se regulární výrazy lámou.
 */
class Ond286LegacyLinksTest extends TestCase
{
    use RefreshDatabase;

    private const LIVE_CS = 'potrebuje-vase-firma-webovou-stranku';

    private const LIVE_EN = 'website-redesign-reasons-signals-and-how-to-do-it';

    /** Odkazy na starou doménu, které po seederu nesmí v datech zbýt. */
    private const OLD_DOMAIN = 'itwebtech.cz/jak-na-to/';

    // ------------------------------------------------------------------
    // Bod 1 — živý cíl dostane relativní adresu
    // ------------------------------------------------------------------

    public function test_zivy_cil_dostane_relativni_adresu_vlastniho_webu(): void
    {
        $this->seedFixture();
        (new BlogLegacyDomainLinksSeeder)->run();

        $cs = $this->field(1, 'cs', 'content_1');
        $this->assertStringContainsString(
            '<a href="'.route('cs.article', ['slug' => self::LIVE_CS], absolute: false).'">Potřebuje vaše firma webovou stránku?</a>',
            $cs
        );
        // Interní odkaz nemá důvod otevírat nové okno ani nést nofollow.
        $this->assertStringNotContainsString('nofollow', $cs);

        // U EN článku je cíl EN mutace — tedy `/en/blog/...`, ne CS sekce.
        $en = $this->field(4, 'en', 'perex');
        $this->assertStringContainsString(
            '<a href="'.route('en.article', ['slug' => self::LIVE_EN], absolute: false).'">redesigning an existing website</a>',
            $en
        );
        $this->assertStringContainsString('/en/blog/', $en);
    }

    // ------------------------------------------------------------------
    // Bod 2 — mrtvý cíl uvnitř běžné fráze se rozbalí, věta zůstane celá
    // ------------------------------------------------------------------

    public function test_mrtvy_cil_se_rozbali_a_text_zustane(): void
    {
        $this->seedFixture();
        (new BlogLegacyDomainLinksSeeder)->run();

        $this->assertStringContainsString(
            'Udělejte si také alespoň rychlou analýzu konkurence.',
            $this->field(1, 'cs', 'content_1')
        );

        // Vnořené formátování uvnitř odkazu musí přežít i s okolními značkami.
        $this->assertStringContainsString(
            'jak přesně funguje </em><em>SEO a proč je tak důležité</em><em> jej integrovat',
            $this->field(11, 'cs', 'content_mid')
        );
    }

    // ------------------------------------------------------------------
    // Bod 3 — vazba „přečtěte si článek X" se přepisuje (znění z OND-288)
    // ------------------------------------------------------------------

    public function test_veta_doporucujici_stazeny_clanek_se_prepise(): void
    {
        $this->seedFixture();
        (new BlogLegacyDomainLinksSeeder)->run();

        $a4 = $this->field(4, 'en', 'content_1');
        $this->assertStringNotContainsString('Read more about SEO in this article', $a4);
        $this->assertStringContainsString('<p>Search engines are where most visitors start', $a4);
        // Odstavec uvozuje odrážky pod sebou — nesmí zmizet celý.
        $this->assertStringContainsString('<ul><li><p>What keywords should my Page target?', $a4);

        $a10 = $this->field(10, 'en', 'content_2');
        $this->assertStringNotContainsString("don't miss my article", $a10);
        $this->assertStringContainsString('In practice that means a site that loads fast', $a10);

        $a13 = $this->field(13, 'en', 'bonus');
        $this->assertStringNotContainsString('I recommend reading the article', $a13);
        // Blok teď končí výzvou ke konzultaci, celý odstavec s doporučením je pryč.
        $this->assertStringEndsWith('turn them into customers.</p>', $a13);
    }

    // ------------------------------------------------------------------
    // Past: `jak-na-to` je i koncovka jednoho slugu
    // ------------------------------------------------------------------

    public function test_slug_koncici_na_jak_na_to_zustane_nedotcen(): void
    {
        $this->seedFixture();
        (new BlogLegacyDomainLinksSeeder)->run();

        $value = $this->field(13, 'cs', 'extra');

        // Relativní odkaz na vlastní web se seederu netýká vůbec.
        $this->assertStringContainsString(
            '<a href="/jak-na-to/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">redesign webu</a>',
            $value
        );
        // Absolutní odkaz na starou doménu se rozbalí, slug se nesmí pokazit.
        $this->assertStringContainsString('proč stojí za to zvážit redesign webu.', $value);
    }

    // ------------------------------------------------------------------
    // Celkový výsledek, idempotence, inertnost
    // ------------------------------------------------------------------

    public function test_po_behu_nezustane_zadny_odkaz_na_starou_domenu(): void
    {
        $this->seedFixture();
        $this->assertGreaterThan(0, $this->countOldDomainLinks());

        (new BlogLegacyDomainLinksSeeder)->run();

        $this->assertSame(0, $this->countOldDomainLinks());
    }

    public function test_druhy_beh_uz_nic_nemeni(): void
    {
        $this->seedFixture();

        (new BlogLegacyDomainLinksSeeder)->run();
        $afterFirst = $this->snapshot();

        (new BlogLegacyDomainLinksSeeder)->run();

        $this->assertSame($afterFirst, $this->snapshot());
    }

    public function test_na_nenaplnene_db_je_inertni(): void
    {
        (new BlogLegacyDomainLinksSeeder)->run();

        $this->assertSame(0, DB::table('article_translations')->count());
    }

    public function test_migrace_je_na_nenaplnene_db_inertni(): void
    {
        $migration = require database_path('migrations/2026_09_23_110000_ond286_odkazy_na_starou_domenu.php');
        $migration->up();

        $this->assertSame(0, DB::table('articles')->count());
    }

    // ------------------------------------------------------------------
    // Fixture — doslovné výřezy z produkční DB
    // ------------------------------------------------------------------

    private function seedFixture(): void
    {
        $old = 'https://itwebtech.cz/jak-na-to/';

        $rows = [
            [1, 'cs', 'content_1',
                '<p>Pokud zatím nejste rozhodnuti, zda vůbec webovou stránku potřebujete, doporučuji přečíst si článek '
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.self::LIVE_CS.'">Potřebuje vaše firma webovou stránku?</a>'
                .' .</p><p><strong>POZOR!</strong> Udělejte si také alespoň rychlou '
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.'zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">analýzu konkurence</a>'
                .'. Nechcete přece, aby si vás lidé pletli s konkurencí.</p>'],

            [4, 'en', 'perex',
                '<blockquote><p>Imagine you are defining the requirements for a new website or '
                .'<a target="_blank" rel="noopener noreferrer nofollow" href="'.$old.self::LIVE_EN.'">redesigning an existing website</a>'
                .', it is essential that you ask yourself the right questions.</p></blockquote>'],

            [4, 'en', 'content_1',
                '<h2><strong>5. Search Engine Optimization (SEO)</strong></h2><p>Read more about SEO in this article '
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.'co-je-soe-a-proc-je-tak-dulezite">What is SEO and why is it so important? Get a basic overview.</a></p>'
                .'<ul><li><p>What keywords should my Page target?</p></li><li><p>Do I have an SEO strategy? (regular analyses)</p></li></ul>'],

            [10, 'en', 'content_2',
                '<p>SEO (Search Engine Optimization) is more important than ever. With the growing number of online businesses, '
                ."it's essential that your website appears in top positions on search engines to attract organic traffic. "
                ."If you want to learn more about SEO, don't miss my article "
                .'<a target="_blank" rel="noopener noreferrer nofollow" href="'.$old.'what-is-seo-and-why-is-it-so-important">What is SEO</a>.</p>'
                .'<h3>4. Personalization and targeted content</h3>'],

            [11, 'cs', 'content_mid',
                '<p><em>SEO, neboli optimalizace pro vyhledávače, je jako GPS pro internet. Ale jak přesně funguje </em>'
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.'co-je-seo-a-proc-je-tak-dulezite"><em>SEO a proč je tak důležité</em></a>'
                .'<em> jej integrovat do designu a obsahu vašeho webu?</em></p>'],

            [13, 'en', 'bonus',
                '<p>Take your online presence to a new level that will attract visitors and turn them into customers.</p>'
                .'<p>If you want to know a little more about the elements of effective web design, I recommend reading the article '
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.'how-to-create-a-successful-website">How to Create a Successful Website</a>.</p>'],

            // Past z OND-286: `jak-na-to` je i koncovka slugu. Relativní odkaz
            // na vlastní web musí zůstat, absolutní se rozbalí bez poškození slugu.
            [13, 'cs', 'extra',
                '<p>V článku <a href="/jak-na-to/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">redesign webu</a> najdete, '
                .'proč stojí za to zvážit '
                .'<a target="" rel="noopener noreferrer nofollow" href="'.$old.'redesign-webovych-stranek-duvody-signaly-a-jak-na-to">redesign webu</a>.</p>'],
        ];

        foreach ($rows as [$articleId, $locale, $column, $html]) {
            $article = Article::find($articleId);

            if (! $article) {
                $article = new Article(['slug' => 'clanek-'.$articleId, 'published' => true]);
                $article->id = $articleId;
                $article->save();
            }

            $translation = DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where('locale', $locale)
                ->first();

            if ($translation) {
                DB::table('article_translations')->where('id', $translation->id)->update([$column => $html]);

                continue;
            }

            DB::table('article_translations')->insert([
                'article_id' => $articleId,
                'locale'     => $locale,
                'active'     => true,
                'title'      => 'Titulek '.$articleId.' '.$locale,
                $column      => $html,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function field(int $articleId, string $locale, string $column): string
    {
        return (string) DB::table('article_translations')
            ->where('article_id', $articleId)
            ->where('locale', $locale)
            ->value($column);
    }

    private function countOldDomainLinks(): int
    {
        $columns = ['title', 'description', 'perex', 'content_1', 'content_mid', 'content_2', 'bonus', 'extra'];
        $count = 0;

        foreach (DB::table('article_translations')->get() as $row) {
            foreach ($columns as $column) {
                $count += substr_count((string) ($row->$column ?? ''), self::OLD_DOMAIN);
            }
        }

        return $count;
    }

    /** @return array<string, string> */
    private function snapshot(): array
    {
        $columns = ['title', 'description', 'perex', 'content_1', 'content_mid', 'content_2', 'bonus', 'extra'];
        $snapshot = [];

        foreach (DB::table('article_translations')->orderBy('id')->get() as $row) {
            foreach ($columns as $column) {
                $snapshot["{$row->article_id}.{$row->locale}.{$column}"] = (string) ($row->$column ?? '');
            }
        }

        return $snapshot;
    }
}
