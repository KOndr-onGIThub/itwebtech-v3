<?php

namespace App\Filament\Resources\PortfolioTagResource\Pages;

use App\Filament\Resources\PortfolioTagResource;
use App\Models\Portfolio\PortfolioTag;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioTag extends EditRecord
{
    protected static string $resource = PortfolioTagResource::class;

    /** @var array<string, mixed>|null */
    protected ?array $pendingTranslations = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Předvyplnění překladů z relace.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var PortfolioTag $record */
        $record = $this->record;
        $record->loadMissing('translations');

        return PortfolioTagResource::fillTranslations($record, $data);
    }

    /**
     * Před uložením oddělíme překlady — `portfolio_tags` je nemá.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingTranslations = $data['translations'] ?? [];
        unset($data['translations']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var PortfolioTag $record */
        $record = $this->record;

        PortfolioTagResource::persistTranslations($record, [
            'translations' => $this->pendingTranslations ?? [],
        ]);
    }
}
