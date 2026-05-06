<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioProjectOutcome extends Model
{
    protected $table = 'portfolio_project_outcomes';

    protected $fillable = [
        'project_id',
        'key',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PortfolioProject::class, 'project_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PortfolioProjectOutcomeTranslation::class, 'outcome_id');
    }

    public function translation(?string $locale = null): ?PortfolioProjectOutcomeTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'cs');
    }
}
