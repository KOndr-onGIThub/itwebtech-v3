<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-448 — body B-01, B-02 a B-08 z dokumentu `planovane-zmeny` (OND-441).
 *
 *  B-01  jeden poptávkový formulář (homepage + /kontakt), zásady bez
 *        zaškrtávaného souhlasu,
 *  B-02  první krok je „úvodní hovor“ (asi 15 minut); web nikde neslibuje
 *        konzultaci ani nezávaznou / bezplatnou nabídku,
 *  B-08  ceník bez počtu stránek, „Texty píšu já“, věta pod balíčky jen na /cenik.
 *
 * Kontroluje se vykreslená stránka ve všech třech jazycích (`assertOk()` vždy
 * první — bez něj by 500 s `APP_DEBUG=false` prošla jako „text chybí“).
 */
class Ond448LeadFormIntroCallPricingTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = [
        'cs' => ['home' => '/',    'contact' => '/kontakt',    'price' => '/cenik',         'projects' => '/projekty',    'privacy' => '/zasady-ochrany-osobnich-udaju'],
        'en' => ['home' => '/en/', 'contact' => '/en/contact', 'price' => '/en/price',      'projects' => '/en/projects', 'privacy' => '/en/privacy-policy'],
        'de' => ['home' => '/de/', 'contact' => '/de/kontakt', 'price' => '/de/preisliste', 'projects' => '/de/projekte', 'privacy' => '/de/datenschutz'],
    ];

    /**
     * Slova prvního kroku, která B-02 z webu stahuje (i „30/60 min“ hovoru).
     * Holé „30 minut“ ne — případovka Choccoboard legitimně píše „30 minut
     * ruční práce denně“.
     */
    private const RETIRED_FIRST_STEP = [
        'cs' => ['onzultac', '30 minut hovoru', 'Během 30 minut', '60 min', 'nezávaznou nabídku', 'Nezávazná poptávka', 'Do té doby vás nic nezavazuje'],
        'en' => ['onsultation', '30-minute call', 'In 30 minutes', '60 min', 'no-obligation quote', 'Until then nothing commits you'],
        'de' => ['Beratung ist', 'eine Woche Beratung', 'nach der Beratung', '30-minütiges Gespräch', 'In 30 Minuten', '60 Min', 'Unverbindliches Angebot', 'Bis dahin verpflichtet Sie nichts'],
    ];

    /** B-08: počet stránek jako hranice balíčku a „texty máte připravené“. */
    private const RETIRED_PAGE_COUNT = [
        'cs' => ['do 5 stránek', 'do 12 stránek', 'do pěti stránek', 'Do 5 stránek', 'Do 12 stránek', 'Víc než pět stránek', 'Menší počet stránek', 'máte připravené', 'kolik stránek to má'],
        'en' => ['up to 5 pages', 'up to 12 pages', 'up to five pages', 'Up to 5 custom pages', 'Up to 12 custom pages', 'More than five pages', 'Fewer pages', 'copy and photos are ready', 'how many pages it has'],
        'de' => ['bis 5 Seiten', 'bis 12 Seiten', 'bis zu fünf Seiten', 'Bis zu 5 individuelle', 'Bis zu 12 individuelle', 'Mehr als fünf Seiten', 'Weniger Seiten', 'liegen bereit', 'wie viele Seiten sie hat'],
    ];

    private function page(string $locale, string $page): string
    {
        return $this->get(self::PAGES[$locale][$page])->assertOk()->getContent();
    }

    public function test_every_touched_page_renders_in_every_locale(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PAGES as $locale => $pages) {
            foreach ($pages as $url) {
                $this->get($url)->assertOk();
            }
        }
    }

    public function test_no_page_offers_a_consultation_or_a_free_quote(): void
    {
        $this->seed(PortfolioSeeder::class);

        $offenders = [];
        foreach (self::PAGES as $locale => $pages) {
            foreach (['home', 'contact', 'price', 'projects'] as $page) {
                $body = $this->page($locale, $page);
                foreach (self::RETIRED_FIRST_STEP[$locale] as $phrase) {
                    if (str_contains($body, e($phrase))) {
                        $offenders[] = "{$pages[$page]} — „{$phrase}“";
                    }
                }
            }
        }

        $this->assertSame([], $offenders, "B-02: starý první krok zůstal:\n".implode("\n", $offenders));
    }

    public function test_intro_call_is_about_fifteen_minutes_everywhere(): void
    {
        $fifteen = ['cs' => '15 min', 'en' => '15 min', 'de' => '15 Min'];

        foreach (self::PAGES as $locale => $pages) {
            foreach (['home', 'contact', 'price'] as $page) {
                $this->assertStringContainsString($fifteen[$locale], $this->page($locale, $page), "{$pages[$page]}: chybí „15 min“");
            }
            $this->assertStringContainsString(e(__('home.how_i_work.steps.0.heading', [], $locale)), $this->page($locale, 'home'));
        }
    }

    public function test_price_and_home_drop_the_page_count(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PAGES as $locale => $pages) {
            foreach (['home', 'price'] as $page) {
                $body = $this->page($locale, $page);
                foreach (self::RETIRED_PAGE_COUNT[$locale] as $phrase) {
                    $this->assertStringNotContainsString(e($phrase), $body, "{$pages[$page]}: „{$phrase}“");
                }
                // Claim a podtitul balíčku, žádné stránky.
                foreach (__('price.tiers', [], $locale) as $tier) {
                    $this->assertStringContainsString(e($tier['scope']), $body, "{$pages[$page]}: claim balíčku");
                }
            }
        }
    }

    public function test_pages_sentence_and_texts_card_stand_on_price_only(): void
    {
        foreach (self::PAGES as $locale => $pages) {
            $price = $this->page($locale, 'price');
            $home = $this->page($locale, 'home');
            $note = e(__('price.pages_note', [], $locale));

            $this->assertStringContainsString('<p class="pd-note pd-price__pages">'.$note.'</p>', $price, "{$pages['price']}: věta pod balíčky");
            $this->assertStringNotContainsString($note, $home, "{$pages['home']}: věta pod balíčky patří jen na /cenik");

            $first = __('price.guarantees.items', [], $locale)[0];
            $this->assertStringContainsString('<h3 class="pd-point__title">'.e($first['title']).'</h3>', $price, "{$pages['price']}: karta „Texty píšu já“");
        }
    }

    public function test_business_tier_no_longer_promises_a_booking_system(): void
    {
        $words = ['cs' => 'ezervač', 'en' => 'ooking', 'de' => 'uchung'];

        foreach ($words as $locale => $word) {
            $tier = __('price.tiers', [], $locale)[1];
            $this->assertSame('business', $tier['key']);
            $this->assertStringNotContainsString($word, $tier['scope'].$tier['desc'].implode(' ', $tier['features']), "[$locale] Firemní web");
            $this->assertStringNotContainsString($word, __('home.price_anchor.items', [], $locale)[1]['desc'], "[$locale] dlaždice Firemní web");
        }
    }

    public function test_privacy_policy_no_longer_mentions_a_ticked_consent_for_the_form(): void
    {
        $ticked = ['cs' => 'zaškrtáváte', 'en' => 'you tick', 'de' => 'ankreuzen'];
        $basis = ['cs' => 'čl. 6 odst. 1 písm. b GDPR', 'en' => 'Art. 6(1)(b) GDPR', 'de' => 'Art. 6 Abs. 1 lit. b DSGVO'];

        foreach (self::PAGES as $locale => $pages) {
            $body = $this->page($locale, 'privacy');
            $this->assertStringNotContainsString($ticked[$locale], $body, "{$pages['privacy']}");
            $this->assertStringContainsString($basis[$locale], $body, "{$pages['privacy']}: právní důvod jednání o smlouvě zůstává");
        }
    }

    /** B-01: stejná pole ve stejném pořadí na obou místech, bez předmětu. */
    public function test_home_and_contact_render_the_same_fields_in_the_same_order(): void
    {
        foreach (self::PAGES as $locale => $pages) {
            $fields = [];
            foreach (['home', 'contact'] as $page) {
                $body = $this->page($locale, $page);
                $form = substr($body, strpos($body, '<form @submit.prevent="submit"'));
                $form = substr($form, 0, strpos($form, '</form>'));
                preg_match_all('~<(?:input|textarea)[^>]*\sname="([^"]+)"~', $form, $m);
                $fields[$page] = array_values(array_diff($m[1], ['_token', 'website_url', 'source']));
            }

            $this->assertSame(['locale', 'name', 'email', 'tel', 'message', 'attachment[]'], $fields['home'], "[$locale] homepage");
            $this->assertSame($fields['home'], $fields['contact'], "[$locale] /kontakt se liší od homepage");
        }
    }
}
