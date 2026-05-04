<?php

namespace App\Models\Revisions;

use A17\Twill\Models\Model;

class ArticleRevision extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'payload',
        'author_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
