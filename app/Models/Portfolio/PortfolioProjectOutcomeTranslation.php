<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProjectOutcomeTranslation extends Model
{
    protected $table = 'portfolio_project_outcome_translations';

    protected $fillable = [
        'outcome_id',
        'locale',
        'label',
        'value',
        'description',
    ];

    public function outcome(): BelongsTo
    {
        return $this->belongsTo(PortfolioProjectOutcome::class, 'outcome_id');
    }
}
