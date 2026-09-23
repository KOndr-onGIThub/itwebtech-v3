<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * OND-264: Přílohy z kontaktního formuláře se tiše zahazovaly — server je
 * nikdy nepřečetl. Tyto testy hlídají, že dorazí na disk i do notifikace
 * a že přes limity neprojde nic potichu.
 */
class ContactAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Novák',
            'email'   => 'jan@example.com',
            'tel'     => '+420123456789',
            'subject' => 'Poptávka webu',
            'message' => 'Dobrý den, posílám podklady.',
            'gdpr'    => '1',
        ], $overrides);
    }

    /**
     * Drop zóna si limity bere z config/contact.php — když se blade rozbije,
     * spadne celá stránka /kontakt, ne jen widget.
     */
    public function test_contact_page_renders_the_upload_widget_with_config_limits(): void
    {
        $response = $this->get('/kontakt');

        $response->assertOk();
        $response->assertSee('name="attachment[]"', false);
        $response->assertSee('maxFiles: '.config('contact.uploads.max_files'), false);
        $response->assertSee('fileDropZone(', false);
    }

    public function test_attachments_are_stored_and_linked_to_the_lead(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $response = $this->postJson('/contact', $this->validPayload([
            'attachment' => [
                UploadedFile::fake()->create('zadání webu.pdf', 120, 'application/pdf'),
                UploadedFile::fake()->image('logo.png'),
            ],
        ]));

        $response->assertOk();

        $lead = ContactSubmission::first();
        $this->assertCount(2, $lead->attachments);

        // Původní jména zůstávají v metadatech (a tedy i v e-mailu)…
        $this->assertSame('zadání webu.pdf', $lead->attachments[0]['name']);
        $this->assertSame('logo.png', $lead->attachments[1]['name']);

        // …na disku jsou pod bezpečným jménem a soubor tam opravdu leží.
        foreach ($lead->attachments as $file) {
            Storage::disk(config('contact.uploads.disk'))->assertExists($file['path']);
            $this->assertStringStartsWith('contact-attachments/', $file['path']);
            $this->assertTrue($file['mailed']);
        }

        $this->assertStringContainsString('01-zadani-webu.pdf', $lead->attachments[0]['path']);
    }

    public function test_attachments_are_attached_to_the_notification_mail(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [UploadedFile::fake()->create('nabidka.pdf', 64, 'application/pdf')],
        ]))->assertOk();

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $attachments = $mail->attachments();

            return count($attachments) === 1
                && $mail->hasTo(config('mail.contact_to'));
        });
    }

    /**
     * Regrese k produkční chybě „No hint path defined for [mail]" — šablona je
     * markdownová, Mailable ji ale posílal jako obyčejný view, takže KAŽDÁ
     * notifikace z formuláře na produkci spadla. Mail::fake() to neodhalí,
     * protože nic nerenderuje — proto se tu renderuje doopravdy.
     */
    public function test_notification_mail_renders_including_attachment_list(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [UploadedFile::fake()->create('podklady.pdf', 64, 'application/pdf')],
        ]))->assertOk();

        $html = (new ContactMessage(ContactSubmission::first()))->render();

        $this->assertStringContainsString('Jan Novák', $html);
        $this->assertStringContainsString('podklady.pdf', $html);
        $this->assertStringContainsString('přiloženo k tomuto e-mailu', $html);
    }

    public function test_too_many_files_are_rejected_with_a_localized_error(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $files = [];
        for ($i = 0; $i < config('contact.uploads.max_files') + 1; $i++) {
            $files[] = UploadedFile::fake()->create("soubor-{$i}.pdf", 10, 'application/pdf');
        }

        $this->postJson('/contact', $this->validPayload(['attachment' => $files]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['attachment'])
            ->assertJsonFragment(['attachment' => [__('contact.upload.error_too_many')]]);

        $this->assertDatabaseCount('contact_submissions', 0);
        Mail::assertNothingSent();
    }

    public function test_single_file_over_the_limit_is_rejected(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $overLimitKb = config('contact.uploads.max_file_mb') * 1024 + 256;

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [UploadedFile::fake()->create('velky.pdf', $overLimitKb, 'application/pdf')],
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['attachment.0'])
            ->assertJsonFragment([
                'attachment.0' => [__('contact.upload.error_per_file', [
                    'max' => config('contact.uploads.max_file_mb'),
                ])],
            ]);

        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_total_size_over_the_limit_is_rejected(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        // Tři soubory pod limitem na kus, ale dohromady přes celkový strop.
        $perFileKb = config('contact.uploads.max_file_mb') * 1024;

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [
                UploadedFile::fake()->create('a.pdf', $perFileKb, 'application/pdf'),
                UploadedFile::fake()->create('b.pdf', $perFileKb, 'application/pdf'),
                UploadedFile::fake()->create('c.pdf', $perFileKb, 'application/pdf'),
            ],
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['attachment'])
            ->assertJsonFragment(['attachment' => [__('contact.upload.error_too_large')]]);

        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_disallowed_file_type_is_rejected(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [UploadedFile::fake()->create('skript.exe', 10, 'application/octet-stream')],
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['attachment.0']);

        $this->assertDatabaseCount('contact_submissions', 0);
        Storage::disk(config('contact.uploads.disk'))->assertDirectoryEmpty('/');
    }

    public function test_submission_without_attachments_still_works(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        $this->postJson('/contact', $this->validPayload())->assertOk();

        $lead = ContactSubmission::first();
        $this->assertNull($lead->attachments);
        $this->assertSame(ContactSubmission::MAIL_SENT, $lead->mail_status);
    }

    /**
     * Co se nevejde do rozpočtu e-mailu, musí zůstat na disku a být vypsané
     * v těle zprávy — nikdy se nesmí jen tiše zahodit.
     */
    public function test_files_over_the_mail_budget_are_stored_but_not_attached(): void
    {
        Storage::fake(config('contact.uploads.disk'));
        Mail::fake();

        config(['contact.uploads.mail_budget_mb' => 1]);

        $this->postJson('/contact', $this->validPayload([
            'attachment' => [
                UploadedFile::fake()->create('mala.pdf', 512, 'application/pdf'),
                UploadedFile::fake()->create('velka.pdf', 2048, 'application/pdf'),
            ],
        ]))->assertOk();

        $lead = ContactSubmission::first();

        $this->assertTrue($lead->attachments[0]['mailed']);
        $this->assertFalse($lead->attachments[1]['mailed']);

        foreach ($lead->attachments as $file) {
            Storage::disk(config('contact.uploads.disk'))->assertExists($file['path']);
        }

        $html = (new ContactMessage($lead))->render();
        $this->assertStringContainsString('velka.pdf', $html);
        $this->assertStringContainsString('nevešlo se do e-mailu', $html);
    }
}
