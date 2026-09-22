<?php

namespace App\Support;

use App\Mail\LeadMessage;
use App\Models\LandingLead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * OND-264: Jedno místo, odkud odchází notifikace o poptávce.
 *
 * Poptávky chodí ze tří formulářů (homepage, FAQ, landing) a každý si dřív
 * řešil ukládání sám — notifikace nakonec nechodila z žádného. Tahle třída
 * drží stejný kontrakt jako ContactController: DB je zdroj pravdy, e-mail
 * je notifikace a jeho selhání se nikdy nespolkne, jen se zapíše a zaloguje.
 */
class LeadMailer
{
    public static function notify(LandingLead $lead): void
    {
        $recipient = config('mail.contact_to');

        try {
            Mail::to($recipient)->send(new LeadMessage($lead));

            $lead->update(['mail_status' => LandingLead::MAIL_SENT]);

            Log::info('OND-264 poptávka: notifikace odeslána.', [
                'lead_id'   => $lead->id,
                'source'    => $lead->source,
                'recipient' => $recipient,
            ]);
        } catch (Throwable $e) {
            report($e);

            $lead->update([
                'mail_status' => LandingLead::MAIL_FAILED,
                'mail_error'  => mb_substr($e->getMessage(), 0, 1000),
            ]);

            Log::error('OND-264 poptávka: odeslání notifikace selhalo (lead je uložen v DB).', [
                'lead_id'   => $lead->id,
                'source'    => $lead->source,
                'recipient' => $recipient,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}
