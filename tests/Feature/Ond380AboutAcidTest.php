<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-380 — /o-mne ve slovníku nové homepage (1. z 9 podstránek).
 *
 * Návrh a měření: OND-365. Tenhle test hlídá tři sliby té karty, protože
 * podle téhle stránky se portuje zbylých osm a každý z nich se dá porušit
 * nepozorností, kterou snímek neodhalí:
 *
 *  1. ŠABLONA NESMÍ SÁHNOUT NA OBSAH. Všech devět jazykových klíčů stojí
 *     na stránce doslova a ve stejném pořadí — ve všech třech jazycích.
 *     Pozor na hranici tohohle tvrzení: porovnává se vykreslená stránka
 *     proti `lang/`, takže test chytí šablonu, která klíč zahodí, zkrátí,
 *     přeloží po svém nebo prohodí — NE změnu samotného `lang/` (tu dělá
 *     board a je vidět v diffu). Důkaz „obsah se nezměnil ani o slovo"
 *     je prázdný `git diff lang/`, ne tenhle soubor.
 *  2. NASVÍCENÍ NESMÍ ZMIZET. Kužely z `hloubka.css` §E visí na atributech
 *     `data-pdd` PŘÍMÝCH DĚTÍ obalu `.pd--depth-sub` (selektor `> section[…]`),
 *     stín pod videem na třídě `.about-intro__video`. Obojí je neviditelné
 *     v markupu a tiché při rozbití — světlo prostě zhasne.
 *  3. STARÝ SLOVNÍK JE PRYČ. `.page-hero`, pruhy `.section-alt` / `.section-cta`
 *     a zaoblené `.btn-primary` jsou přesně ty prvky, které podstránku
 *     prozrazovaly. Kdyby se některý vrátil, je celý převod k ničemu.
 *
 * `/en/about` je tu schválně: Jack ověřoval jen češtinu a němčinu.
 */
class Ond380AboutAcidTest extends TestCase
{
    use RefreshDatabase;

    /** locale => cesta */
    private const PAGES = [
        'cs' => '/o-mne',
        'en' => '/en/about',
        'de' => '/de/ueber-mich',
    ];

    public function test_all_three_locales_render(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->get($url)->assertOk();
        }
    }

    /**
     * Obsah `<main>`. Lišta a předpatička jsou sitewide a nesou vlastní
     * slovník — navigační odkaz „O mně" i `btn btn-primary` v tlačítku
     * poptávky. Bez tohohle ořezu by test hlásil vadu na cizím kódu
     * a zároveň by propustil pořadí klíčů (odkaz v liště stojí nad obsahem).
     */
    private function mainOf(string $html): string
    {
        $from = mb_strpos($html, '<main');
        $to = mb_strpos($html, '</main>');

        $this->assertNotFalse($from, 'stránka nemá <main>');
        $this->assertNotFalse($to, 'stránka nemá </main>');

        return mb_substr($html, $from, $to - $from);
    }

    /**
     * Devět klíčů, doslova a v pořadí. `sections` je pole čtyř kapitol,
     * takže řetězců je třináct — ale klíče jsou ty, které karta jmenuje.
     */
    public function test_every_language_key_renders_verbatim_and_in_order(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->app->setLocale($locale);

            $expected = [__('about.subheading'), __('about.heading'), __('about.intro')];
            foreach (__('about.sections') as $section) {
                $expected[] = $section['heading'];
                $expected[] = $section['text'];
            }
            $expected[] = __('about.cta_text');
            $expected[] = __('about.cta_button');

            $main = $this->mainOf($this->get($url)->assertOk()->getContent());
            $text = html_entity_decode(strip_tags($main), ENT_QUOTES, 'UTF-8');

            $cursor = 0;
            foreach ($expected as $i => $string) {
                $at = mb_strpos($text, $string, 0, 'UTF-8');

                $this->assertNotFalse(
                    $at,
                    "[$locale] klíč #$i se na stránce nevyskytuje doslova: ".mb_substr($string, 0, 60)
                );
                $this->assertGreaterThanOrEqual(
                    $cursor,
                    $at,
                    "[$locale] klíč #$i stojí na stránce dřív než předchozí — změnilo se pořadí"
                );
                $cursor = $at;
            }
        }
    }

    /**
     * Vrstva A a C z `hloubka.css` §E. Kužel je navázaný na `> section[data-pdd]`,
     * takže nestačí atribut kdekoli na stránce — sekce musí zůstat PŘÍMÝM
     * dítětem obalu. Proto se hledá i ten obal.
     */
    public function test_depth_layer_hooks_survive(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString('pd--depth-sub', $html, "[$locale] zmizel obal vrstvy hloubky");

            foreach (['about-intro', 'about-story', 'about-cta'] as $pdd) {
                $this->assertStringContainsString(
                    'data-pdd="'.$pdd.'"',
                    $html,
                    "[$locale] zmizel kužel `$pdd` (hloubka.css §E)"
                );
            }

            // Kontaktní stín pod videem, hloubka.css §E2.
            $this->assertStringContainsString(
                'about-intro__video',
                $html,
                "[$locale] zmizel stín pod videem (.about-intro__video)"
            );

            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            foreach (['about-intro', 'about-story', 'about-cta'] as $pdd) {
                $direct = $xpath->query(
                    '//div[contains(@class,"pd--depth-sub")]/section[@data-pdd="'.$pdd.'"]'
                );
                $this->assertSame(
                    1,
                    $direct->length,
                    "[$locale] sekce `$pdd` není přímým dítětem `.pd--depth-sub` — selektor `> section[data-pdd]` v §E ji mine a kužel zhasne"
                );
            }
        }
    }

    public function test_acid_vocabulary_replaced_the_subpage_one(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $html = $this->mainOf($this->get($url)->assertOk()->getContent());

            foreach (['pd-page-head', 'pd-heading--sub', 'pd-steps--chapters', 'pd-figure', 'pd-cta'] as $class) {
                $this->assertStringContainsString($class, $html, "[$locale] chybí komponenta `$class`");
            }

            // Starý slovník podstránky. Hledá se v `<main>`, kde se žádný
            // z těch řetězců nesmí vyskytnout ani jako část jiné třídy —
            // `pd-section`, `pd-about-cta` ani `pd-steps__media` ho neobsahují,
            // takže holý podřetězec je tady bezpečný i dostatečný.
            foreach (['page-hero', 'section-alt', 'section-cta', 'about-portrait', 'btn btn-primary'] as $dead) {
                $this->assertStringNotContainsString(
                    $dead,
                    $html,
                    "[$locale] v obsahu stránky se vrátil starý slovník: `$dead`"
                );
            }
        }
    }
}
