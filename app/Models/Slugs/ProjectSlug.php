<?php

namespace App\Models\Slugs;

use A17\Twill\Models\Model;

class ProjectSlug extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'slug',
        'locale',
        'active',
    ];
}
