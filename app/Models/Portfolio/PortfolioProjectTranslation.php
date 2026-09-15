<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProjectTranslation extends Model
{
    protected $table = 'portfolio_project_translations';

    protected $fillable = [
        'project_id',
        'locale',
        'title',
        'subtitle',
        'summary',
        'description',
        'challenge',
        'solution',
        'result',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PortfolioProject::class, 'project_id');
    }
}
