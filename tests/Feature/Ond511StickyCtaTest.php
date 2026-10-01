<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-511 — spodní mobilní lišta „Poptávka" žije jen uprostřed stránky.
 *
 * Kdy se ukáže, řídí `resources/js/sticky-cta.js` podle zón v šablonách:
 * `data-sticky-cta="start"` (tlačítko hera / hlava podstránky) a
 * `data-sticky-cta="hide"` (formulář, závěrečná výzva, patička). Server
 * ji vykresluje skrytou (`inert`, bez `is-visible`) — při načtení nesmí
 * bliknout. Na /kontakt, ochraně údajů a cookies se nevykresluje vůbec.
 */
class Ond511StickyCtaTest extends TestCase
{
    use RefreshDatabase;

    private const BAR = '<div class="mobile-bottom-bar" role="region"';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    private function html(string $url): string
    {
        return $this->get($url)->assertOk()->getContent();
    }

    /** Otevírací tag lišty. */
    private function barTag(string $html, string $where): string
    {
        $this->assertSame(1, substr_count($html, 'class="mobile-bottom-bar"'), "[$where] lišta jednou");
        $this->assertSame(1, preg_match('~<div class="mobile-bottom-bar"[^>]*>~', $html, $m), "[$where] tag lišty");

        return $m[0];
    }

    /** Počet prvků s daným `data-sticky-cta`. */
    private function zones(string $html, string $kind): int
    {
        return substr_count($html, 'data-sticky-cta="' . $kind . '"');
    }

    public function test_bar_renders_hidden_by_default(): void
    {
        foreach (['/', '/en', '/de', '/cenik', '/o-mne', '/projekty', '/recenze', '/zapisky'] as $url) {
            $tag = $this->barTag($this->html($url), $url);

            $this->assertStringContainsString(' inert>', $tag, "[$url] skrytá = inert");
            $this->assertStringNotContainsString('is-visible', $tag, "[$url] bez is-visible");
        }
    }

    public function test_bar_is_not_rendered_on_contact_and_legal_pages(): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            foreach (['contact', 'privacy', 'cookies'] as $page) {
                $url = lroute($page, $locale);
                $this->assertStringNotContainsString('mobile-bottom-bar', $this->html($url), "[$locale] $page");
            }
        }
    }

    public function test_homepage_marks_hero_button_form_and_footer(): void
    {
        foreach (['/', '/en', '/de'] as $url) {
            $html = $this->html($url);

            $this->assertSame(1, $this->zones($html, 'start'), "[$url] start");
            $this->assertMatchesRegularExpression('~<a href="#[^"]+" class="pd-cta" data-analytics="hero_cta_primary_click" data-sticky-cta="start">~', $html, "[$url] start = tlačítko hera");

            // Sekce „Poptávka", formulář v ní a patička. Inline odkaz na
            // formulář v textu (služby) zónou není.
            $this->assertSame(3, $this->zones($html, 'hide'), "[$url] hide");
            $this->assertMatchesRegularExpression('~<section class="pd-section" id="[^"]+" data-sticky-cta="hide">~', $html, "[$url] sekce Poptávka");
            $this->assertMatchesRegularExpression('~x-data="contactForm\([^"]*"\s+data-sticky-cta="hide">~', $html, "[$url] formulář");
            $this->assertStringContainsString('<footer class="footer-bar" data-sticky-cta="hide">', $html, "[$url] patička");
        }
    }

    public function test_subpages_mark_head_closing_cta_and_footer(): void
    {
        $project = PortfolioProject::published()->firstOrFail();

        $pages = [
            '/o-mne'    => 'about-cta',
            '/cenik'    => 'price-cta',
            '/projekty' => 'projects-cta',
            '/recenze'  => 'reviews-cta',
            $project->detailUrl('cs') => 'project-cta',
            '/en/price' => 'price-cta',
        ];

        foreach ($pages as $url => $closing) {
            $html = $this->html($url);

            $this->assertSame(1, $this->zones($html, 'start'), "[$url] start");
            $this->assertMatchesRegularExpression('~<section class="pd-section pd-page-head"[^>]*data-sticky-cta="start"~', $html, "[$url] start = hlava");
            $this->assertMatchesRegularExpression('~<section class="pd-section pd-(?:close|about-cta)" data-pdd="' . $closing . '" data-sticky-cta="hide">~', $html, "[$url] závěr");
            $this->assertSame(2, $this->zones($html, 'hide'), "[$url] závěr + patička");
            $locale = str_starts_with($url, '/en/') ? 'en' : 'cs';
            $this->barTag($html, $url);
            $this->assertStringContainsString('href="' . lroute('contact', $locale) . '"', $this->between($html, self::BAR, '</a>'), "[$url] lišta vede na /kontakt");
        }

        // Blog bez závěrečné výzvy: hlava a patička.
        $html = $this->html('/zapisky');
        $this->assertSame(1, $this->zones($html, 'start'), '[/zapisky] start');
        $this->assertSame(1, $this->zones($html, 'hide'), '[/zapisky] patička');
    }

    public function test_drawer_reports_its_state_for_the_bar(): void
    {
        $this->assertStringContainsString(
            '<div x-data="{ open: false }" x-effect="$dispatch(\'nav-drawer\', open)">',
            $this->html('/o-mne'),
        );
    }

    private function between(string $haystack, string $from, string $to): string
    {
        $start = strpos($haystack, $from);
        $this->assertNotFalse($start, "Chybí `$from`.");
        $end = strpos($haystack, $to, $start);

        return substr($haystack, $start, $end - $start);
    }
}
