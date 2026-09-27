<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-411 — tři tiché odkazy z těla homepage (Ondřejův bod 2 „homepage je
 * ostrov", návrh OND-348, znění en/de OND-410).
 *
 * Hlavní tvrzení:
 *  1. Z `<main>` homepage vede odkaz na stránku o mně, na článek o přípravě
 *     a na výpis zápisků — v každém jazyce na adresu v tom jazyce.
 *     Že slug článku z lang (`cta_more_slug`) na produkci vrací 200 bez
 *     přesměrování, se ověřilo proti produkční DB — seedery článků jen
 *     upravují existující řádky a samy o sobě ho v testu nevyrobí.
 *  2. Nové tlačítko nepřibylo: `.pd-cta` v `<main>` jsou pořád 2.
 */
class Ond411HomepageQuietLinksTest extends TestCase
{
    use RefreshDatabase;

    private const HOMES = ['cs' => '/', 'en' => '/en/', 'de' => '/de/'];

    private function main(string $locale): string
    {
        $body = $this->get(self::HOMES[$locale])->assertOk()->getContent();
        $start = strpos($body, '<main');
        $this->assertNotFalse($start, "Homepage {$locale} nemá <main>.");

        return substr($body, $start, strpos($body, '</main>', $start) - $start);
    }

    /** @return array<string, array{0: string, 1: string}> místo → [href, text odkazu] */
    private function expectedLinks(string $locale): array
    {
        $article = route("{$locale}.article", ['slug' => trans('home.how_i_work.cta_more_slug', [], $locale)]);

        return [
            'B' => [lroute('about', $locale), e(trans('home.toyota.more_inline_about', [], $locale))],
            'C' => [$article, e(trans('home.how_i_work.cta_more_article', [], $locale))],
            'D' => [lroute('blog', $locale), e(trans('home.faq.more_inline_blog', [], $locale))],
        ];
    }

    public function test_main_links_to_about_article_and_notes_in_every_locale(): void
    {
        foreach (array_keys(self::HOMES) as $locale) {
            $main = $this->main($locale);

            foreach ($this->expectedLinks($locale) as $spot => [$href, $text]) {
                $this->assertStringContainsString('<a href="' . $href . '">' . $text . '</a>', $main, "{$spot} {$locale}");
            }
        }
    }

    public function test_no_new_button_in_main(): void
    {
        foreach (array_keys(self::HOMES) as $locale) {
            preg_match_all('/class="(?:[^"]*\s)?pd-cta(?:\s[^"]*)?"/', $this->main($locale), $m);
            $this->assertCount(2, $m[0], "Počet .pd-cta v <main> ({$locale}).");
        }
    }
}
