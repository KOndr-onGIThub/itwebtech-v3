<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Audit záznam o úspěšném přihlášení do admin panelu.
 *
 * Zapisuje listener LogAdminLoginToAuditTrail na Auth\Events\Login.
 */
class AdminLoginLog extends Model
{
    protected $table = 'admin_login_log';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
