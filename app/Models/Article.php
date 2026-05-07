<?php

namespace App\Models;

use App\Models\Revisions\ArticleRevision;
use App\Models\Slugs\ArticleSlug;
use App\Models\Translations\ArticleTranslation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'published',
        'published_at',
        'position',
        'publish_start_date',
        'publish_end_date',
        'image_url',
        'author',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function slugs(): HasMany
    {
        return $this->hasMany(ArticleSlug::class);
    }

    /**
     * Audit historie slugů — všechny záznamy v `article_slugs` (cs/en/de, aktivní + neaktivní),
     * seřazeno od nejnovějších. Slouží pro 301 redirect lookup a Filament read-only audit panel.
     */
    public function slugHistory(): HasMany
    {
        return $this->hasMany(ArticleSlug::class)->orderByDesc('created_at');
    }

    /**
     * Snapshoty obsahu (read-only). Filament tab „Revize" čte tuto relaci.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class)->orderByDesc('created_at');
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

    /**
     * Aliasováno na existující sloupec `published`. Filament list view a form toggle
     * odkazují na `is_published`, abychom respektovali konvenci portfolia.
     */
    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => (bool) $this->published,
            set: fn ($value): array => ['published' => (bool) $value],
        );
    }

    /**
     * Cs-locale title accessor pro list view (zjednodušení sloupce „Titulek").
     */
    public function getTitleCsAttribute(): ?string
    {
        return $this->translations->firstWhere('locale', 'cs')?->title;
    }

    /**
     * Vrátí URL master featured image v public výstupu.
     *
     * - Pokud `image_url` začíná `articles/` → cesta v storage public disku
     *   (uploadováno přes Filament FileUpload disk='public').
     * - Jinak vrať raw hodnotu (např. legacy URL nebo prázdné).
     */
    public function getHeroImageUrlAttribute(): ?string
    {
        $value = $this->image_url;
        if (! filled($value)) {
            return null;
        }

        if (Str::startsWith($value, 'articles/')) {
            return Storage::disk('public')->url($value);
        }

        return $value;
    }
}
