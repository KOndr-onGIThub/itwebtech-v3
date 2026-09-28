<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-387 — předpatička zrušená, navigace a claim v patičce (§3c základu
 * podstránek, OND-379).
 *
 * Tři tvrzení, každé se dá porušit jinak:
 *  1. Na všech jedenácti stránkách (homepage + deset podstránek) ve všech třech
 *     jazycích je v patičce navigace se sedmi odkazy a claim — a žádná
 *     předpatička ani tlačítko. Právní stránky jsou v seznamu schválně:
 *     OND-266 jim předpatičku přidal kvůli navigaci, a ta nesmí zmizet s ní.
 *  2. Navigace mluví jazykem stránky — aria-label i popisky z vlastního
 *     locale, ne převzaté z `cs`.
 *  3. `lang/` — klíče předpatičky jsou pryč ve všech locale a klíče patičky
 *     mají všude stejný tvar. Klíč chybějící v jednom jazyce by se na
 *     stránce vytiskl jako vlastní jméno.
 */
class Ond387FooterNavigationTest extends TestCase
{
    use RefreshDatabase;

    // OND-442: „Recenze“ za „Projekty“.
    private const NAV = ['home', 'projects', 'reviews', 'price', 'blog', 'about', 'contact'];

    private const ARTICLE_SLUGS = [
        'cs' => 'kolik-stoji-webove-stranky',
        'en' => 'how-much-does-a-website-cost',
        'de' => 'wieviel-kostet-eine-webseite',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);

        $article = Article::create(['slug' => self::ARTICLE_SLUGS['en'], 'published' => true]);

        foreach (self::ARTICLE_SLUGS as $locale => $slug) {
            $article->translations()->create([
                'locale'      => $locale,
                'active'      => true,
                'title'       => 'Title '.$locale,
                'description' => 'Desc '.$locale,
                'perex'       => 'Perex '.$locale,
            ]);
            ArticleSlug::create([
                'article_id' => $article->id,
                'locale'     => $locale,
                'slug'       => $slug,
                'active'     => true,
            ]);
        }
    }

    /** @return array<string, string> název => URL, pro daný jazyk */
    private function pages(string $locale): array
    {
        $pages = [];
        foreach (['home', 'about', 'contact', 'price', 'projects', 'reviews', 'blog', 'privacy', 'cookies'] as $name) {
            $pages[$name] = lroute($name, $locale);
        }
        // `pitarena` je značka — slug je jazyk-neutrální ve všech locale.
        $pages['project'] = route("{$locale}.project", ['url' => 'pitarena']);
        $pages['article'] = route("{$locale}.article", ['slug' => self::ARTICLE_SLUGS[$locale]]);

        return $pages;
    }

    /** Obsah `<footer class="footer-bar">…</footer>`. */
    private function footer(string $body, string $where): string
    {
        $this->assertSame(
            1,
            preg_match('~<footer class="footer-bar">(.*?)</footer>~s', $body, $m),
            "Na {$where} chybí patička.",
        );

        return $m[1];
    }

    // ------------------------------------------------------------------
    // 1. + 2. Vykreslené stránky
    // ------------------------------------------------------------------

    public function test_every_page_has_the_footer_navigation_and_no_prefooter(): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            foreach ($this->pages($locale) as $name => $url) {
                $where = "{$name} ({$locale})";
                $body = $this->get($url)->assertOk()->getContent();

                $this->assertStringNotContainsString('footer-prefooter', $body, "Na {$where} zůstala předpatička.");

                $footer = $this->footer($body, $where);

                $this->assertStringContainsString(
                    'aria-label="'.e(trans('layout.footer.nav_label', [], $locale)).'"',
                    $footer,
                    "Navigace v patičce na {$where} nemá aria-label ze svého jazyka.",
                );

                preg_match_all('~<a href="([^"]+)"\s+class="footer-bar__nav-link"[^>]*>([^<]+)</a>~', $footer, $links, PREG_SET_ORDER);

                $this->assertSame(
                    array_map(fn ($r) => [lroute($r, $locale), trans("layout.nav.{$r}", [], $locale)], self::NAV),
                    array_map(fn ($l) => [html_entity_decode($l[1]), html_entity_decode($l[2])], $links),
                    "Navigace v patičce na {$where} nemá sedm odkazů ve správném pořadí a jazyce.",
                );

                $this->assertStringContainsString(
                    e(trans('layout.footer.tagline', [], $locale)),
                    $footer,
                    "Na {$where} chybí v patičce claim.",
                );

                // Žádné prodejní tlačítko v patičce (§3b) — ani pod právním textem (§E).
                $this->assertDoesNotMatchRegularExpression('~class="[^"]*\bbtn\b~', $footer, "V patičce na {$where} je tlačítko.");
            }
        }
    }

    public function test_the_current_page_is_marked_in_the_footer_navigation(): void
    {
        $footer = $this->footer($this->get('/kontakt')->assertOk()->getContent(), '/kontakt');

        $this->assertMatchesRegularExpression(
            '~href="'.preg_quote(lroute('contact', 'cs'), '~').'"\s+class="footer-bar__nav-link"\s+aria-current="page"~',
            $footer,
        );
        $this->assertSame(1, substr_count($footer, 'aria-current="page"'));
    }

    // ------------------------------------------------------------------
    // 3. lang/
    // ------------------------------------------------------------------

    public function test_prefooter_keys_are_gone_and_footer_keys_share_one_shape(): void
    {
        $shapes = [];

        foreach (['cs', 'en', 'de'] as $locale) {
            $layout = require lang_path("{$locale}/layout.php");

            $this->assertArrayNotHasKey('prefooter', $layout, "lang/{$locale}/layout.php má pořád `prefooter`.");

            $shapes[$locale] = array_keys($layout['footer']);
            sort($shapes[$locale]);

            foreach (['tagline', 'nav_label'] as $key) {
                $this->assertNotSame('', trim($layout['footer'][$key] ?? ''), "lang/{$locale}: prázdné `footer.{$key}`.");
            }
        }

        $this->assertSame($shapes['cs'], $shapes['en']);
        $this->assertSame($shapes['cs'], $shapes['de']);

        // Aria-label je přeložený, ne převzatý z češtiny.
        $labels = array_map(fn ($l) => trans('layout.footer.nav_label', [], $l), ['cs', 'en', 'de']);
        $this->assertCount(3, array_unique($labels));
    }
}
