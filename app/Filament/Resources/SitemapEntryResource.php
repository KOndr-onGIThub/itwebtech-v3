<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SitemapEntryResource\Pages;
use App\Models\SitemapEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class SitemapEntryResource extends Resource
{
    protected static ?string $model = SitemapEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Sitemap';

    protected static ?string $navigationLabel = 'Ruční záznamy';

    protected static ?string $modelLabel = 'ruční záznam';

    protected static ?string $pluralModelLabel = 'ruční záznamy';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->label('URL')
                ->required()
                ->maxLength(2048)
                ->url()
                ->helperText('Absolutní URL záznamu pro sitemap.xml.'),

            Forms\Components\TextInput::make('priority')
                ->label('Priorita')
                ->numeric()
                ->step(0.1)
                ->minValue(0.0)
                ->maxValue(1.0)
                ->nullable()
                ->helperText('Hodnota 0.0–1.0.'),

            Forms\Components\Select::make('changefreq')
                ->label('Frekvence změn')
                ->options([
                    'always'  => 'always',
                    'hourly'  => 'hourly',
                    'daily'   => 'daily',
                    'weekly'  => 'weekly',
                    'monthly' => 'monthly',
                    'yearly'  => 'yearly',
                    'never'   => 'never',
                ])
                ->nullable(),

            Forms\Components\DateTimePicker::make('lastmod')
                ->label('Poslední změna')
                ->seconds(false)
                ->nullable(),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktivní')
                ->helperText('Vypnuté záznamy se v sitemap.xml neobjeví.')
                ->default(true),

            Forms\Components\Textarea::make('note')
                ->label('Poznámka')
                ->rows(3)
                ->maxLength(2048)
                ->nullable()
                ->helperText('Interní poznámka pro admina, do sitemap se nepropisuje.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->sortable()
                    ->limit(60)
                    ->tooltip(fn (SitemapEntry $record) => $record->url),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priorita')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),

                Tables\Columns\TextColumn::make('changefreq')
                    ->label('Frekvence')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktivní')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lastmod')
                    ->label('Poslední změna')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('note')
                    ->label('Poznámka')
                    ->limit(40)
                    ->tooltip(fn (SitemapEntry $record) => $record->note)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivní')
                    ->boolean()
                    ->trueLabel('Pouze aktivní')
                    ->falseLabel('Pouze neaktivní')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('regenerate')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSitemapEntries::route('/'),
            'create' => Pages\CreateSitemapEntry::route('/create'),
            'edit'   => Pages\EditSitemapEntry::route('/{record}/edit'),
        ];
    }
}
