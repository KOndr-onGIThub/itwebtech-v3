<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapOverride extends Model
{
    protected $fillable = [
        'url',
        'priority',
        'changefreq',
        'lastmod',
        'is_excluded',
    ];

    protected $casts = [
        'priority' => 'float',
        'is_excluded' => 'bool',
        'lastmod' => 'datetime',
    ];
}
