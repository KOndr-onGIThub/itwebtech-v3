<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Poptávka z homepage, FAQ mikroformuláře nebo landingu.
 *
 * OND-264: `mail_status` sleduje odeslání notifikace — stejný vzor jako
 * u [[ContactSubmission]]. DB je zdroj pravdy, e-mail je notifikace.
 */
class LandingLead extends Model
{
    public const MAIL_PENDING = 'pending';

    public const MAIL_SENT = 'sent';

    public const MAIL_FAILED = 'failed';

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'budget',
        'message',
        'source',
        'mail_status',
        'mail_error',
        'ip_address',
        'user_agent',
    ];
}
