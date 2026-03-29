<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasNesting;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Article extends Model implements Sortable
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasFiles, HasRevisions, HasPosition, HasNesting;

    Protected $primaryKey = "id";

    protected $fillable = [
        'published',
        'title',
        'description',
        'position',
        'img_preview',
        'img_main',
        'perex',
        'content_1',
        'content_mid',
        'img_mid',
        'content_2',
        'img_end',
        'bonus',
        'extra',
    ];
    
    public $translatedAttributes = [
        'title',
        'description',
        'active',
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
