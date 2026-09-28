<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-407 — detail článku ve slovníku nové homepage (předloha OND-406).
 *
 * Obsah článku z DB se tiskne beze změny, jen v jiném obalu (`.pd-prose`).
 * Vrstva hloubky zůstává vypnutá (OND-251). Konec vede na další článek
 * podle `position`, za posledním první, a jen na články s aktivním slugem
 * v dané locale — jinak by odkaz vedl na 404.
 */
class Ond407ArticleDetailTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    private const BODY = '<p>Odstavec s <strong>tučným</strong> a <a href="/cenik">odkazem</a>.</p><h2>Nadpis</h2><ul><li>bod</li></ul>';

    /** @var array<int, Article> */
    private array $articles = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Pozice schválně mimo pořadí vzniku: kruh má jít po `position`.
        foreach ([['a', 2], ['b', 1], ['c', 3]] as [$key, $position]) {
            $article = Article::create(['slug' => "clanek-{$key}", 'published' => true, 'position' => $position]);
            foreach (self::LOCALES as $locale) {
                $article->translations()->create([
                    'locale'      => $locale,
                    'active'      => true,
                    'title'       => "Web a z čeho {$key} {$locale}",
                    'description' => str_repeat("Popis {$key} {$locale}. ", 20),
                    'perex'       => "<blockquote><p>Perex {$key} {$locale}</p></blockquote>",
                    'content_1'   => self::BODY,
                ]);
                ArticleSlug::create([
                    'article_id' => $article->id,
                    'locale'     => $locale,
                    'slug'       => "clanek-{$key}-{$locale}",
                    // Článek `c` nemá v de aktivní slug → v de kruhu chybí.
                    'active'     => ! ($key === 'c' && $locale === 'de'),
                ]);
            }
            $this->articles[$key] = $article;
        }
    }

    private function url(string $key, string $locale): string
    {
        return lroute('blog', $locale) . "/clanek-{$key}-{$locale}";
    }

    public function test_article_renders_in_new_vocabulary_without_depth_layer(): void
    {
        foreach (self::LOCALES as $locale) {
            $body = $this->get($this->url('a', $locale))->assertOk()->getContent();

            $this->assertStringContainsString('<div class="pd">', $body, $locale);
            $this->assertStringNotContainsString('pd--depth', $body, "OND-251 {$locale}");
            $this->assertStringContainsString('<div class="pd-prose">', $body, $locale);
            $this->assertStringContainsString('<div class="pd-prose__lead">', $body, $locale);
            // Obsah z DB bajtově beze změny.
            $this->assertStringContainsString(self::BODY, $body, $locale);
            $this->assertStringContainsString("<p>Perex a {$locale}</p>", $body, $locale);
            // Zpětný odkaz je nadřádek, stará dvojitá šipka a CTA tlačítka pryč.
            $this->assertStringContainsString('<a href="' . lroute('blog', $locale) . '" class="pd-eyebrow__back">', $body, $locale);
            $this->assertStringNotContainsString('class="back-link"', $body, $locale);
            $this->assertStringNotContainsString('article_author_contact_click', $body, $locale);
            $this->assertStringContainsString('class="pd-author"', $body, $locale);
            // Popis se v hlavě netiskne, zůstává v meta description (zkrácený na 155).
            $this->assertStringNotContainsString('class="page-hero__subline"', $body, $locale);
            $this->assertStringContainsString(
                '<meta name="description" content="' . e(\Illuminate\Support\Str::limit(str_repeat("Popis a {$locale}. ", 20), 155, '…')) . '">',
                $body,
                $locale
            );
        }
    }

    public function test_title_gets_brand_suffix_in_title_og_and_twitter(): void
    {
        $body = $this->get($this->url('a', 'en'))->assertOk()->getContent();
        $title = e('Web a z čeho a en | ' . config('app.name'));

        $this->assertStringContainsString("<title>{$title}</title>", $body);
        $this->assertStringContainsString('<meta property="og:title"        content="' . $title . '">', $body);
        $this->assertStringContainsString('<meta name="twitter:title"       content="' . $title . '">', $body);
    }

    public function test_czech_h1_binds_single_letter_words_only_in_cs(): void
    {
        $cs = $this->get($this->url('a', 'cs'))->assertOk()->getContent();
        $this->assertStringContainsString("Web a\u{00A0}z\u{00A0}čeho a\u{00A0}cs</h1>", $cs);

        $en = $this->get($this->url('a', 'en'))->assertOk()->getContent();
        $this->assertStringContainsString('Web a z čeho a en</h1>', $en);
    }

    public function test_end_links_to_next_article_by_position_and_wraps(): void
    {
        // Pořadí podle position: b(1) → a(2) → c(3) → b.
        $ring = ['b' => 'a', 'a' => 'c', 'c' => 'b'];
        foreach ($ring as $from => $to) {
            $body = $this->get($this->url($from, 'cs'))->assertOk()->getContent();
            $this->assertStringContainsString('<a href="' . $this->url($to, 'cs') . '" class="pd-next__link">', $body, "{$from} → {$to}");
        }

        // V de článek `c` nemá aktivní slug: kruh je b → a → b.
        $body = $this->get($this->url('a', 'de'))->assertOk()->getContent();
        $this->assertStringContainsString('<a href="' . $this->url('b', 'de') . '" class="pd-next__link">', $body);
        $this->assertStringNotContainsString('clanek-c-', $body);

        // Ceník a realizace jsou u každého článku.
        $this->assertStringContainsString('<a href="' . lroute('price', 'de') . '" class="pd-next__link">', $body);
        $this->assertStringContainsString('<a href="' . lroute('projects', 'de') . '" class="pd-next__link">', $body);
    }

    public function test_bonus_renders_as_aside_only_when_filled(): void
    {
        $body = $this->get($this->url('a', 'cs'))->assertOk()->getContent();
        $this->assertStringNotContainsString('class="pd-aside"', $body);

        $this->articles['a']->translations()->where('locale', 'cs')->update(['bonus' => '<p>Bonus</p>']);
        $body = $this->get($this->url('a', 'cs'))->assertOk()->getContent();
        $this->assertMatchesRegularExpression('#<aside class="pd-aside">\s*<p>Bonus</p>\s*</aside>#', $body);
    }
}
