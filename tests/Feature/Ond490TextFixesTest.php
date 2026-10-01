<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Database\Seeders\BlogContentDeSeeder;
use Database\Seeders\BlogContentEnSeeder;
use Database\Seeders\BlogContentSeeder;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-490 (znění OND-491, dokument `texty`) — opravy textů webu.
 *
 * Negativní aserce hledají stará znění ve VYKRESLENÉ stránce, ne v lang
 * souboru: přejmenovaný klíč by jinak prošel naprázdno. Ke každé stránce
 * patří i pozitivní aserce nového znění, takže „nic se nevykreslilo“
 * neprojde jako úspěch.
 */
class Ond490TextFixesTest extends TestCase
{
    use RefreshDatabase;

    /** url => [nová znění, která na stránce musí být] */
    private const PRESENT = [
        '/'               => ['Jsem Ondra Kriška', 'Nezávazně probereme', '&mdash; Ondra Kriška', 'Vinařství Antoš',
            'Ať je zakázka velká, nebo malá', 'Když uvidím, že vám pomoct neumím',
            '<a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>'],
        '/o-mne'          => ['Jmenuju se Ondra Kriška', 'Mluvím česky, písemně se domluvíme v jakémkoli jazyce.',
            'protože to ovlivňuje víc věcí než samotný web'],
        '/cenik'          => ['Rychlá domluva nad podklady', 'Logo a firemní barvy, které už máte'],
        '/kontakt'        => ['jen Ondra'],
        '/projekty'       => ['pro realitní makléřky, zubní ordinaci, automobilku a další',
            'Zákazníci se vás ptají na věci, které by měli najít na webu.'],
        '/recenze'        => ['<em>5,0 z 5</em> na Googlu a Firmy.cz'],
        '/en/'            => ['How it turned out', '<a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>'],
        '/en/price'       => ['Quick back-and-forth on materials'],
        '/en/contact'     => ['no bot'],
        '/de/'            => ['Wie es ausging', '<a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>'],
        '/de/preisliste'  => ['Schnelle Abstimmung bei den Unterlagen'],
        '/de/kontakt'     => ['kein Bot'],
    ];

    /** Stará znění, která nesmí být na žádné z výše uvedených stránek. */
    private const GONE = [
        'vlastním kódu', 'vlastní kód',
        '26 hodnocení', '23+', 'Nezávazně proberu', 'starý web jim ji kazil', 'Chtěli dlouhodobě růst',
        'co říká klient', 'nepasujeme', 'víc než sto tisíc', 'týden na podrobnou schůzku',
        'pokrývače', 'Nabídka služeb je nejasná', 'Chybí jasný postup', 'jednoduchý prezentační',
        'rozbitý odkaz', 'Úplné a rychlé odpovědi', 'Obsah si po zaškolení', 'CRM',
        'Mluvím a píšu česky', 'nemám jak ovlivnit',
        'my own code', '26 ratings', '23+ projects', 'a roofer', 'Complete, quick answers', 'No CRM',
        'eigenen Code', 'eigener Code', '26 Bewertungen', 'Dachdecker', 'Vollständige und schnelle Antworten', 'Kein CRM',
    ];

    /** Bod 3: „Ondra“ jen v češtině, en/de dál píšou „Ondřej“. */
    private const GONE_CS = ['Jsem Ondřej Kriška', 'Jmenuju se Ondřej', '&mdash; Ondřej Kriška', 'jen Ondřej', 'Ondřejovi'];

    public function test_old_wording_is_gone_and_new_is_rendered(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PRESENT as $url => $present) {
            $html = $this->get($url)->assertOk()->getContent();

            foreach ($present as $text) {
                $this->assertStringContainsString($text, $html, "{$url}: chybí nové znění „{$text}“.");
            }
            // Bod 8: VP Industry na homepage ne, mezi projekty zůstává.
            $gone = in_array($url, ['/', '/en/', '/de/'], true) ? [...self::GONE, 'VP Industry', 'vp-industry'] : self::GONE;
            if (! str_starts_with($url, '/en/') && ! str_starts_with($url, '/de/')) {
                $gone = [...$gone, ...self::GONE_CS];
            }
            foreach ($gone as $text) {
                $this->assertStringNotContainsString($text, $html, "{$url}: pořád stojí staré znění „{$text}“.");
            }
        }
    }

    /** Bod 17: „Má to smysl řešit teď?“ má 6 důvodů ve všech jazycích. */
    public function test_projects_fit_section_has_six_reasons(): void
    {
        foreach (['/projekty' => 'cs', '/en/projects' => 'en', '/de/projekte' => 'de'] as $url => $locale) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertCount(6, trans('projects.fit.items', [], $locale));

            foreach (trans('projects.fit.items', [], $locale) as $item) {
                $this->assertStringContainsString('<li>'.e($item).'</li>', $html, "{$url}: chybí důvod „{$item}“.");
            }
        }
    }

    /** Body 20 a 21: „Snižuje cenu“ má stejně položek jako „Zvedá cenu“. */
    public function test_price_compare_lists_are_balanced(): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            $this->assertCount(6, trans('price.compare.up.items', [], $locale), $locale);
            $this->assertCount(6, trans('price.compare.down.items', [], $locale), $locale);
        }
    }

    /** Bod 15: e-mail pod formulářem je odkaz, zbytek věty zůstává escapovaný text. */
    public function test_form_note_email_is_a_mailto_link(): void
    {
        foreach (['/' => 'cs', '/en/' => 'en', '/de/' => 'de'] as $url => $locale) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertSame(1, preg_match('~<p class="pd-form__note">(.*?)</p>~s', $html, $m), $url);
            $this->assertStringContainsString('<a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>', $m[1], $url);
            $this->assertSame(strip_tags($m[1]), e(trans('home.inline_form.note', [], $locale)), $url);
        }
    }

    /**
     * Migrace případovek: `down()` na datech ze současného YAML vrátí stará
     * znění (dokládá, že podmínky sedí na skutečné texty), `up()` je vrátí
     * přesně na stav YAML a druhý `up()` nic nezmění.
     */
    public function test_case_study_migration_round_trip(): void
    {
        $this->seed(PortfolioSeeder::class);
        $migration = require database_path('migrations/2026_10_01_100000_ond490_texty_pripadovek.php');

        $fresh = $this->portfolioSnapshot();
        $this->assertStringNotContainsString('růst', $this->vp('cs', 'subtitle'));
        $this->assertSame(4, $this->vpOutcomeCount());

        $migration->down();
        $this->assertSame('Web připravený růst, jakmile klient zapne obsah', $this->vp('cs', 'subtitle'));
        $this->assertSame(5, $this->vpOutcomeCount());
        $this->assertStringContainsString('Záchrana starého webu', $this->translation('cyklocentrum', 'cs', 'challenge'));

        $migration->up();
        $this->assertEquals($fresh, $this->portfolioSnapshot());

        $migration->up();
        $this->assertEquals($fresh, $this->portfolioSnapshot());
    }

    /**
     * Migrace článků zapíše jen uvedená pole a jen ze seederu. Ostatní pole
     * dotčených článků zůstanou, jak byla.
     */
    public function test_article_migration_writes_only_listed_fields_and_page_renders(): void
    {
        $slugs = ['cs' => 'kolik-stoji-webove-stranky', 'en' => 'how-much-does-a-website-cost', 'de' => 'was-kostet-eine-website'];
        $article = new Article(['slug' => $slugs['cs'], 'published' => true]);
        $article->id = 3;
        $article->save();

        foreach ($slugs as $locale => $slug) {
            $article->translations()->create([
                'locale'      => $locale,
                'active'      => true,
                'title'       => "Titulek {$locale}",
                'description' => "Popis {$locale}",
                'perex'       => '<p>Starý perex.</p>',
                'content_1'   => '<p>Starý text 1.</p>',
                'content_2'   => '<p>Starý text 2.</p>',
                'img_preview' => 'preview-3.jpg',
                'img_main'    => 'main-3.jpg',
            ]);
            ArticleSlug::create(['article_id' => 3, 'locale' => $locale, 'slug' => $slug, 'active' => true]);
        }

        (require database_path('migrations/2026_10_01_100100_ond490_texty_clanku.php'))->up();

        $seeders = ['cs' => new BlogContentSeeder, 'en' => new BlogContentEnSeeder, 'de' => new BlogContentDeSeeder];
        $paths = ['cs' => '/zapisky/', 'en' => '/en/blog/', 'de' => '/de/blog/'];

        foreach ($seeders as $locale => $seeder) {
            $row = DB::table('article_translations')->where('article_id', 3)->where('locale', $locale)->first();
            $this->assertSame($seeder->articles()[3]['content_1'], $row->content_1, $locale);
            $this->assertSame($seeder->articles()[3]['content_2'], $row->content_2, $locale);
            $this->assertSame("Titulek {$locale}", $row->title, $locale);
            $this->assertSame('<p>Starý perex.</p>', $row->perex, $locale);

            $html = $this->get($paths[$locale].$slugs[$locale])->assertOk()->getContent();
            foreach (['jednoduchý prezentační', 'rozbitý odkaz', 'Úplné a rychlé', 'Obsah si plníte sami', 'vlastním kódem',
                'my own code', 'Complete, quick answers', 'eigenem Code'] as $gone) {
                $this->assertStringNotContainsString($gone, $html, "{$locale}: {$gone}");
            }
        }

        $cs = $this->get('/zapisky/kolik-stoji-webove-stranky')->getContent();
        $this->assertStringContainsString('Weby programuju od základu.', $cs);
        $this->assertStringContainsString('Rychlá domluva nad podklady.', $cs);
    }

    private function vp(string $locale, string $field): ?string
    {
        return $this->translation('vp-industry', $locale, $field);
    }

    private function translation(string $slug, string $locale, string $field): ?string
    {
        $projectId = DB::table('portfolio_projects')->where('slug', $slug)->value('id');

        return DB::table('portfolio_project_translations')->where('project_id', $projectId)->where('locale', $locale)->value($field);
    }

    private function vpOutcomeCount(): int
    {
        $projectId = DB::table('portfolio_projects')->where('slug', 'vp-industry')->value('id');

        return DB::table('portfolio_project_outcomes')->where('project_id', $projectId)->count();
    }

    /** Texty překladů a výstupy všech projektů bez časových razítek a id výstupů. */
    private function portfolioSnapshot(): array
    {
        $texts = DB::table('portfolio_project_translations')->orderBy('id')->get()
            ->map(fn ($r) => collect((array) $r)->except(['created_at', 'updated_at'])->all())->all();

        $outcomes = DB::table('portfolio_project_outcomes as o')
            ->join('portfolio_project_outcome_translations as t', 't.outcome_id', '=', 'o.id')
            ->orderBy('o.project_id')->orderBy('o.sort_order')->orderBy('t.locale')
            ->get(['o.project_id', 'o.sort_order', 't.locale', 't.label', 't.value', 't.description'])
            ->map(fn ($r) => (array) $r)->all();

        return [$texts, $outcomes];
    }
}
