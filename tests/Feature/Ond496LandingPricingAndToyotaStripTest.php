<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-496 — odpovědi Ondřeje na OND-490 (1. 10. 2026).
 *
 *  1. Pruh na úvodní stránce: „18 let praxe" → „18 let v Toyotě" (cs/en/de).
 *     Očekávané texty jsou tu natvrdo, ne přes `__()`, aby test chytil i
 *     změnu v `lang/`.
 *  2. Reklamní stránka /lp/webove-stranky-na-miru ukazuje stejné balíčky a
 *     ceny jako /cenik — obojí se čte z `price.*`. Staré balíčky
 *     Standard/Custom a startovní web za 25 000 Kč jsou pryč.
 */
class Ond496LandingPricingAndToyotaStripTest extends TestCase
{
    use RefreshDatabase;

    private const STRIP = [
        '/'    => '18 let v Toyotě',
        '/en/' => '18 years at Toyota',
        '/de/' => '18 Jahre bei Toyota',
    ];

    private function landingUrl(): string
    {
        return '/'.config('landing.preview_path');
    }

    public function test_homepage_strip_says_years_at_toyota_in_every_locale(): void
    {
        foreach (self::STRIP as $url => $text) {
            $body = $this->get($url)->assertOk()->getContent();

            $start = strpos($body, 'class="pd-strip__list"');
            $this->assertNotFalse($start, "{$url}: pruh .pd-strip__list chybí.");
            $strip = substr($body, $start, strpos($body, '</ul>', $start) - $start);

            $this->assertStringContainsString($text, $strip, "{$url}: v pruhu chybí „{$text}“.");
        }

        $this->get('/')->assertDontSee('18 let praxe');
        $this->get('/en/')->assertDontSee('18 years of experience');
        $this->get('/de/')->assertDontSee('18 Jahre Erfahrung');
    }

    /** OND-497: stejná oprava v pruhu štítků na reklamní stránce. */
    public function test_landing_chips_say_years_at_toyota(): void
    {
        $body = $this->get($this->landingUrl())->assertOk()->getContent();

        $start = strpos($body, 'class="landing-badge-list"');
        $this->assertNotFalse($start, 'Pruh .landing-badge-list chybí.');
        $chips = substr($body, $start, strpos($body, '</div>', $start) - $start);

        $this->assertStringContainsString('18 let v Toyotě', $chips);
        $this->assertStringNotContainsString('18 let zkušeností', $chips);
    }

    public function test_landing_shows_the_same_packages_and_prices_as_price_page(): void
    {
        $landing = $this->get($this->landingUrl())->assertOk();
        $price = $this->get('/cenik')->assertOk();

        $shared = [trans('price.intro', [], 'cs'), trans('price.note', [], 'cs')];

        foreach (trans('price.tiers', [], 'cs') as $tier) {
            $shared[] = $tier['name'];
            $shared[] = $tier['scope'];
            $shared[] = $tier['desc'];
            array_push($shared, ...$tier['features']);
        }

        foreach ($shared as $text) {
            $price->assertSee($text);
            $landing->assertSee($text);
        }

        // Pořadí balíčků je stejné jako na ceníku.
        $landing->assertSeeInOrder(array_column(trans('price.tiers', [], 'cs'), 'name'));
    }

    public function test_landing_has_no_old_packages_or_prices(): void
    {
        $landing = $this->get($this->landingUrl())->assertOk();

        foreach (['Standard', 'Custom', 'Startovní web', '25 000', '95 000'] as $old) {
            $landing->assertDontSee($old);
        }
    }

    public function test_landing_price_faq_answer_is_the_price_page_sentence(): void
    {
        $this->get($this->landingUrl())
            ->assertOk()
            ->assertSeeInOrder(['Kolik stojí web na míru?', trans('price.intro', [], 'cs')]);
    }
}
