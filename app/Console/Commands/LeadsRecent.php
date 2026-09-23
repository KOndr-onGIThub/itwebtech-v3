<?php

namespace App\Console\Commands;

use App\Models\ContactSubmission;
use Illuminate\Console\Command;

/**
 * OND-173: Verifikační kanál pro QA agenta.
 *
 * Umožňuje ověřit doručení leadu z kontaktního formuláře BEZ čtení schránky —
 * čte přímo z DB (zdroj pravdy) a ukazuje stav odeslání notifikačního e-mailu.
 *
 * Příklady:
 *   php artisan leads:recent
 *   php artisan leads:recent --limit=5 --json
 *   php artisan leads:recent --subject="TEST: automatická verifikace"
 *   php artisan leads:recent --email=ondrej@example.com --json
 *
 * Návratový kód: 0 = nalezeny záznamy, 1 = žádný záznam (užitečné pro smoke check).
 */
class LeadsRecent extends Command
{
    protected $signature = 'leads:recent
        {--limit=10 : Kolik posledních leadů vypsat}
        {--json : Vypiš strojově čitelný JSON místo tabulky}
        {--email= : Filtruj podle e-mailu odesílatele (LIKE)}
        {--subject= : Filtruj podle předmětu (LIKE)}';

    protected $description = 'Vypíše poslední leady z kontaktního formuláře + stav odeslání notifikace (OND-173).';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $query = ContactSubmission::query()->latest('id');

        if ($email = $this->option('email')) {
            $query->where('email', 'like', '%'.$email.'%');
        }

        if ($subject = $this->option('subject')) {
            $query->where('subject', 'like', '%'.$subject.'%');
        }

        $leads = $query->limit($limit)->get();

        if ($this->option('json')) {
            $this->line($leads->map(fn (ContactSubmission $l) => [
                'id'          => $l->id,
                'name'        => $l->name,
                'email'       => $l->email,
                'tel'         => $l->tel,
                'subject'     => $l->subject,
                'locale'      => $l->locale,
                'mail_status' => $l->mail_status,
                'mail_error'  => $l->mail_error,
                // OND-264: ať je na jeden pohled vidět, jestli přílohy dorazily.
                'attachments' => $l->attachments ?? [],
                'created_at'  => $l->created_at?->toIso8601String(),
            ])->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return $leads->isEmpty() ? self::FAILURE : self::SUCCESS;
        }

        if ($leads->isEmpty()) {
            $this->warn('Žádný lead nenalezen.');

            return self::FAILURE;
        }

        $this->table(
            ['ID', 'Jméno', 'E-mail', 'Předmět', 'Lang', 'Mail', 'Přílohy', 'Vytvořeno'],
            $leads->map(fn (ContactSubmission $l) => [
                $l->id,
                \Illuminate\Support\Str::limit($l->name, 20),
                \Illuminate\Support\Str::limit($l->email, 28),
                \Illuminate\Support\Str::limit($l->subject ?? '—', 28),
                $l->locale ?? '—',
                $l->mail_status,
                count($l->attachments ?? []) ?: '—',
                $l->created_at?->format('d.m.Y H:i'),
            ])->toArray(),
        );

        return self::SUCCESS;
    }
}
