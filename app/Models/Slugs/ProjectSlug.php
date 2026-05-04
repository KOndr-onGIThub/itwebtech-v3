<?php

namespace App\Models\Slugs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSlug extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'slug',
        'locale',
        'active',
    ];
}
