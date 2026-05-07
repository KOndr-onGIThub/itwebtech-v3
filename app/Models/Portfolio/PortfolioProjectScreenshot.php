<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioProjectScreenshot extends Model
{
    protected $table = 'portfolio_project_screenshots';

    protected $fillable = [
        'project_id',
        'path',
        'type',
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
        return $this->hasMany(PortfolioProjectScreenshotTranslation::class, 'screenshot_id');
    }

    public function translation(?string $locale = null): ?PortfolioProjectScreenshotTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'cs');
    }
}
