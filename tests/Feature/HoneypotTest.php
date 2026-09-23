<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Mail\LeadMessage;
use App\Models\ContactSubmission;
use App\Models\LandingLead;
use App\Support\Honeypot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-280: Past na spamboty na všech třech veřejných formulářích.
 *
 * Dvě věci, které musí platit zároveň:
 *   1. Vyplněná past = nic se neuloží, nic neodejde, ale odpověď vypadá jako
 *      úspěch (bot se nesmí dozvědět, že ho to chytilo).
 *   2. Normální odeslání projde beze změny. Tohle je skutečné riziko téhle
 *      změny — kdyby past sklapla na člověka, tiše bychom zahazovali poptávky.
 */
class HoneypotTest extends TestCase
{
    use RefreshDatabase;

    private function contactPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Novák',
            'email'   => 'jan@example.com',
            'tel'     => '+420123456789',
            'subject' => 'Poptávka webu',
            'message' => 'Dobrý den, mám zájem o web.',
            'gdpr'    => '1',
        ], $overrides);
    }

    private function homePayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Novák',
            'email'   => 'jan@example.com',
            'phone'   => '+420123456789',
            'message' => 'Chtěl bych web.',
        ], $overrides);
    }

    private function landingPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jana Firemní',
            'company' => 'Firma s.r.o.',
            'email'   => 'jana@example.com',
            'phone'   => '+420987654321',
            'budget'  => '50–100 tis.',
            'message' => 'Potřebujeme nový web.',
            'gdpr'    => '1',
        ], $overrides);
    }

    private function landingUrl(): string
    {
        return '/'.config('landing.preview_path').'/lead';
    }

    /** Hodnota, kterou by do pasti nasypal bot. */
    private function trap(): array
    {
        return [Honeypot::FIELD => 'https://spam.example/buy-now'];
    }

    // --- 1) Past sklapne: nic se neuloží, nic neodejde, odpověď je „úspěch" ---

    public function test_contact_form_silently_drops_bot_submission(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->contactPayload($this->trap()))
            ->assertOk()
            ->assertJson(['message' => __('contact.message_success')]);

        $this->assertDatabaseCount('contact_submissions', 0);
        Mail::assertNothingSent();
    }

    public function test_home_form_silently_drops_bot_submission(): void
    {
        Mail::fake();

        $this->post('/poptavka', $this->homePayload($this->trap()))
            ->assertSessionHas('home_lead_success', true)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('landing_leads', 0);
        Mail::assertNothingSent();
    }

    public function test_faq_form_silently_drops_bot_submission(): void
    {
        Mail::fake();

        // FAQ mikroformulář musí ohlásit úspěch na svém místě, ne na poptávce —
        // jinak by z odpovědi šlo poznat, že request skončil jinak než obvykle.
        $this->post('/poptavka', $this->homePayload($this->trap() + ['source' => 'home.faq']))
            ->assertSessionHas('faq_lead_success', true)
            ->assertSessionHas('home_lead_target', 'faq');

        $this->assertDatabaseCount('landing_leads', 0);
        Mail::assertNothingSent();
    }

    public function test_landing_form_silently_drops_bot_submission(): void
    {
        Mail::fake();

        $this->post($this->landingUrl(), $this->landingPayload($this->trap()))
            ->assertSessionHas('landing_lead_success', true);

        $this->assertDatabaseCount('landing_leads', 0);
        Mail::assertNothingSent();
    }

    /**
     * Past nesmí jít obejít tím, že bot pošle pole prázdné nebo vůbec —
     * to je normální chování prohlížeče a musí projít.
     */
    public function test_empty_trap_field_is_treated_as_human(): void
    {
        Mail::fake();

        $this->post('/poptavka', $this->homePayload([Honeypot::FIELD => '']))
            ->assertSessionHas('home_lead_success', true);

        $this->assertDatabaseCount('landing_leads', 1);
        Mail::assertSent(LeadMessage::class);
    }

    // --- 2) Skutečné odeslání projde beze změny ---

    public function test_contact_form_still_works_for_humans(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->contactPayload())->assertOk();

        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertSame('jan@example.com', ContactSubmission::first()->email);
        Mail::assertSent(ContactMessage::class);
    }

    public function test_home_and_landing_forms_still_work_for_humans(): void
    {
        Mail::fake();

        $this->post('/poptavka', $this->homePayload())
            ->assertSessionHas('home_lead_success', true);
        $this->post($this->landingUrl(), $this->landingPayload())
            ->assertSessionHas('landing_lead_success', true);

        $this->assertDatabaseCount('landing_leads', 2);
        $this->assertSame(
            ['home.inline', 'landing.website-service'],
            LandingLead::orderBy('id')->pluck('source')->all(),
        );
        Mail::assertSent(LeadMessage::class, 2);
    }

    // --- 3) Pole je ve stránce a je schované ---

    public function test_trap_field_is_rendered_and_hidden_on_every_form(): void
    {
        foreach (['/kontakt', '/', '/'.config('landing.preview_path')] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString(
                'name="'.Honeypot::FIELD.'"',
                $html,
                "Past chybí na $url",
            );
            // Mimo obrazovku, mimo tabulátor, mimo čtečku obrazovky.
            $this->assertStringContainsString('left:-9999px', $html, "Past je vidět na $url");
            $this->assertStringContainsString('tabindex="-1"', $html, "Past je v tabulátoru na $url");
            $this->assertStringContainsString('aria-hidden="true"', $html, "Past čte čtečka na $url");
        }
    }
}
