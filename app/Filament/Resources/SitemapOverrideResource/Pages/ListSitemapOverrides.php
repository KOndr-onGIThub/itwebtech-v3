<?php

namespace App\Filament\Resources\SitemapOverrideResource\Pages;

use App\Filament\Resources\SitemapOverrideResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;

class ListSitemapOverrides extends ListRecords
{
    protected static string $resource = SitemapOverrideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('regenerate')
                ->label('Regenerovat sitemap')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function (): void {
                    Cache::forget(config('sitemap.cache_key'));

                    Notification::make()
                        ->title('Sitemap cache smazána')
                        ->body('Další požadavek na /sitemap.xml ji vygeneruje znovu.')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}
