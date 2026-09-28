<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Mail\LeadMessage;
use App\Models\ContactSubmission;
use App\Models\LandingLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-264: Poptávky z homepage, FAQ a landingu se jen ukládaly do DB —
 * notifikace neexistovala, takže se o nich nikdo nedozvěděl.
 *
 * OND-448 (B-01): homepage posílá týž formulář jako /kontakt (`POST /contact`
 * → `contact_submissions`, mail `ContactMessage`, pokryto v ContactFormTest).
 * Do `landing_leads` a `LeadMessage` už píše jen landing page.
 */
class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_landing_form_sends_notification(): void
    {
        Mail::fake();

        $this->post($this->landingUrl(), $this->landingPayload())
            ->assertSessionHas('landing_lead_success', true);

        $lead = LandingLead::first();
        $this->assertSame('landing.website-service', $lead->source);
        $this->assertSame(LandingLead::MAIL_SENT, $lead->mail_status);

        Mail::assertSent(LeadMessage::class, fn (LeadMessage $mail) => $mail->hasTo(config('mail.contact_to')));
    }

    public function test_lead_is_kept_when_notification_fails(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP down'));

        $this->post($this->landingUrl(), $this->landingPayload())
            ->assertSessionHas('landing_lead_success', true);

        $lead = LandingLead::first();
        $this->assertSame(LandingLead::MAIL_FAILED, $lead->mail_status);
        $this->assertStringContainsString('SMTP down', $lead->mail_error);
    }

    /**
     * Stejná past jako u ContactMessage: markdownová šablona poslaná jako
     * `view:` spadne na „No hint path defined for [mail]" — Mail::fake() to
     * neodhalí, protože nic nerenderuje.
     */
    public function test_notification_mail_renders(): void
    {
        Mail::fake();

        $this->post($this->landingUrl(), $this->landingPayload());

        $html = (new LeadMessage(LandingLead::first()))->render();

        $this->assertStringContainsString('Jana Firemní', $html);
        $this->assertStringContainsString('Potřebujeme nový web.', $html);
        $this->assertStringContainsString('landing.website-service', $html);
    }

    /** OND-448: mail z jednotného formuláře říká, odkud poptávka přišla; předmět už není. */
    public function test_contact_mail_names_the_form_source(): void
    {
        Mail::fake();

        $this->postJson('/contact', [
            'name'    => 'Jan Novák',
            'email'   => 'jan@example.com',
            'message' => 'Chtěl bych web.',
            'source'  => 'home',
        ])->assertOk();

        $mail = new ContactMessage(ContactSubmission::first());
        $html = $mail->render();

        $this->assertSame('Nová poptávka z webu (homepage)', $mail->envelope()->subject);
        $this->assertStringContainsString('formulář: homepage', $html);
        $this->assertStringContainsString('Chtěl bych web.', $html);
        $this->assertStringNotContainsString('Předmět', $html);
    }
}
