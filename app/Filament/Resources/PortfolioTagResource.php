<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioTagResource\Pages;
use App\Models\Portfolio\PortfolioTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PortfolioTagResource extends Resource
{
    protected static ?string $model = PortfolioTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?string $navigationLabel = 'Tagy';

    protected static ?string $modelLabel = 'tag';

    protected static ?string $pluralModelLabel = 'tagy';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema(static::formSchema());
    }

    /**
     * Sdílené schéma pro PortfolioTag formulář (used také v `createOptionForm`
     * z PortfolioProjectResource — tagy se mohou zakládat přímo v projektu).
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public static function formSchema(): array
    {
        return [
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(191)
                ->unique(ignoreRecord: true)
                ->alphaDash()
                ->live(onBlur: true)
                ->helperText('URL identifikátor — automaticky z názvu (CS).'),

            Forms\Components\TextInput::make('sort_order')
                ->label('Pořadí')
                ->numeric()
                ->default(0),

            Forms\Components\Tabs::make('translations')
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Česky')
                        ->schema([
                            Forms\Components\TextInput::make('translations.cs.name')
                                ->label('Název (CS)')
                                ->required()
                                ->maxLength(191)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, Forms\Set $set, Forms\Get $get): void {
                                    if (! filled($state)) {
                                        return;
                                    }
                                    if (! filled($get('slug'))) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                        ]),
                    Forms\Components\Tabs\Tab::make('English')
                        ->badge(fn (Forms\Get $get) => filled($get('translations.en.name')) ? null : '⚠')
                        ->schema([
                            Forms\Components\TextInput::make('translations.en.name')
                                ->label('Name (EN)')
                                ->maxLength(191)
                                ->helperText('Doporučeno — pokud chybí, zobrazí se CS.'),
                        ]),
                    Forms\Components\Tabs\Tab::make('Deutsch')
                        ->badge(fn (Forms\Get $get) => filled($get('translations.de.name')) ? null : '⚠')
                        ->schema([
                            Forms\Components\TextInput::make('translations.de.name')
                                ->label('Name (DE)')
                                ->maxLength(191)
                                ->helperText('Empfohlen — fehlt es, wird CS angezeigt.'),
                        ]),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Název (aktivní lokalizace)')
                    ->state(fn (PortfolioTag $record) => $record->translation()?->name ?? '—'),
                Tables\Columns\TextColumn::make('projects_count')
                    ->label('Projektů')
                    ->counts('projects')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Pořadí')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPortfolioTags::route('/'),
            'create' => Pages\CreatePortfolioTag::route('/create'),
            'edit'   => Pages\EditPortfolioTag::route('/{record}/edit'),
        ];
    }

    /**
     * Načti aktuální překlady do formy ve tvaru `translations.{locale}.name`.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function fillTranslations(PortfolioTag $record, array $data): array
    {
        $data['translations'] = [];
        foreach (['cs', 'en', 'de'] as $locale) {
            $tr = $record->translations->firstWhere('locale', $locale);
            $data['translations'][$locale] = [
                'name' => $tr?->name,
            ];
        }

        return $data;
    }

    /**
     * Persistence překladů: upsert podle (tag_id, locale).
     *
     * @param  array<string, mixed>  $data
     */
    public static function persistTranslations(PortfolioTag $record, array $data): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            $name = $data['translations'][$locale]['name'] ?? null;

            if (! filled($name)) {
                // EN/DE může být prázdné — smaž případný starý záznam.
                if ($locale !== 'cs') {
                    $record->translations()->where('locale', $locale)->delete();
                }
                continue;
            }

            $record->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name'   => $name],
            );
        }
    }
}
