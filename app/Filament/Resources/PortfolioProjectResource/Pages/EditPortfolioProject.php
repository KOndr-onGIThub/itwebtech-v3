<?php

namespace App\Filament\Resources\PortfolioProjectResource\Pages;

use App\Filament\Resources\PortfolioProjectResource;
use App\Models\Portfolio\PortfolioProject;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioProject extends EditRecord
{
    protected static string $resource = PortfolioProjectResource::class;

    /** @var array{translations: array, outcomes: array, screenshots: array, is_published: bool}|null */
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
        /** @var PortfolioProject $record */
        $record = $this->record;

        return PortfolioProjectResource::fillFormData($record, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var PortfolioProject $record */
        $record = $this->record;

        [$projectData, $sideEffects] = PortfolioProjectResource::splitFormData($data, $record);
        $this->sideEffects = $sideEffects;

        return $projectData;
    }

    protected function afterSave(): void
    {
        /** @var PortfolioProject $record */
        $record = $this->record;

        PortfolioProjectResource::applySideEffects($record, $this->sideEffects ?? [
            'translations' => [],
            'outcomes'     => [],
            'screenshots'  => [],
            'is_published' => false,
        ]);
    }
}
