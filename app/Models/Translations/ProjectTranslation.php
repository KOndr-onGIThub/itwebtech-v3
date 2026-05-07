<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTranslation extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'active',
        'locale',
        'title',
        'og_img',
        'description',
        'content',
        'testimonial',
        'client_says',
        'cta',
    ];
}
