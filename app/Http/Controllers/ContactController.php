<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    /**
     * OND-173: Zpracování kontaktního formuláře.
     *
     * Pořadí je záměrné a je jádrem fixu:
     *   1. Lead se VŽDY nejdřív uloží do DB (`contact_submissions`) — DB je
     *      zdroj pravdy. Když se uložení nepovede, vrátíme klientovi chybu
     *      (a zamezíme tichému ztracení leadu).
     *   2. Až poté se pokusíme odeslat notifikační e-mail. E-mail se posílá
     *      SYNCHRONNĚ (bez queue → nezávisí na workeru). Selhání mailu NENÍ
     *      tiché: zaloguje se a zapíše se do `mail_status`/`mail_error`.
     *   3. Klientovi vrátíme úspěch jen když je lead bezpečně uložen — i když
     *      e-mail selže. Lead nikdy nezávisí jen na e-mailu.
     */
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'tel'     => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'gdpr'    => 'required|accepted',
        ]);

        // 1) Ulož lead do DB — zdroj pravdy. Když selže, klient dostane chybu.
        try {
            $submission = ContactSubmission::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'tel'         => $data['tel'] ?? null,
                'subject'     => $data['subject'] ?? null,
                'message'     => $data['message'] ?? null,
                'locale'      => App::getLocale(),
                'mail_status' => ContactSubmission::MAIL_PENDING,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (Throwable $e) {
            report($e);
            Log::error('OND-173 kontaktní formulář: uložení leadu do DB selhalo.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(
                ['message' => __('contact.message_error')],
                500
            );
        }

        // 2) Notifikační e-mail — synchronně, mimo queue. Selhání nesmí být tiché.
        $recipient = config('mail.contact_to');

        try {
            Mail::to($recipient)->send(new ContactMessage($submission));

            $submission->update(['mail_status' => ContactSubmission::MAIL_SENT]);

            Log::info('OND-173 kontaktní formulář: notifikace odeslána.', [
                'lead_id'   => $submission->id,
                'recipient' => $recipient,
            ]);
        } catch (Throwable $e) {
            report($e);

            $submission->update([
                'mail_status' => ContactSubmission::MAIL_FAILED,
                'mail_error'  => mb_substr($e->getMessage(), 0, 1000),
            ]);

            // Loguj hlasitě — ale lead je bezpečně v DB, takže klient dál dostane úspěch.
            Log::error('OND-173 kontaktní formulář: odeslání notifikace selhalo (lead je uložen v DB).', [
                'lead_id'   => $submission->id,
                'recipient' => $recipient,
                'error'     => $e->getMessage(),
            ]);
        }

        // 3) Lead je uložen → klient dostane úspěch (nezávisle na stavu e-mailu).
        return response()->json(['message' => __('contact.message_success')]);
    }
}
