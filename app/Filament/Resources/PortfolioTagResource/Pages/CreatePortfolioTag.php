<?php

namespace App\Filament\Resources\PortfolioTagResource\Pages;

use App\Filament\Resources\PortfolioTagResource;
use App\Models\Portfolio\PortfolioTag;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioTag extends CreateRecord
{
    protected static string $resource = PortfolioTagResource::class;

    /** @var array<string, mixed>|null */
    protected ?array $pendingTranslations = null;

    /**
     * Z dat formuláře oddělíme `translations` (sloupec `portfolio_tags` ho nezná)
     * a uložíme je až po vytvoření tagu v `afterCreate()`.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingTranslations = $data['translations'] ?? [];
        unset($data['translations']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var PortfolioTag $record */
        $record = $this->record;

        PortfolioTagResource::persistTranslations($record, [
            'translations' => $this->pendingTranslations ?? [],
        ]);
    }
}
