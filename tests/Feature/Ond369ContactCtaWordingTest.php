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

    /**
     * Druhá strana téhož: předpatička se na podstránkách vykresluje a mluví
     * rozhodnutým slovníkem. Bez tohohle by test výš prošel i tak, že by
     * tlačítko zmizelo — a na existenci předpatičky zadání sahat zakazuje
     * (rozsah řeší OND-343).
     */
    public function test_the_prefooter_speaks_the_decided_vocabulary_on_subpages(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PAGES as $locale => $paths) {
            foreach ($paths as $path) {
                $body = $this->get($path)->assertOk()->getContent();

                // Homepage si předpatičku schovává (`hide_prefooter`), takže
                // tam se tagline ani tlačítko nevykreslí — a nemá to hlídat
                // tato karta.
                if (! str_contains($body, e(trans('layout.prefooter.tagline', [], $locale)))) {
                    continue;
                }

                $this->assertStringContainsString(
                    e(self::DECIDED_CTA[$locale]),
                    $body,
                    "Předpatička na {$path} nemluví rozhodnutým slovníkem.",
                );
            }
        }
    }

    // ------------------------------------------------------------------
    // 2. lang/
    // ------------------------------------------------------------------

    public function test_every_unified_key_uses_the_decided_vocabulary(): void
    {
        $keys = [
            'layout.prefooter.cta',
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
     * Věty, které o konzultaci mluví jako o kroku v procesu, nejsou výzva ke
     * kontaktu a zadání je nechává být. Hlídáme je, aby je příští plošný
     * replace nesmazal s nimi.
     */
    public function test_prose_about_the_consultation_is_left_alone(): void
    {
        $prose = [
            'projects.fit.cta_text' => [
                'cs' => 'úvodní konzultace',
                'en' => 'intro call',
                'de' => 'Erstgespräch',
            ],
            'price.cta.desc' => [
                'cs' => 'Během 30 minut',
                'en' => 'In 30 minutes',
                'de' => 'In 30 Minuten',
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
     * `price.cta.desc` je podtitulek téže výzvy jako tlačítko, proto z něj
     * „zdarma" padá — ale nezávaznost je jiný slib a ta zůstává.
     */
    public function test_the_price_cta_still_promises_a_non_binding_consultation(): void
    {
        $expected = [
            'cs' => 'Konzultace je nezávazná.',
            'en' => 'The consultation is non-binding.',
            'de' => 'Die Beratung ist unverbindlich.',
        ];

        foreach ($expected as $locale => $sentence) {
            $this->assertStringContainsString(
                $sentence,
                trans('price.cta.desc', [], $locale),
                "Slib nezávaznosti chybí v `lang/{$locale}/price.php` (cta.desc).",
            );
        }
    }
}
