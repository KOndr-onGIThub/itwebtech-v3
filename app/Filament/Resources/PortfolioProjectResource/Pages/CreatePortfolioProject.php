<?php

namespace App\Filament\Resources\PortfolioProjectResource\Pages;

use App\Filament\Resources\PortfolioProjectResource;
use App\Models\Portfolio\PortfolioProject;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioProject extends CreateRecord
{
    protected static string $resource = PortfolioProjectResource::class;

    /** @var array{translations: array, outcomes: array, screenshots: array, is_published: bool}|null */
    protected ?array $sideEffects = null;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$projectData, $sideEffects] = PortfolioProjectResource::splitFormData($data);
        $this->sideEffects = $sideEffects;

        return $projectData;
    }

    protected function afterCreate(): void
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
