<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-373 — tlačítko cenové úrovně slibuje nezávaznost, ne cenu.
 *
 * Stejný slot mluvil v každém jazyce jinak: cs „Chci nezávaznou nabídku",
 * de „Unverbindliches Angebot anfordern", en ale „Get a free quote" — jediný
 * jazyk, který na tlačítku mluvil o ceně. A dělal to 4× (tři karty + lepící
 * lišta), přesně tam, kde OND-369 tenhle slovník z webu vyhazovalo.
 *
 * Tři tvrzení, tři bloky:
 *  1. `/en/price` vykreslená — na stránce nesmí stát „free quote"; věcná
 *     „free" (co je v ceně, složenina) zůstávají.
 *  2. `lang/` — všechny čtyři klíče drží rozhodnuté znění, cs a de se nehýbou.
 *  3. Co zadání zakazuje sáhnout — protějšek bloku 1, aby příští plošný
 *     replace „free" nesmazal i tam, kde je věcné.
 */
class Ond373EnPriceCtaWordingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Rozhodnuté znění. Přesný protějšek cs „nezávaznou nabídku" a de
     * „Unverbindliches Angebot" — nevymýšlí se nový slovník, překládá se ten,
     * který už v cs a de rozhodnutý je.
     */
    private const DECIDED_CTA = [
        'cs' => 'Chci nezávaznou nabídku',
        'en' => 'Get a no-obligation quote',
        'de' => 'Unverbindliches Angebot anfordern',
    ];

    /** Všechny čtyři slovy: tři cenové úrovně a lepící lišta. */
    private const CTA_KEYS = [
        'price.sticky_cta.cta',
        'price.tiers.0.cta',
        'price.tiers.1.cta',
        'price.tiers.2.cta',
    ];

    private const PRICE_PAGES = [
        'cs' => '/cenik',
        'en' => '/en/price',
        'de' => '/de/preisliste',
    ];

    // ------------------------------------------------------------------
    // 1. Vykreslená stránka
    // ------------------------------------------------------------------

    /**
     * Vlastní důkaz ze zadání. Hlídá se napříč všemi třemi ceníky — slib ceny
     * na tlačítku nemá důvod stát v žádném jazyce, takže jeden seznam odhalí
     * i překlep v nesprávném locale.
     */
    public function test_no_price_page_promises_a_free_quote_on_a_button(): void
    {
        $offenders = [];

        foreach (self::PRICE_PAGES as $locale => $path) {
            $body = $this->get($path)->assertOk()->getContent();

            if (str_contains($body, 'free quote')) {
                $offenders[] = "{$path} ({$locale})";
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Slib ceny „free quote\" zůstal na vykreslené stránce: ".implode(', ', $offenders),
        );
    }

    /**
     * Druhá strana téhož: tlačítka se opravdu vykreslí a mluví rozhodnutým
     * zněním. Bez tohohle by test výš prošel i tak, že by tlačítka zmizela.
     *
     * Čtyřikrát, protože čtyři jsou. Kdyby PR opravil jen karty a zapomněl na
     * lepící lištu (ta se objeví až po odscrollování, tedy nejsnáz se přehlédne),
     * spadne to právě tady.
     */
    public function test_all_four_english_buttons_speak_the_decided_wording(): void
    {
        $body = $this->get(self::PRICE_PAGES['en'])->assertOk()->getContent();

        $this->assertSame(
            4,
            substr_count($body, e(self::DECIDED_CTA['en'])),
            'Na `/en/price` nestojí rozhodnuté znění 4× (tři karty + lepící lišta).',
        );
    }

    // ------------------------------------------------------------------
    // 2. lang/
    // ------------------------------------------------------------------

    public function test_every_tier_cta_uses_the_decided_wording_in_every_locale(): void
    {
        foreach (self::DECIDED_CTA as $locale => $expected) {
            foreach (self::CTA_KEYS as $key) {
                $this->assertSame(
                    $expected,
                    trans($key, [], $locale),
                    "`{$key}` v `{$locale}` nemluví rozhodnutým zněním.",
                );
            }
        }
    }

    // ------------------------------------------------------------------
    // 3. Na co zadání zakazuje sáhnout
    // ------------------------------------------------------------------

    /**
     * „Free" je na stránce dvakrát věcně: jednou popisuje, co je v ceně,
     * jednou jde o složeninu. Ani jedno neslibuje cenu a zadání je výslovně
     * nechává být — tenhle test je tu, aby je příští plošný replace nesmazal.
     */
    public function test_the_factual_free_stays_on_the_english_page(): void
    {
        $body = $this->get(self::PRICE_PAGES['en'])->assertOk()->getContent();

        foreach (['Hosting and domain for 1 year free', 'Maintenance-free websites'] as $factual) {
            $this->assertStringContainsString(
                e($factual),
                $body,
                "Věcné „{$factual}\" zmizelo — zadání ho nechává být.",
            );
        }
    }

    /**
     * `price.quotation` je druhotné tlačítko, kde se en a de shodují, že
     * nezávaznost nepatří. Zadání to označuje za nesoulad, který se neopravuje
     * — bez téhle kotvy by ho někdo „dokončil" spolu s kartami.
     */
    public function test_the_secondary_quotation_button_is_left_alone(): void
    {
        $this->assertSame('Get a quote', trans('price.quotation', [], 'en'));
        $this->assertSame('Angebot anfragen', trans('price.quotation', [], 'de'));
    }
}
