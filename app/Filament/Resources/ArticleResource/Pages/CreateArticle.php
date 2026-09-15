<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    /** @var array{translations: array, original_slug: ?string, is_published: bool}|null */
    protected ?array $sideEffects = null;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$articleData, $sideEffects] = ArticleResource::splitFormData($data);
        $this->sideEffects = $sideEffects;

        return $articleData;
    }

    protected function afterCreate(): void
    {
        /** @var Article $record */
        $record = $this->record;

        ArticleResource::applySideEffects($record, $this->sideEffects ?? [
            'translations'  => [],
            'original_slug' => null,
            'is_published'  => false,
        ]);
    }
}
