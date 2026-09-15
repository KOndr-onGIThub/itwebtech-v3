<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-173: Kontaktní formulář musí lead uložit do DB a odeslat notifikaci.
 */
class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
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

    public function test_submission_stores_lead_and_sends_notification(): void
    {
        Mail::fake();

        $response = $this->postJson('/contact', $this->validPayload());

        $response->assertOk()->assertJson(['message' => __('contact.message_success')]);

        $this->assertDatabaseCount('contact_submissions', 1);
        $lead = ContactSubmission::first();
        $this->assertSame('jan@example.com', $lead->email);
        $this->assertSame(ContactSubmission::MAIL_SENT, $lead->mail_status);
        $this->assertNotNull($lead->locale);

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->hasTo(config('mail.contact_to'));
        });
    }

    public function test_lead_is_stored_even_when_mail_fails(): void
    {
        // Simuluj selhání transportu — lead musí i tak zůstat v DB, klient dostane úspěch.
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP down'));

        $response = $this->postJson('/contact', $this->validPayload());

        $response->assertOk();

        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertSame(ContactSubmission::MAIL_FAILED, ContactSubmission::first()->mail_status);
    }

    public function test_validation_rejects_missing_gdpr_and_email(): void
    {
        $response = $this->postJson('/contact', $this->validPayload([
            'gdpr'  => null,
            'email' => 'neni-email',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gdpr', 'email']);

        $this->assertDatabaseCount('contact_submissions', 0);
    }
}
