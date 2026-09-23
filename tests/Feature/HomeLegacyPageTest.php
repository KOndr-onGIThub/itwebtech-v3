<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * OND-306: původní homepage odklopená na `/puvodni-homepage`.
 *
 * Tohle je zámek na zmrazenou verzi. Během přestavby domovské stránky
 * (OND-305) se `pages/home.blade.php` i `lang/*_/home.php` přepisují od
 * základu — tenhle test je jediné, co nám řekne, že jsme přitom omylem
 * nerozbili starou verzi, kterou Ondřej používá k porovnání.
 *
 * Titulky jsou schválně natvrdo, ne přes `__('home_legacy.…')` — proti
 * překladovému klíči by test prošel i poté, co někdo texty přepíše.
 *
 * Smazat spolu se zbytkem lešení, až bude nová homepage schválená.
 */
class HomeLegacyPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Hlavní titulek staré homepage ke dni 23. 9. 2026, per locale.
     */
    private const FROZEN_HEADINGS = [
        '/puvodni-homepage'    => 'Weby a aplikace <em>na míru</em>.<br>Postavím vám je sám, na vlastním kódu.',
        '/en/puvodni-homepage' => 'Websites and applications <em>built to fit</em>.<br>I build them myself, on my own code.',
        '/de/puvodni-homepage' => 'Websites und Anwendungen <em>nach Maß</em>.<br>Ich baue sie selbst, mit eigenem Code.',
    ];

    public function test_legacy_homepage_renders_frozen_heading_in_all_locales(): void
    {
        foreach (self::FROZEN_HEADINGS as $url => $heading) {
            $response = $this->get($url);

            $response->assertOk();
            $this->assertStringContainsString($heading, $response->getContent(), "Titulek se nerenderuje na {$url}");
        }
    }

    /**
     * Interní srovnávací stránka nesmí konkurovat `/` ve vyhledávači —
     * ani meta robots, ani canonical mířící omylem na homepage.
     */
    public function test_legacy_homepage_is_excluded_from_search(): void
    {
        foreach (array_keys(self::FROZEN_HEADINGS) as $url) {
            $body = $this->get($url)->getContent();

            $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $body);
            $this->assertStringContainsString('<link rel="canonical" href="'.url($url).'">', $body);
            $this->assertStringContainsString('<link rel="alternate" hreflang="cs" href="'.url('/puvodni-homepage').'">', $body);
        }
    }

    public function test_legacy_homepage_is_not_in_sitemap(): void
    {
        Cache::forget(config('sitemap.cache_key'));

        $body = $this->get('/sitemap.xml')->getContent();

        $this->assertStringNotContainsString('puvodni-homepage', $body);
    }

    /**
     * Ondřej dostane adresu do komentáře na kartě. Na veřejném webu na ni
     * nesmí vést odkaz — je to interní srovnávací stránka.
     */
    public function test_no_public_page_links_to_legacy_homepage(): void
    {
        foreach (['/', '/kontakt', '/projekty'] as $url) {
            $this->assertStringNotContainsString(
                'puvodni-homepage',
                $this->get($url)->getContent(),
                "Na {$url} vede odkaz na starou homepage",
            );
        }
    }

    /**
     * Pojistka, že odklopení nezměnilo chování `/` — ta zůstává indexovaná.
     */
    public function test_live_homepage_stays_indexable(): void
    {
        $body = $this->get('/')->getContent();

        $this->assertStringContainsString('<meta name="robots" content="index, follow">', $body);
    }
}
