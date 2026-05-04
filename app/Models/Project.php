<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model implements Sortable
{
    use HasTranslation, HasSlug, HasMedias, HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'position',
        'kind',
        'price_czk',
        'price_eur',
        'comparison',
        'img_before',
        'img_after',
        'customer',
        'client_photo',
        'client_name',
        'client_role',
        'testimonial_source_img',
        'testimonial_src',
    ];

    public $translatedAttributes = [
        'active',
        'title',
        'og_img',
        'description',
        'content',
        'testimonial',
        'client_says',
        'cta',
    ];

    public $slugAttributes = [
        'title',
    ];

    public function screens(): HasMany
    {
        return $this->hasMany(ProjectScreen::class)->orderBy('position');
    }
}
