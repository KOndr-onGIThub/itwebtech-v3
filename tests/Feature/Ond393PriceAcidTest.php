<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-393 — /cenik ve slovníku nové homepage (3. z 9 podstránek).
 *
 * Návrh a měření: OND-391. Vzor je `Ond392ContactAcidTest` (/kontakt).
 * Na ceníku je každá částka Ondřejovo rozhodnutí, takže hlavní tvrzení je:
 *
 *  1. ŠABLONA NESMÍ SÁHNOUT NA OBSAH ANI NA ČÍSLA. Každý viditelný klíč
 *     `price.*` (a pruh `home.social_proof`) stojí v `<main>` doslova a ve
 *     stejném pořadí, cs/en/de — včetně všech částek. Hranice tvrzení je
 *     stejná jako u /kontakt: chytí šablonu, která klíč zahodí, prohodí nebo
 *     přeformátuje, NE změnu samotného `lang/` (ta je vidět v diffu).
 *  2. VÝZVY PODLE ROZHODNUTÍ OND-391 §5. Jediné acidové tlačítko `.pd-cta`,
 *     tři tiché odkazy u úrovní s analytikou, žádná plovoucí pilulka.
 *  3. NASVÍCENÍ NESMÍ ZHASNOUT. Kužely v hloubka.css §E visí na `data-pdd`
 *     přímých dětí obalu `.pd--depth-sub`.
 *  4. STARÝ SLOVNÍK JE PRYČ.
 */
class Ond393PriceAcidTest extends TestCase
{
    use RefreshDatabase;

    /** locale => cesta */
    private const PAGES = [
        'cs' => '/cenik',
        'en' => '/en/price',
        'de' => '/de/preisliste',
    ];

    private const SECTIONS = ['price-proof', 'price-guarantees', 'price-tiers', 'price-compare', 'price-addons', 'price-cta'];

    public function test_all_three_locales_render(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_title_follows_the_about_page_pattern(): void
    {
        $expected = [
            'cs' => 'Ceník — Ondřej Kriška, ONDRAWEB',
            'en' => 'Pricing — Ondřej Kriška, ONDRAWEB',
            'de' => 'Preise — Ondřej Kriška, ONDRAWEB',
        ];

        foreach (self::PAGES as $locale => $url) {
            $this->get($url)->assertOk()->assertSee('<title>'.$expected[$locale].'</title>', false);
        }
    }

    private function mainOf(string $html): string
    {
        $from = mb_strpos($html, '<main');
        $to = mb_strpos($html, '</main>');

        $this->assertNotFalse($from, 'stránka nemá <main>');
        $this->assertNotFalse($to, 'stránka nemá </main>');

        return mb_substr($html, $from, $to - $from);
    }

    private function text(string $html): string
    {
        return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Viditelné klíče v pořadí šablony, částky doplňků i věty s rozpětím
     * a vstupní cenou mezi nimi. Hledá se od kurzoru, ne od začátku —
     * krátké řetězce se na stránce opakují. Odkaz na případovku tu není:
     * v testovací DB žádné projekty nejsou (hlídá ho `Ond359…`).
     */
    public function test_every_language_key_and_amount_renders_verbatim_and_in_order(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->app->setLocale($locale);

            $expected = array_map(fn ($key) => $this->text(__($key)), [
                'price.hero.page_mark_label', 'price.hero.upline', 'price.hero.heading_html', 'price.hero.subline',
                'home.social_proof.rating_aria', 'home.social_proof.reviews',
                'home.social_proof.scope', 'home.social_proof.experience', 'home.social_proof.award',
                'price.guarantees.heading',
            ]);
            foreach (__('price.guarantees.items') as $g) {
                array_push($expected, $g['title'], $g['text']);
            }
            $expected[] = __('price.intro');
            foreach (__('price.tiers') as $tier) {
                $expected[] = $tier['name'];
                if ($tier['popular']) {
                    $expected[] = __('price.popular');
                }
                array_push($expected, $tier['scope'], $tier['desc'], ...$tier['features']);
                $expected[] = $tier['cta'];
            }
            array_push($expected, __('price.entry_note'), __('price.note'), __('price.compare.heading'));
            foreach (['up', 'down'] as $direction) {
                $expected[] = __("price.compare.$direction.label");
                array_push($expected, ...__("price.compare.$direction.items"));
            }
            array_push($expected, __('price.addons.heading'), __('price.addons.desc'));
            foreach (__('price.addons.items') as $addon) {
                array_push($expected, $addon['name'], $addon['price'], $addon['desc']);
            }
            array_push($expected, __('price.cta.heading'), __('price.cta.desc'), __('price.cta.btn'));

            $text = $this->text($this->mainOf($this->get($url)->assertOk()->getContent()));

            $cursor = 0;
            foreach ($expected as $i => $string) {
                $at = mb_strpos($text, $string, $cursor, 'UTF-8');

                $this->assertNotFalse(
                    $at,
                    "[$locale] řetězec #$i chybí nebo stojí dřív, než má: ".mb_substr($string, 0, 60)
                );
                $cursor = $at + mb_strlen($string);
            }
        }
    }

    /**
     * `.pd-eyebrow__sep` je prázdný span. Bez mezer v markupu se „CENÍK"
     * a „Žádné…" slijí pro čtečku i pro `innerText` do jednoho slova
     * (předloha OND-391, bod 8).
     */
    public function test_eyebrow_parts_do_not_merge_into_one_word(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->app->setLocale($locale);
            $main = $this->mainOf($this->get($url)->assertOk()->getContent());

            $this->assertStringContainsString(
                e(__('price.hero.page_mark_label')).' <span class="pd-eyebrow__sep" aria-hidden="true"></span> '.e(__('price.hero.upline')),
                $main,
                "[$locale] nadřádek nemá mezery kolem `.pd-eyebrow__sep`"
            );
        }
    }

    public function test_calls_to_action_follow_the_decision(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $main = $this->mainOf($html);

            $this->assertSame(1, substr_count($main, 'class="pd-cta"'), "[$locale] v obsahu má být jediné acidové tlačítko");
            $this->assertStringNotContainsString('price-sticky-cta', $html, "[$locale] vrátila se plovoucí pilulka");

            // Analytika po úrovních zůstává na tichých odkazech.
            $this->assertSame(3, substr_count($main, 'data-analytics="pricing_tier_cta_primary_click"'), "[$locale] odkazy u úrovní ztratily analytiku");
            $this->assertSame(3, substr_count($main, 'data-analytics-view="pricing_tier_view"'), "[$locale] úrovně ztratily `pricing_tier_view`");
            $this->assertSame(3, substr_count($main, 'class="pd-case__cta"'), "[$locale] odkazy u úrovní nejsou tiché `.pd-case__cta`");
        }
    }

    public function test_depth_layer_hooks_survive(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " pd ") and contains(@class, "pd--depth-sub")]')->length,
                "[$locale] obal vrstvy hloubky nemá třídu `pd` — komponenty si nepřitáhnou barvu"
            );

            foreach (self::SECTIONS as $pdd) {
                $this->assertSame(
                    1,
                    $xpath->query('//div[contains(@class,"pd--depth-sub")]/section[@data-pdd="'.$pdd.'"]')->length,
                    "[$locale] sekce `$pdd` není přímým dítětem `.pd--depth-sub` — kužel v §E zhasne"
                );
            }

            // Doporučená úroveň je právě jedna — nese acidovou linku.
            $this->assertSame(
                1,
                $xpath->query('//article[contains(concat(" ", normalize-space(@class), " "), " pd-price__col--featured ")]')->length,
                "[$locale] doporučená úroveň není právě jedna"
            );
        }
    }

    public function test_acid_vocabulary_replaced_the_subpage_one(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $main = $this->mainOf($this->get($url)->assertOk()->getContent());

            foreach (['pd-page-head', 'pd-heading--sub', 'pd-strip__list', 'pd-testi', 'pd-points', 'pd-price--full', 'pd-duo', 'pd-rates', 'pd-close', 'pd-cta'] as $class) {
                $this->assertStringContainsString($class, $main, "[$locale] chybí komponenta `$class`");
            }

            foreach (['page-hero', 'pricing-', 'section-wrapper', 'section-alt', 'section-cta', 'price-cta__', 'btn btn-primary', 'btn btn-secondary', 'data-reveal'] as $dead) {
                $this->assertStringNotContainsString($dead, $main, "[$locale] v obsahu stránky se vrátil starý slovník: `$dead`");
            }
        }
    }
}
