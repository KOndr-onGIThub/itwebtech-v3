<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-369 — jedna akce, jeden slovník.
 *
 * Nová homepage říkala „Napsat poptávku", podstránky pod ní pořád zvaly na
 * „konzultaci zdarma" (předpatička v `layouts/app.blade.php`). Na `/o-mne`
 * a `/kontakt` z toho byla dvojitá výzva pod sebou.
 *
 * Tři bloky testů, protože jde o tři různá tvrzení:
 *  1. Vykreslené stránky — na žádné z nich nesmí stát stará výzva, a to ve
 *     všech třech jazycích. Tohle je vlastní důkaz; klíče jsou jen cesta k němu.
 *  2. `lang/` — rozhodnutý slovník (OND-307) sedí u všech změněných klíčů.
 *  3. Próza zůstala. Zadání výslovně rozlišuje popisek tlačítka od věty
 *     v textu; kdyby někdo příště „dokončil" sjednocení plošným replacem,
 *     spadne mu právě tento blok.
 */
class Ond369ContactCtaWordingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Rozhodnutý slovník. Zdroj je `home.sticky.cta` — tlačítko v hlavičce
     * a v draweru, přepsané na OND-307. Držíme se ho, nevymýšlíme nový.
     */
    private const DECIDED_CTA = [
        'cs' => 'Napsat poptávku',
        'en' => 'Write an enquiry',
        'de' => 'Anfrage schreiben',
    ];

    /**
     * Stará řeč, která po OND-369 nesmí stát na žádné vykreslené stránce.
     * Hledá se napříč jazyky naráz — německá stránka nemá důvod obsahovat
     * českou variantu, takže jeden seznam pro všechny odhalí i překlep
     * v nesprávném locale ([[oprava v jednom jazyce není oprava]]).
     */
    private const RETIRED_CTA = [
        'Domluvit konzultaci zdarma',
        'Domluvit konzultaci',
        'Book a free consultation',
        'Book a consultation',
        'Book consultation',
        'Kostenlose Beratung vereinbaren',
        'Kostenlose Beratung buchen',
        'Beratung vereinbaren',
        'Beratung buchen',
    ];

    /** Slovo „zdarma" jde pryč z výzvy ke kontaktu — ceník od OND-354 stojí na prahovém čísle. */
    private const RETIRED_FREE_PROMISE = [
        'Konzultace je zdarma',
        'The consultation is free',
        'Die Beratung ist kostenlos',
    ];

    /** Všech šest stránek ze zadání, pro každý jazyk. */
    private const PAGES = [
        'cs' => ['/', '/o-mne', '/kontakt', '/zapisky', '/cenik', '/projekty'],
        'en' => ['/en', '/en/about', '/en/contact', '/en/blog', '/en/price', '/en/projects'],
        'de' => ['/de', '/de/ueber-mich', '/de/kontakt', '/de/blog', '/de/preisliste', '/de/projekte'],
    ];

    // ------------------------------------------------------------------
    // 1. Vykreslené stránky
    // ------------------------------------------------------------------

    public function test_no_page_still_invites_to_a_consultation_in_any_locale(): void
    {
        $this->seed(PortfolioSeeder::class);

        $offenders = [];

        foreach (self::PAGES as $locale => $paths) {
            foreach ($paths as $path) {
                $body = $this->get($path)->assertOk()->getContent();

                foreach (array_merge(self::RETIRED_CTA, self::RETIRED_FREE_PROMISE) as $phrase) {
                    if (str_contains($body, e($phrase))) {
                        $offenders[] = "{$path} ({$locale}) — „{$phrase}\"";
                    }
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Stará výzva ke kontaktu zůstala na vykreslených stránkách:\n".implode("\n", $offenders),
        );
    }

    // Druhá strana téhož — „předpatička mluví rozhodnutým slovníkem" — tu
    // hlídal test, který OND-387 odebral spolu s předpatičkou. Tlačítko
    // v patičce není; že se nevrátí, hlídá Ond387FooterNavigationTest.

    // ------------------------------------------------------------------
    // 2. lang/
    // ------------------------------------------------------------------

    public function test_every_unified_key_uses_the_decided_vocabulary(): void
    {
        $keys = [
            'layout.cta.contact',
            'projects.fit.cta_primary',
            'projects.cta.primary',
            'price.cta.btn',
            // Kotva slovníku. Kdyby se rozhodnuté znění změnilo tady a nikde
            // jinde, spadne tenhle test a ne až zákazník.
            'home.sticky.cta',
        ];

        foreach (self::DECIDED_CTA as $locale => $expected) {
            foreach ($keys as $key) {
                $this->assertSame(
                    $expected,
                    trans($key, [], $locale),
                    "`{$key}` v `{$locale}` nemluví rozhodnutým slovníkem.",
                );
            }
        }
    }

    // ------------------------------------------------------------------
    // 3. Próza zůstala
    // ------------------------------------------------------------------

    /**
     * Věty, které o prvním kroku mluví jako o kroku v procesu, nejsou výzva ke
     * kontaktu a zadání je nechává být. Hlídáme je, aby je příští plošný
     * replace nesmazal s nimi.
     *
     * OND-448 (B-02): první krok je „úvodní hovor“ (asi 15 minut), slovo
     * konzultace z webu odešlo.
     */
    public function test_prose_about_the_intro_call_is_left_alone(): void
    {
        $prose = [
            'projects.fit.cta_text' => [
                'cs' => 'úvodním hovoru',
                'en' => 'intro call',
                'de' => 'Erstgespräch',
            ],
            'price.cta.desc' => [
                'cs' => 'úvodní hovor, asi 15 minut',
                'en' => 'intro call is enough, about 15 minutes',
                'de' => 'Erstgespräch genügt, etwa 15 Minuten',
            ],
        ];

        foreach ($prose as $key => $perLocale) {
            foreach ($perLocale as $locale => $fragment) {
                $this->assertStringContainsString(
                    $fragment,
                    trans($key, [], $locale),
                    "Próza v `{$key}` (`{$locale}`) zmizela — zadání ji nechává být.",
                );
            }
        }
    }

    /**
     * OND-448 (B-02): web o ceně specifikace mlčí, ale nikde neslibuje, že je
     * první krok nebo nabídka nezávazná či zdarma. (Do OND-448 tu test naopak
     * hlídal „Konzultace je nezávazná.“ — board to 28. 9. 2026 zrušil.)
     */
    public function test_the_price_cta_promises_neither_a_consultation_nor_no_obligation(): void
    {
        $retired = [
            'cs' => ['onzultac', 'nezávazn', '30 minut'],
            'en' => ['onsultation', 'non-binding', 'no-obligation', '30 minutes'],
            'de' => ['Beratung', 'unverbindlich', '30 Minuten'],
        ];

        foreach ($retired as $locale => $words) {
            foreach ($words as $word) {
                $this->assertStringNotContainsString(
                    $word,
                    trans('price.cta.desc', [], $locale),
                    "`price.cta.desc` (`{$locale}`) pořád obsahuje „{$word}“.",
                );
            }
        }
    }
}
