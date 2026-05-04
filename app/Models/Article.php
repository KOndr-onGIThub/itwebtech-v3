<?php

namespace App\Models;

use App\Models\Slugs\ArticleSlug;
use App\Models\Translations\ArticleTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'published',
        'position',
        'publish_start_date',
        'publish_end_date',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function slugs(): HasMany
    {
        return $this->hasMany(ArticleSlug::class);
    }

    public function translation(string $locale = null): ?ArticleTranslation
    {
        $locale ??= app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?? $this->translations->where('locale', 'cs')->first();
    }

    public function slug(string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $record = $this->slugs->where('locale', $locale)->where('active', true)->first()
            ?? $this->slugs->where('locale', 'cs')->where('active', true)->first();
        return $record?->slug;
    }
}
