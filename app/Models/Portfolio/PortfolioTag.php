<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioTag extends Model
{
    protected $table = 'portfolio_tags';

    protected $fillable = [
        'slug',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PortfolioTagTranslation::class, 'tag_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            PortfolioProject::class,
            'portfolio_project_tag',
            'tag_id',
            'project_id'
        );
    }

    public function translation(?string $locale = null): ?PortfolioTagTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'cs');
    }
}
