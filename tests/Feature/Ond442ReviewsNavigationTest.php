<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-442 — „Recenze“ v navigaci.
 *
 *  1. Horní menu: „Úvod“ pryč (na úvod vede logo), „Recenze“ hned za
 *     „Projekty“ — ve všech třech jazycích, s popiskem z vlastního locale.
 *  2. Drawer: „Úvod“ zůstává, „Recenze“ za „Projekty“.
 *  3. Na /recenze je „Recenze“ aktivní v menu i v draweru, a nic jiného.
 *  4. `layout.nav.reviews` je ve všech třech `lang/{cs,en,de}/layout.php`.
 */
class Ond442ReviewsNavigationTest extends TestCase
{
    use RefreshDatabase;

    private const HEADER = ['projects', 'reviews', 'price', 'blog', 'about', 'contact'];

    private const DRAWER = ['home', 'projects', 'reviews', 'price', 'blog', 'about', 'contact'];

    private const LABELS = ['cs' => 'Recenze', 'en' => 'Reviews', 'de' => 'Bewertungen'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    /** @return list<array{0: string, 1: string, 2: bool}> [href, popisek, aktivní] */
    private function links(string $body, string $nav, string $link, string $where): array
    {
        $this->assertSame(
            1,
            preg_match('~<nav class="'.$nav.'"[^>]*>(.*?)</nav>~s', $body, $m),
            "Na {$where} chybí <nav class=\"{$nav}\">.",
        );

        preg_match_all('~<a href="([^"]+)"\s+class="'.$link.'( '.$link.'--active)?\s*"[^>]*>\s*([^<]+?)\s*</a>~', $m[1], $found, PREG_SET_ORDER);

        return array_map(fn ($l) => [html_entity_decode($l[1]), html_entity_decode($l[3]), $l[2] !== ''], $found);
    }

    /** @param list<string> $routes */
    private function expected(array $routes, string $locale, ?string $active): array
    {
        return array_map(
            fn ($r) => [lroute($r, $locale), trans("layout.nav.{$r}", [], $locale), $r === $active],
            $routes,
        );
    }

    public function test_header_and_drawer_list_reviews_after_projects_in_every_locale(): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            foreach (['home' => null, 'projects' => 'projects', 'reviews' => 'reviews'] as $page => $active) {
                $where = "{$page} ({$locale})";
                $body = $this->get(lroute($page, $locale))->assertOk()->getContent();

                $this->assertSame(
                    $this->expected(self::HEADER, $locale, $active),
                    $this->links($body, 'navbar__nav', 'navbar__link', $where),
                    "Horní menu na {$where} nemá správné položky, pořadí nebo aktivní stav.",
                );

                $this->assertSame(
                    $this->expected(self::DRAWER, $locale, $page === 'home' ? 'home' : $active),
                    $this->links($body, 'drawer__nav', 'drawer__link', $where),
                    "Drawer na {$where} nemá správné položky, pořadí nebo aktivní stav.",
                );
            }
        }
    }

    public function test_reviews_link_points_to_the_localized_reviews_page(): void
    {
        $paths = ['cs' => '/recenze', 'en' => '/en/reviews', 'de' => '/de/bewertungen'];

        foreach ($paths as $locale => $path) {
            $body = $this->get(lroute('home', $locale))->assertOk()->getContent();

            foreach (['navbar__nav' => 'navbar__link', 'drawer__nav' => 'drawer__link'] as $nav => $link) {
                $reviews = array_values(array_filter(
                    $this->links($body, $nav, $link, "home ({$locale})"),
                    fn ($l) => parse_url($l[0], PHP_URL_PATH) === $path,
                ));

                $this->assertCount(1, $reviews, "V {$nav} ({$locale}) není právě jeden odkaz na {$path}.");
                $this->assertSame(self::LABELS[$locale], $reviews[0][1]);
            }

            $this->get($path)->assertOk();
        }
    }

    public function test_reviews_nav_key_exists_in_every_locale(): void
    {
        foreach (self::LABELS as $locale => $label) {
            $layout = require lang_path("{$locale}/layout.php");

            $this->assertSame($label, $layout['nav']['reviews'] ?? null, "lang/{$locale}/layout.php: `nav.reviews`.");
            $this->assertSame(trans('reviews.eyebrow', [], $locale), $label, "Popisek v menu ({$locale}) se liší od nadpisu stránky.");
        }
    }
}
