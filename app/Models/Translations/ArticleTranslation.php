<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;

class ArticleTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'active',
        'locale',
        'title',
        'description',
        'perex',
        'content_1',
        'content_mid',
        'content_2',
        'bonus',
        'extra',
        'img_preview',
        'img_main',
        'img_mid',
        'img_end',
    ];
}
