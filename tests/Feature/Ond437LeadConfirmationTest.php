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
            'phone'   => '',
            'message' => 'Chtěl bych nový web.',
        ], $overrides);
    }

    // ------------------------------------------------------------------
    // Návrh 2 — homepage po odeslání
    // ------------------------------------------------------------------

    public function test_homepage_renders_confirmation_instead_of_form_after_submit(): void
    {
        foreach (self::HOMES as $locale => $url) {
            $html = $this->from($url)
                ->followingRedirects()
                ->post('/poptavka', $this->leadPayload())
                ->assertOk()
                ->getContent();

            [$date, $received] = self::EXPECTED[$locale];
            $panel = $this->panelOf($html);

            // Potvrzení: štítek s časem, nadpis, den odpovědi, e-mail, kroky, článek.
            $this->assertStringContainsString('role="status"', $panel, "[$locale]");
            $this->assertStringContainsString('data-lead-confirmation', $panel, "[$locale]");
            $this->assertStringContainsString(e(__('home.inline_form.confirmation.stamp', ['received' => $received], $locale)), $panel, "[$locale] štítek");
            $this->assertStringContainsString(e(__('home.inline_form.confirmation.heading', [], $locale)), $panel, "[$locale] nadpis");
            $this->assertStringContainsString('<strong class="pd-date">'.$date.'</strong>', $panel, "[$locale] den odpovědi");
            $this->assertStringContainsString('jan@firma.cz', $panel, "[$locale] e-mail");
            foreach (__('home.inline_form.confirmation.steps', [], $locale) as $step) {
                $this->assertStringContainsString(e($step['label']), $panel, "[$locale] krok");
            }
            $slug = __('home.how_i_work.cta_more_slug', [], $locale);
            $this->assertStringContainsString(route("{$locale}.article", ['slug' => $slug]), $panel, "[$locale] odkaz na článek");

            // Formulář ani stará zelená hláška se nevykreslí.
            $this->assertStringNotContainsString('<form', $panel, "[$locale] formulář zůstal");
            $this->assertStringNotContainsString('pd-alert--success', $html, "[$locale]");

            // Spodní mobilní lišta s poptávkou se na téhle odpovědi nevykresluje.
            $this->assertStringNotContainsString('class="mobile-bottom-bar"', $html, "[$locale] lišta");
            $this->assertMatchesRegularExpression('/<body class="[^"]*\bis-lead-sent\b/', $html, "[$locale]");

            // Fokus po přesměrování míří na potvrzení.
            $this->assertStringContainsString("document.querySelector('[data-lead-confirmation]')?.focus(", $html, "[$locale] fokus");

            $this->flushSession();
        }
    }

    public function test_confirmation_escapes_the_submitted_email_via_honeypot_path(): void
    {
        // Past na boty vrací totéž co úspěch — i s e-mailem z formuláře, který
        // prošel bez validace. Musí se escapovat.
        $html = $this->from('/')
            ->followingRedirects()
            ->post('/poptavka', $this->leadPayload(['email' => '<script>x</script>', 'website_url' => 'bot']))
            ->assertOk()
            ->getContent();

        $panel = $this->panelOf($html);
        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $panel);
        $this->assertStringNotContainsString('<script>x', $panel);
    }

    public function test_validation_error_state_still_shows_the_form(): void
    {
        $html = $this->from('/')
            ->followingRedirects()
            ->post('/poptavka', $this->leadPayload(['email' => '']))
            ->assertOk()
            ->getContent();

        $panel = $this->panelOf($html);
        $this->assertStringContainsString('pd-alert pd-alert--error', $panel);
        $this->assertStringContainsString('<form', $panel);
        $this->assertStringNotContainsString('data-lead-confirmation', $panel);
        $this->assertStringContainsString('class="mobile-bottom-bar"', $html);
    }

    // ------------------------------------------------------------------
    // Návrh 2 — /kontakt (Alpine, bez přesměrování)
    // ------------------------------------------------------------------

    public function test_contact_endpoint_returns_confirmation_in_the_page_language(): void
    {
        foreach (array_keys(self::CONTACTS) as $locale) {
            $json = $this->postJson('/contact', [
                'name' => 'Jan Novák', 'email' => 'jan@firma.cz', 'gdpr' => '1', 'locale' => $locale,
            ])->assertOk()->json();

            [$date, $received] = self::EXPECTED[$locale];
            $html = $json['confirmation'];

            $this->assertStringContainsString('role="status"', $html, "[$locale]");
            $this->assertStringContainsString(e(__('contact.thank_you.stamp', ['received' => $received], $locale)), $html, "[$locale] štítek");
            $this->assertStringContainsString(e(__('contact.thank_you.heading', [], $locale)), $html, "[$locale] nadpis");
            $this->assertStringContainsString('<strong class="pd-date">'.$date.'</strong>', $html, "[$locale] den odpovědi");
            $this->assertStringContainsString('jan@firma.cz', $html, "[$locale] e-mail");
            // /kontakt má pod formulářem vlastní „Co se stane potom" — kroky tu nejsou.
            $this->assertStringNotContainsString('pd-done__steps', $html, "[$locale]");
        }
    }

    public function test_contact_page_swaps_form_for_server_confirmation(): void
    {
        foreach (self::CONTACTS as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString('<div class="pd-form__thanks" x-show="submitted" x-cloak x-html="confirmation"></div>', $html, "[$locale]");
            $this->assertStringContainsString('<input type="hidden" name="locale" value="'.$locale.'">', $html, "[$locale]");
            $this->assertStringContainsString('<strong class="pd-date">'.self::EXPECTED[$locale][0].'</strong>', $html, "[$locale] den v úvodu");
        }
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
            $this->assertSame(4, substr_count($strip, '<li>'), "[$locale] pruh má 4 položky");

            $this->assertStringNotContainsString('💬', $html, "[$locale] emoji");
            $this->assertStringContainsString('class="mobile-bottom-bar"', $html, "[$locale] lišta před odesláním");
        }
    }

    private function panelOf(string $html): string
    {
        return $this->between($html, '<div class="pd-form__panel">', '</section>');
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
