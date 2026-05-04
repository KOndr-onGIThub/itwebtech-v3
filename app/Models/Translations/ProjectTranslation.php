<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;

class ProjectTranslation extends Model
{
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
