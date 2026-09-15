<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapEntry extends Model
{
    protected $fillable = [
        'url',
        'priority',
        'changefreq',
        'lastmod',
        'is_active',
        'note',
    ];

    protected $casts = [
        'priority' => 'float',
        'is_active' => 'bool',
        'lastmod' => 'datetime',
    ];
}
