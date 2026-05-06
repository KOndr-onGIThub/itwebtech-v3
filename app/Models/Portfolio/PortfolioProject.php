<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioProject extends Model
{
    use SoftDeletes;

    protected $table = 'portfolio_projects';

    protected $fillable = [
        'category',
        'slug',
        'client_name',
        'live_url',
        'year',
        'duration',
        'featured',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'featured'     => 'boolean',
        'sort_order'   => 'integer',
        'year'         => 'integer',
        'published_at' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PortfolioProjectTranslation::class, 'project_id');
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(PortfolioProjectScreenshot::class, 'project_id')
            ->orderBy('sort_order');
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(PortfolioProjectOutcome::class, 'project_id')
            ->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            PortfolioTag::class,
            'portfolio_project_tag',
            'project_id',
            'tag_id'
        );
    }

    /**
     * Pouze publikované projekty (published_at v minulosti / nyní).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Vrátí překlad pro daný locale s fallbackem na 'cs'.
     */
    public function translation(?string $locale = null): ?PortfolioProjectTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'cs');
    }
}
