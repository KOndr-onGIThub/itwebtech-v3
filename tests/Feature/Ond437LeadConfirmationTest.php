<?php

namespace Tests\Feature;

use App\Support\ReplyDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-437 — návrhy 1, 2 a 6 z OND-429:
 *  1. den odpovědi místo „následující pracovní den" (hero, formulář, /kontakt),
 *  2. po odeslání potvrzení MÍSTO formuláře, bez spodní mobilní lišty,
 *  6. z pruhu čísel pryč `response`, z mobilní lišty emoji.
 *
 * Čas je zmrazený na pondělí 28. 9. 2026 (státní svátek), 14:32 v Praze:
 * den odpovědi je úterý 29. 9.
 */
class Ond437LeadConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private const HOMES = ['cs' => '/', 'en' => '/en/', 'de' => '/de/'];

    private const CONTACTS = ['cs' => '/kontakt', 'en' => '/en/contact', 'de' => '/de/kontakt'];

    /** Den odpovědi a čas přijetí pro zmrazený čas, jak je má vykreslit šablona. */
    private const EXPECTED = [
        'cs' => ["v\u{00A0}úterý 29.\u{00A0}9.", "28.\u{00A0}9., 14:32"],
        'en' => ["Tuesday 29\u{00A0}September", "28\u{00A0}September, 14:32 Prague time"],
        'de' => ['Dienstag, 29.9.', '28.9., 14:32 Uhr'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        // V UTC jako aplikace (config/app.php). Zmrazení v Europe/Prague by
        // Carbon propsal i do čtení `created_at` z DB a čas by lhal o 2 h.
        $this->travelTo(Carbon::parse('2026-09-28 12:32', 'UTC'));
    }

    private function leadPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Novák',
            'email'   => 'jan@firma.cz',
            'tel'     => '',
            'message' => 'Chtěl bych nový web.',
        ], $overrides);
    }

    // ------------------------------------------------------------------
    // Návrh 2 — potvrzení po odeslání. OND-448 (B-01): homepage i /kontakt
    // posílají týž formulář přes AJAX na `POST /contact`; potvrzení vykreslí
    // server do JSON a `contactForm` ho vloží místo formuláře.
    // ------------------------------------------------------------------

    public function test_home_submit_returns_confirmation_with_steps_in_the_page_language(): void
    {
        foreach (array_keys(self::HOMES) as $locale) {
            $html = $this->postJson('/contact', $this->leadPayload(['locale' => $locale, 'source' => 'home']))
                ->assertOk()
                ->json('confirmation');

            [$date, $received] = self::EXPECTED[$locale];

            // Potvrzení: štítek s časem, nadpis, den odpovědi, e-mail, kroky, článek.
            $this->assertStringContainsString('role="status"', $html, "[$locale]");
            $this->assertStringContainsString('data-lead-confirmation', $html, "[$locale]");
            $this->assertStringContainsString(e(__('home.inline_form.confirmation.stamp', ['received' => $received], $locale)), $html, "[$locale] štítek");
            $this->assertStringContainsString('<h3 class="pd-done__title">'.e(__('home.inline_form.confirmation.heading', [], $locale)).'</h3>', $html, "[$locale] nadpis h3 pod h2 sekce");
            $this->assertStringContainsString('<strong class="pd-date">'.$date.'</strong>', $html, "[$locale] den odpovědi");
            $this->assertStringContainsString('jan@firma.cz', $html, "[$locale] e-mail");
            $this->assertStringContainsString('pd-done__steps', $html, "[$locale] kroky");
            foreach (__('home.inline_form.confirmation.steps', [], $locale) as $step) {
                $this->assertStringContainsString(e($step['label']), $html, "[$locale] krok");
                $this->assertStringContainsString(e($step['text']), $html, "[$locale] krok");
            }
            $slug = __('home.how_i_work.cta_more_slug', [], $locale);
            $this->assertStringContainsString(route("{$locale}.article", ['slug' => $slug]), $html, "[$locale] odkaz na článek");
        }
    }

    public function test_confirmation_escapes_the_submitted_email_via_honeypot_path(): void
    {
        // Past na boty vrací totéž co úspěch — i s e-mailem z formuláře, který
        // prošel bez validace. Musí se escapovat.
        $html = $this->postJson('/contact', $this->leadPayload(['email' => '<script>x</script>', 'website_url' => 'bot', 'source' => 'home']))
            ->assertOk()
            ->json('confirmation');

        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>x', $html);
    }

    public function test_validation_error_returns_field_errors_and_no_confirmation(): void
    {
        $this->postJson('/contact', $this->leadPayload(['email' => '', 'source' => 'home']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonMissingPath('confirmation');
    }

    public function test_contact_endpoint_returns_confirmation_in_the_page_language(): void
    {
        foreach (array_keys(self::CONTACTS) as $locale) {
            $html = $this->postJson('/contact', $this->leadPayload(['locale' => $locale, 'source' => 'contact']))
                ->assertOk()
                ->json('confirmation');

            [$date, $received] = self::EXPECTED[$locale];

            // Stejné znění jako na homepage (B-01), jen bez kroků a s h2.
            $this->assertStringContainsString('role="status"', $html, "[$locale]");
            $this->assertStringContainsString(e(__('home.inline_form.confirmation.stamp', ['received' => $received], $locale)), $html, "[$locale] štítek");
            $this->assertStringContainsString('<h2 class="pd-done__title">'.e(__('home.inline_form.confirmation.heading', [], $locale)).'</h2>', $html, "[$locale] nadpis");
            $this->assertStringContainsString('<strong class="pd-date">'.$date.'</strong>', $html, "[$locale] den odpovědi");
            $this->assertStringContainsString('jan@firma.cz', $html, "[$locale] e-mail");
            // /kontakt má pod formulářem vlastní „Co se stane potom" — kroky tu nejsou.
            $this->assertStringNotContainsString('pd-done__steps', $html, "[$locale]");
        }
    }

    public function test_both_pages_swap_the_shared_form_for_server_confirmation(): void
    {
        foreach (['home' => self::HOMES, 'contact' => self::CONTACTS] as $source => $urls) {
            foreach ($urls as $locale => $url) {
                $html = $this->get($url)->assertOk()->getContent();

                $this->assertStringContainsString('<div class="pd-form__thanks" x-show="submitted" x-cloak x-html="confirmation"></div>', $html, "[$source $locale]");
                $this->assertStringContainsString('<input type="hidden" name="locale" value="'.$locale.'">', $html, "[$source $locale]");
                $this->assertStringContainsString('<input type="hidden" name="source" value="'.$source.'">', $html, "[$source $locale]");
                $this->assertStringContainsString("source: '$source'", $html, "[$source $locale] contactForm zná místo");
                $this->assertStringNotContainsString('action="', $this->between($html, 'class="pd-form__panel"', '</form>'), "[$source $locale] žádný klasický POST");
            }
        }
    }

    /**
     * OND-448 (B-01): lišta vede k formuláři, který je nejblíž — homepage na
     * sekci „Poptávka“, /kontakt na formulář na téže stránce, jinde na /kontakt.
     * Po odeslání ji schová `contactForm` (třída `is-lead-sent`), server nic.
     */
    public function test_mobile_bar_points_to_the_nearest_form(): void
    {
        $bar = fn (string $url) => $this->between($this->get($url)->assertOk()->getContent(), '<div class="mobile-bottom-bar"', '</div>');

        $this->assertStringContainsString('href="#'.__('home.anchors.poptavka').'"', $bar('/'));
        $this->assertStringContainsString('href="#kontaktni-formular"', $bar('/kontakt'));
        $this->assertStringContainsString('href="'.lroute('contact').'"', $bar('/cenik'));
        $this->assertStringContainsString('href="'.route('en.contact').'"', $bar('/en/price'));
    }

    // ------------------------------------------------------------------
    // Návrh 1 a 6 — homepage před odesláním
    // ------------------------------------------------------------------

    public function test_homepage_shows_reply_date_and_no_second_promise_in_strip(): void
    {
        $old = [
            'cs' => 'Odpověď nejpozději následující pracovní den',
            'en' => 'Reply by the next business day',
            'de' => 'Antwort spätestens am nächsten Arbeitstag',
        ];

        foreach (self::HOMES as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $date = self::EXPECTED[$locale][0];

            // Hero i úvod formuláře nesou den, žádné zástupné místo nezůstalo.
            $this->assertSame(2, substr_count($html, '<strong class="pd-date">'.$date.'</strong>'), "[$locale] hero + formulář");
            $this->assertStringNotContainsString(':date', $html, "[$locale]");

            $strip = $this->between($html, '<ul class="pd-strip__list">', '</ul>');
            $this->assertStringNotContainsString($old[$locale], $strip, "[$locale] pruh");
            // OND-490 (bod 9): „23+ realizací“ z pruhu pryč; OND-506 přidal
            // údaj o rozsahu bez čísla → 4 položky.
            $this->assertSame(4, substr_count($strip, '<li>'), "[$locale] pruh má 4 položky");
            $this->assertStringNotContainsString('23+', $strip, "[$locale] pruh");

            $this->assertStringNotContainsString('💬', $html, "[$locale] emoji");
            $this->assertStringContainsString('class="mobile-bottom-bar"', $html, "[$locale] lišta před odesláním");
        }
    }

    /**
     * OND-506: údaj schválený na OND-495 (soupis rev. 4, sekce 2a) stojí v pruhu
     * na HP i na /cenik, hned za hodnocením. Texty natvrdo, ne přes `__()`,
     * aby test chytil i změnu v `lang/`. Žádné číslo („23+“) se nevrací.
     */
    public function test_strip_shows_scope_of_work_without_a_number_on_home_and_price(): void
    {
        $scope = [
            'cs' => 'Desítky webů, aplikací i menších zakázek',
            'en' => 'Dozens of websites, apps and smaller jobs',
            'de' => 'Dutzende Websites, Apps und kleinere Aufträge',
        ];
        $prices = ['cs' => '/cenik', 'en' => '/en/price', 'de' => '/de/preisliste'];

        foreach (['home' => self::HOMES, 'price' => $prices] as $page => $urls) {
            foreach ($urls as $locale => $url) {
                $html = $this->get($url)->assertOk()->getContent();
                $strip = $this->between($html, 'class="pd-strip__list"', '</ul>');

                $this->assertSame(4, substr_count($strip, '<li>'), "[$page/$locale] pruh má 4 položky");
                $this->assertStringContainsString('<li><strong>'.e($scope[$locale]).'</strong></li>', $strip, "[$page/$locale] údaj o rozsahu");
                $this->assertStringNotContainsString('23+', $strip, "[$page/$locale] pruh");

                // Pořadí: hodnocení → rozsah → Toyota → ocenění.
                $at = strpos($strip, e($scope[$locale]));
                $this->assertGreaterThan(strpos($strip, 'sr-only'), $at, "[$page/$locale] za hodnocením");
                $this->assertLessThan(strpos($strip, 'Toyot'), $at, "[$page/$locale] před Toyotou");
            }
        }
    }

    private function between(string $html, string $from, string $to): string
    {
        $start = strpos($html, $from);
        $this->assertNotFalse($start, "chybí $from");
        $end = strpos($html, $to, $start);
        $this->assertNotFalse($end, "chybí $to");

        return substr($html, $start, $end - $start);
    }
}
