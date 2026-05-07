<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SitemapOverrideResource\Pages;
use App\Models\SitemapOverride;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class SitemapOverrideResource extends Resource
{
    protected static ?string $model = SitemapOverride::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Sitemap';

    protected static ?string $navigationLabel = 'Přepisy URL';

    protected static ?string $modelLabel = 'přepis URL';

    protected static ?string $pluralModelLabel = 'přepisy URL';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->label('URL')
                ->required()
                ->maxLength(2048)
                ->unique(ignoreRecord: true)
                ->url()
                ->helperText('Absolutní URL, např. https://itwebtech.cz/projekty'),

            Forms\Components\TextInput::make('priority')
                ->label('Priorita')
                ->numeric()
                ->step(0.1)
                ->minValue(0.0)
                ->maxValue(1.0)
                ->nullable()
                ->helperText('Hodnota 0.0–1.0. Pokud prázdné, použije se výchozí dle typu stránky.'),

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
                ->nullable()
                ->helperText('Pokud prázdné, použije se výchozí dle typu stránky.'),

            Forms\Components\DateTimePicker::make('lastmod')
                ->label('Poslední změna')
                ->seconds(false)
                ->nullable()
                ->helperText('Pokud prázdné, použije se generované datum (např. updated_at modelu).'),

            Forms\Components\Toggle::make('is_excluded')
                ->label('Vyloučit ze sitemap')
                ->helperText('Když je zapnuto, URL se v sitemap.xml vůbec neobjeví.')
                ->default(false),
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
                    ->tooltip(fn (SitemapOverride $record) => $record->url),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priorita')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),

                Tables\Columns\TextColumn::make('changefreq')
                    ->label('Frekvence')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_excluded')
                    ->label('Vyloučeno')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lastmod')
                    ->label('Poslední změna')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_excluded')
                    ->label('Vyloučeno ze sitemap'),
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
            'index'  => Pages\ListSitemapOverrides::route('/'),
            'create' => Pages\CreateSitemapOverride::route('/create'),
            'edit'   => Pages\EditSitemapOverride::route('/{record}/edit'),
        ];
    }
}
