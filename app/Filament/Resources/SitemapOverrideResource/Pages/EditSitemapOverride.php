<?php

namespace App\Filament\Resources\SitemapOverrideResource\Pages;

use App\Filament\Resources\SitemapOverrideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSitemapOverride extends EditRecord
{
    protected static string $resource = SitemapOverrideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
