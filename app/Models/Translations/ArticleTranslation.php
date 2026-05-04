<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleTranslation extends Model
{
    use SoftDeletes;

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
