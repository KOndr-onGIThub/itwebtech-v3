<?php

namespace App\Models\Revisions;

use Illuminate\Database\Eloquent\Model;

class ProjectRevision extends Model
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
