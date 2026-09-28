<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * OND-173: Kontaktní formulář musí lead uložit do DB a odeslat notifikaci.
 *
 * OND-448 (B-01): `POST /contact` je jediný poptávkový formulář webu
 * (homepage i /kontakt). Bez předmětu a bez zaškrtávacího souhlasu, zpráva
 * je povinná, `source` rozliší místo a chyby jsou v jazyce stránky.
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
            'message' => 'Dobrý den, mám zájem o web.',
            'source'  => 'contact',
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

    public function test_validation_requires_message_and_valid_email_but_no_consent(): void
    {
        $response = $this->postJson('/contact', $this->validPayload([
            'message' => '',
            'email'   => 'neni-email',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message', 'email'])
            ->assertJsonMissingValidationErrors(['gdpr', 'subject']);

        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_source_is_stored_and_unknown_source_falls_back_to_contact(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->validPayload(['source' => 'home', 'email' => 'a@example.com']))->assertOk();
        $this->postJson('/contact', $this->validPayload(['source' => 'contact', 'email' => 'b@example.com']))->assertOk();
        $this->postJson('/contact', $this->validPayload(['source' => 'nesmysl', 'email' => 'c@example.com']))->assertOk();

        $this->assertSame(
            ['a@example.com' => 'home', 'b@example.com' => 'contact', 'c@example.com' => 'contact'],
            ContactSubmission::orderBy('id')->pluck('source', 'email')->all(),
        );
        $this->assertNull(ContactSubmission::first()->subject);

        Mail::assertSent(ContactMessage::class, fn (ContactMessage $mail) => $mail->submission->source === 'home'
            && str_contains($mail->envelope()->subject, 'homepage'));
    }

    /**
     * `POST /contact` nemá jazyk v URL. Jazyk stránky nese pole `locale`
     * a musí platit už pro validaci — dřív byly hlášky vždycky česky.
     */
    public function test_validation_messages_follow_the_page_language(): void
    {
        $expected = [
            'cs' => 'Pole zpráva je povinné.',
            'en' => 'The message field is required.',
            'de' => 'Das Feld Nachricht ist erforderlich.',
        ];

        foreach ($expected as $locale => $message) {
            $this->postJson('/contact', $this->validPayload(['message' => '', 'locale' => $locale]))
                ->assertStatus(422)
                ->assertJsonPath('errors.message.0', $message);
        }
    }

    public function test_stored_locale_is_the_page_language(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->validPayload(['locale' => 'de']))->assertOk();

        $this->assertSame('de', ContactSubmission::first()->locale);
    }
}
