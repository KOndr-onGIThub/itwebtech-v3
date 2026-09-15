<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * OND-173: Lead z kontaktního formuláře. DB je zdroj pravdy — e-mail je
 * pouze notifikace. `mail_status` sleduje stav odeslání notifikace.
 */
class ContactSubmission extends Model
{
    public const MAIL_PENDING = 'pending';

    public const MAIL_SENT = 'sent';

    public const MAIL_FAILED = 'failed';

    protected $fillable = [
        'name',
        'email',
        'tel',
        'subject',
        'message',
        'locale',
        'mail_status',
        'mail_error',
        'ip_address',
        'user_agent',
    ];
}
