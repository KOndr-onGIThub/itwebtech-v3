<?php

namespace App\Models\Slugs;

use A17\Twill\Models\Model;

class ArticleSlug extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'article_id',
        'slug',
        'locale',
        'active',
    ];
}
