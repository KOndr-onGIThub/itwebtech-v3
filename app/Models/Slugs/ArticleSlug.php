<?php

namespace App\Models\Slugs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleSlug extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'article_id',
        'slug',
        'locale',
        'active',
    ];
}
