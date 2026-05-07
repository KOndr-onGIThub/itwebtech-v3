<?php

namespace App\Filament\Resources\SitemapEntryResource\Pages;

use App\Filament\Resources\SitemapEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSitemapEntry extends EditRecord
{
    protected static string $resource = SitemapEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
