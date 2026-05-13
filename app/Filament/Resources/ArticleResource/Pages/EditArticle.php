<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    /** @var array{translations: array, original_slug: ?string, is_published: bool}|null */
    protected ?array $sideEffects = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Article $record */
        $record = $this->record;

        return ArticleResource::fillFormData($record, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var Article $record */
        $record = $this->record;

        [$articleData, $sideEffects] = ArticleResource::splitFormData($data, $record);
        $this->sideEffects = $sideEffects;

        return $articleData;
    }

    protected function afterSave(): void
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
