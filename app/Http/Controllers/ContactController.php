<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
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
        $limits     = config('contact.uploads');
        $extensions = implode(',', $limits['extensions']);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'tel'          => 'nullable|string|max:50',
            'subject'      => 'nullable|string|max:255',
            'message'      => 'nullable|string|max:5000',
            'gdpr'         => 'required|accepted',
            // OND-264: přílohy se do teď nevalidovaly ani nečetly — mlčky se zahazovaly.
            'attachment'   => ['nullable', 'array', 'max:'.$limits['max_files']],
            'attachment.*' => ['file', 'max:'.($limits['max_file_mb'] * 1024), 'mimes:'.$extensions],
        ], [
            'attachment.array'      => __('contact.upload.error_failed'),
            'attachment.max'        => __('contact.upload.error_too_many'),
            'attachment.*.max'      => __('contact.upload.error_per_file', ['max' => $limits['max_file_mb']]),
            'attachment.*.mimes'    => __('contact.upload.error_mime', [
                'types' => Str::upper(implode(', ', $limits['extensions'])),
            ]),
            'attachment.*.file'     => __('contact.upload.error_failed'),
            'attachment.*.uploaded' => __('contact.upload.error_failed'),
        ]);

        // 0) Přílohy: součet přes všechny soubory + uložení na neveřejný disk.
        //    Validaci součtu nejde vyjádřit pravidlem, dělá se ručně.
        $files = array_values(array_filter(
            $request->file('attachment', []),
            fn ($file) => $file instanceof UploadedFile,
        ));

        $totalBytes = array_sum(array_map(fn (UploadedFile $f) => (int) $f->getSize(), $files));

        if ($totalBytes > $limits['max_total_mb'] * 1024 * 1024) {
            throw ValidationException::withMessages([
                'attachment' => __('contact.upload.error_too_large'),
            ]);
        }

        try {
            [$attachments, $storageDir] = $this->storeAttachments($files);
        } catch (Throwable $e) {
            report($e);
            Log::error('OND-264 kontaktní formulář: uložení příloh selhalo, lead se neukládá.', [
                'error' => $e->getMessage(),
                'files' => count($files),
            ]);

            return response()->json(
                ['message' => __('contact.upload.error_failed')],
                500
            );
        }

        // 1) Ulož lead do DB — zdroj pravdy. Když selže, klient dostane chybu.
        try {
            $submission = ContactSubmission::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'tel'         => $data['tel'] ?? null,
                'subject'     => $data['subject'] ?? null,
                'message'     => $data['message'] ?? null,
                'attachments' => $attachments ?: null,
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

            // Bez leadu nemá smysl držet osiřelé soubory na disku.
            if ($storageDir !== null) {
                Storage::disk(config('contact.uploads.disk'))->deleteDirectory($storageDir);
            }

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
                'lead_id'     => $submission->id,
                'recipient'   => $recipient,
                'attachments' => count($attachments),
            ]);
        } catch (Throwable $e) {
            report($e);

            $submission->update([
                'mail_status' => ContactSubmission::MAIL_FAILED,
                'mail_error'  => mb_substr($e->getMessage(), 0, 1000),
            ]);

            // Loguj hlasitě — ale lead je bezpečně v DB, takže klient dál dostane úspěch.
            Log::error('OND-173 kontaktní formulář: odeslání notifikace selhalo (lead je uložen v DB).', [
                'lead_id'     => $submission->id,
                'recipient'   => $recipient,
                'error'       => $e->getMessage(),
                'attachments' => array_column($attachments, 'path'),
            ]);
        }

        // 3) Lead je uložen → klient dostane úspěch (nezávisle na stavu e-mailu).
        return response()->json(['message' => __('contact.message_success')]);
    }

    /**
     * OND-264: Uloží přílohy na neveřejný disk a vrátí jejich metadata.
     *
     * Do notifikačního e-mailu se vejde jen `mail_budget_mb` — soubory nad
     * rámec rozpočtu se uloží taky, jen se neposílají mailem (`mailed` =
     * false) a e-mail je vypíše i s cestou. Nic se nezahazuje potichu.
     *
     * @param  list<UploadedFile>  $files
     * @return array{0: list<array{name:string,size:int,mime:string,disk:string,path:string,mailed:bool}>, 1: string|null}
     */
    private function storeAttachments(array $files): array
    {
        if ($files === []) {
            return [[], null];
        }

        $disk      = config('contact.uploads.disk');
        $budget    = config('contact.uploads.mail_budget_mb') * 1024 * 1024;
        $directory = 'contact-attachments/'.now()->format('Y-m').'/'.Str::ulid();

        $attachments = [];
        $mailedBytes = 0;

        try {
            foreach ($files as $index => $file) {
                // Velikost i jméno je nutné přečíst PŘED přesunem — po něm už
                // dočasný soubor neexistuje.
                $original = $file->getClientOriginalName();
                $size     = (int) $file->getSize();
                $mime     = $file->getMimeType() ?: 'application/octet-stream';

                $path = Storage::disk($disk)->putFileAs(
                    $directory,
                    $file,
                    $this->safeFilename($original, $index, $file->getClientOriginalExtension()),
                );

                if ($path === false) {
                    throw new RuntimeException('putFileAs vrátil false pro '.$original);
                }

                $fitsInMail = ($mailedBytes + $size) <= $budget;

                if ($fitsInMail) {
                    $mailedBytes += $size;
                }

                $attachments[] = [
                    'name'   => $original,
                    'size'   => $size,
                    'mime'   => $mime,
                    'disk'   => $disk,
                    'path'   => $path,
                    'mailed' => $fitsInMail,
                ];
            }
        } catch (Throwable $e) {
            // Částečně uložená dávka by jen zabírala místo — ukliď ji.
            Storage::disk($disk)->deleteDirectory($directory);

            throw $e;
        }

        return [$attachments, $directory];
    }

    /**
     * Bezpečné jméno souboru na disku. Původní jméno zůstává v metadatech
     * (a tedy i v e-mailu), na disku se jen nesmí objevit nic, co by lezlo
     * mimo adresář.
     */
    private function safeFilename(string $original, int $index, string $extension): string
    {
        $base = Str::slug(pathinfo($original, PATHINFO_FILENAME)) ?: 'priloha';
        $ext  = Str::lower($extension) ?: 'bin';

        return sprintf('%02d-%s.%s', $index + 1, Str::limit($base, 60, ''), $ext);
    }
}
