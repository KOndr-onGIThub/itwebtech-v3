<?php

namespace App\Models;

use App\Models\Slugs\ProjectSlug;
use App\Models\Translations\ProjectTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'published'  => 'boolean',
        'comparison' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function slugs(): HasMany
    {
        return $this->hasMany(ProjectSlug::class);
    }

    public function screens(): HasMany
    {
        return $this->hasMany(ProjectScreen::class)->orderBy('position');
    }

    public function translation(string $locale = null): ?ProjectTranslation
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
