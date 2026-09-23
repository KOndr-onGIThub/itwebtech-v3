<?php

namespace Tests\Feature;

use App\Mail\LeadMessage;
use App\Models\LandingLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-264: Poptávky z homepage, FAQ a landingu se jen ukládaly do DB —
 * notifikace neexistovala, takže se o nich nikdo nedozvěděl.
 */
class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function homePayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Novák',
            'email'   => 'jan@example.com',
            'phone'   => '+420123456789',
            'message' => 'Chtěl bych web.',
        ], $overrides);
    }

    public function test_home_inline_form_sends_notification(): void
    {
        Mail::fake();

        $this->post('/poptavka', $this->homePayload())
            ->assertSessionHas('home_lead_success', true);

        $lead = LandingLead::first();
        $this->assertSame('home.inline', $lead->source);
        $this->assertSame(LandingLead::MAIL_SENT, $lead->mail_status);

        Mail::assertSent(LeadMessage::class, fn (LeadMessage $mail) => $mail->hasTo(config('mail.contact_to')));
    }

    public function test_faq_micro_form_sends_notification(): void
    {
        Mail::fake();

        $this->post('/poptavka', $this->homePayload(['source' => 'home.faq']))
            ->assertSessionHas('faq_lead_success', true);

        $this->assertSame(LandingLead::MAIL_SENT, LandingLead::first()->mail_status);
        Mail::assertSent(LeadMessage::class);
    }

    public function test_landing_form_sends_notification(): void
    {
        Mail::fake();

        $this->post('/'.config('landing.preview_path').'/lead', [
            'name'    => 'Jana Firemní',
            'company' => 'Firma s.r.o.',
            'email'   => 'jana@example.com',
            'phone'   => '+420987654321',
            'budget'  => '50–100 tis.',
            'message' => 'Potřebujeme nový web.',
            'gdpr'    => '1',
        ])->assertSessionHas('landing_lead_success', true);

        $lead = LandingLead::first();
        $this->assertSame('landing.website-service', $lead->source);
        $this->assertSame(LandingLead::MAIL_SENT, $lead->mail_status);

        Mail::assertSent(LeadMessage::class);
    }

    public function test_lead_is_kept_when_notification_fails(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/poptavka', $this->homePayload())
            ->assertSessionHas('home_lead_success', true);

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

        $this->post('/poptavka', $this->homePayload());

        $html = (new LeadMessage(LandingLead::first()))->render();

        $this->assertStringContainsString('Jan Novák', $html);
        $this->assertStringContainsString('Chtěl bych web.', $html);
        $this->assertStringContainsString('home.inline', $html);
    }
}
