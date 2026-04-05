<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingLead extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'budget',
        'message',
        'source',
        'ip_address',
        'user_agent',
    ];
}
