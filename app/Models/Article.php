<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasNesting;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Article extends Model implements Sortable
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasRevisions, HasPosition, HasNesting;

    protected $fillable = [
        'published',
        'position',
    ];

    public $translatedAttributes = [
        'active',
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

    public $slugAttributes = [
        'title',
    ];
}
