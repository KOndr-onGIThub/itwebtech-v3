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
        // OND-209: nepovinný lokalizovaný slug. NULL = použije se
        // jazyk-neutrální `portfolio_projects.slug`.
        'slug',
        'title',
        'subtitle',
        'summary',
        'description',
        'challenge',
        'solution',
        'result',
        // OND-449: B-09 zdroj výsledku, B-05 věta „co si na živém webu vyzkoušet“.
        'result_source',
        'live_hint',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PortfolioProject::class, 'project_id');
    }
}
