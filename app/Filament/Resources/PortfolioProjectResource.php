<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioProjectResource\Pages;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Portfolio\PortfolioProjectOutcome;
use App\Models\Portfolio\PortfolioProjectScreenshot;
use App\Models\Portfolio\PortfolioTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PortfolioProjectResource extends Resource
{
    protected static ?string $model = PortfolioProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?string $navigationLabel = 'Projekty';

    protected static ?string $modelLabel = 'projekt';

    protected static ?string $pluralModelLabel = 'projekty';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('project')
                ->columnSpanFull()
                ->persistTabInQueryString()
                ->tabs([
                    static::basicsTab(),
                    static::tagsTab(),
                    static::translationsTab(),
                    static::outcomesTab(),
                    static::screenshotsTab(),
                ]),
        ]);
    }

    /* ==================================================================== */
    /*  Form tabs                                                           */
    /* ==================================================================== */

    protected static function basicsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Základ')
            ->icon('heroicon-o-identification')
            ->schema([
                Forms\Components\TextInput::make('client_name')
                    ->label('Klient')
                    ->maxLength(191)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                        if (! filled($state)) {
                            return;
                        }
                        if (! filled($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(191)
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->helperText('Jazyk-neutrální, použito v `/projekty/{slug}`.'),
                Forms\Components\Select::make('category')
                    ->label('Kategorie')
                    ->required()
                    ->options([
                        'website'     => 'Website',
                        'application' => 'Application',
                        'other'       => 'Other',
                    ])
                    ->default('website')
                    ->native(false),
                Forms\Components\TextInput::make('live_url')
                    ->label('Live URL')
                    ->url()
                    ->maxLength(191),
                Forms\Components\TextInput::make('year')
                    ->label('Rok')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100),
                Forms\Components\TextInput::make('duration')
                    ->label('Doba realizace')
                    ->maxLength(191)
                    ->placeholder('např. 3 měsíce'),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publikováno')
                    ->dehydrated(false)
                    ->helperText('Po zapnutí nastaví `published_at` na nyní; po vypnutí se vyčistí.'),
                Forms\Components\Toggle::make('featured')
                    ->label('Featured')
                    ->default(false),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Pořadí (sort)')
                    ->numeric()
                    ->default(0),
            ])
            ->columns(2);
    }

    protected static function tagsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Tagy')
            ->icon('heroicon-o-tag')
            ->schema([
                Forms\Components\Select::make('tags')
                    ->label('Tagy')
                    ->multiple()
                    ->relationship(
                        name: 'tags',
                        titleAttribute: 'slug',
                    )
                    ->preload()
                    ->searchable()
                    ->createOptionForm(PortfolioTagResource::formSchema())
                    ->createOptionUsing(function (array $data): int {
                        $translations = $data['translations'] ?? [];
                        unset($data['translations']);

                        $tag = PortfolioTag::create($data);

                        PortfolioTagResource::persistTranslations(
                            $tag,
                            ['translations' => $translations],
                        );

                        return $tag->id;
                    })
                    ->editOptionForm(PortfolioTagResource::formSchema())
                    ->fillEditOptionActionFormUsing(function (PortfolioTag $record): array {
                        $record->loadMissing('translations');

                        return PortfolioTagResource::fillTranslations($record, [
                            'slug'       => $record->slug,
                            'sort_order' => $record->sort_order,
                        ]);
                    })
                    ->updateOptionUsing(function (array $data, PortfolioTag $record): PortfolioTag {
                        $translations = $data['translations'] ?? [];
                        unset($data['translations']);

                        $record->update($data);

                        PortfolioTagResource::persistTranslations(
                            $record,
                            ['translations' => $translations],
                        );

                        return $record->fresh();
                    }),
            ]);
    }

    protected static function translationsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Překlady')
            ->icon('heroicon-o-language')
            ->schema([
                Forms\Components\Tabs::make('locales')
                    ->columnSpanFull()
                    ->tabs([
                        static::projectLocaleTab('cs', 'Česky', required: true),
                        static::projectLocaleTab('en', 'English', required: false),
                        static::projectLocaleTab('de', 'Deutsch', required: false),
                    ]),
            ]);
    }

    protected static function projectLocaleTab(string $locale, string $label, bool $required): Forms\Components\Tabs\Tab
    {
        $key = "translations.$locale";

        return Forms\Components\Tabs\Tab::make($label)
            ->badge(function (Get $get) use ($key, $required) {
                if ($required) {
                    return null;
                }

                return filled($get("$key.title")) ? null : '⚠';
            })
            ->schema([
                Forms\Components\TextInput::make("$key.title")
                    ->label('Titulek')
                    ->required($required)
                    ->maxLength(191),
                // OND-209: lokalizovaný slug. Prázdné = použije se
                // jazyk-neutrální slug ze záložky Základ (tak to má zůstat
                // u značek — PitArena, BARANA, Střechy Zajíc).
                Forms\Components\TextInput::make("$key.slug")
                    ->label('Slug (URL) pro tento jazyk')
                    ->maxLength(191)
                    ->alphaDash()
                    ->helperText('Nepovinné. Prázdné = použije se jazyk-neutrální slug. Změna slugu u publikovaného projektu = stará adresa přestane fungovat.'),
                Forms\Components\TextInput::make("$key.subtitle")
                    ->label('Podtitulek')
                    ->maxLength(191),
                Forms\Components\Textarea::make("$key.summary")
                    ->label('Souhrn (krátký, ≤500 znaků)')
                    ->rows(3)
                    ->maxLength(500),
                Forms\Components\Textarea::make("$key.description")
                    ->label('Popis')
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make("$key.challenge")
                    ->label('Výzva')
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make("$key.solution")
                    ->label('Řešení')
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make("$key.result")
                    ->label('Výsledek')
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make("$key.meta_title")
                    ->label('Meta title')
                    ->maxLength(191),
                Forms\Components\Textarea::make("$key.meta_description")
                    ->label('Meta description')
                    ->rows(2)
                    ->maxLength(500),
            ])
            ->columns(2);
    }

    protected static function outcomesTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Outcomes')
            ->icon('heroicon-o-chart-bar')
            ->schema([
                Forms\Components\Repeater::make('outcomes')
                    // Pure form-state — uložíme outcomes manuálně v EditRecord/CreateRecord hookách,
                    // protože každý outcome má vlastní translations tabulku.
                    ->statePath('outcomes')
                    ->dehydrated(true)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        return ($state['translations']['cs']['label'] ?? null)
                            ?: ($state['key'] ?? null)
                            ?: 'Outcome';
                    })
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                        Forms\Components\TextInput::make('key')
                            ->label('Interní klíč (volitelné)')
                            ->maxLength(191)
                            ->helperText('např. `conversion-rate` — jen pro orientaci.'),
                        Forms\Components\Tabs::make('outcome_translations')
                            ->columnSpanFull()
                            ->tabs([
                                static::outcomeLocaleTab('cs', 'Česky', required: true),
                                static::outcomeLocaleTab('en', 'English', required: false),
                                static::outcomeLocaleTab('de', 'Deutsch', required: false),
                            ]),
                    ]),
            ]);
    }

    protected static function outcomeLocaleTab(string $locale, string $label, bool $required): Forms\Components\Tabs\Tab
    {
        $key = "translations.$locale";

        return Forms\Components\Tabs\Tab::make($label)
            ->badge(function (Get $get) use ($key, $required) {
                if ($required) {
                    return null;
                }

                return filled($get("$key.label")) ? null : '⚠';
            })
            ->schema([
                Forms\Components\TextInput::make("$key.label")
                    ->label('Štítek')
                    ->required($required)
                    ->maxLength(191),
                Forms\Components\TextInput::make("$key.value")
                    ->label('Hodnota')
                    ->required($required)
                    ->maxLength(191)
                    ->placeholder('např. +25 %'),
                Forms\Components\TextInput::make("$key.description")
                    ->label('Popis (volitelné)')
                    ->maxLength(191)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    protected static function screenshotsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Screenshoty')
            ->icon('heroicon-o-photo')
            ->schema([
                Forms\Components\Repeater::make('screenshots')
                    ->statePath('screenshots')
                    ->dehydrated(true)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        $path = $state['path'] ?? null;
                        // FileUpload state je array (livewire temp upload) než se uloží.
                        if (is_array($path)) {
                            $first = reset($path);
                            $path = is_string($first) ? $first : '—';
                        }

                        return ($state['type'] ?? 'gallery').': '.($path ?: '—');
                    })
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                        Forms\Components\Select::make('type')
                            ->label('Typ')
                            ->options([
                                'hero'      => 'hero',
                                'gallery'   => 'gallery',
                                'thumbnail' => 'thumbnail',
                            ])
                            ->default('gallery')
                            ->required()
                            ->native(false),
                        Forms\Components\FileUpload::make('path')
                            ->label('Obrázek')
                            ->disk('public')
                            ->directory(function (Get $get) {
                                $slug = $get('../../slug') ?: '_unsaved';

                                return 'portfolio/'.$slug;
                            })
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imagePreviewHeight('120')
                            ->preserveFilenames(false)
                            ->required(),
                        Forms\Components\Tabs::make('screenshot_translations')
                            ->columnSpanFull()
                            ->tabs([
                                static::screenshotLocaleTab('cs', 'Česky'),
                                static::screenshotLocaleTab('en', 'English'),
                                static::screenshotLocaleTab('de', 'Deutsch'),
                            ]),
                    ]),
            ]);
    }

    protected static function screenshotLocaleTab(string $locale, string $label): Forms\Components\Tabs\Tab
    {
        $key = "translations.$locale";

        return Forms\Components\Tabs\Tab::make($label)
            ->schema([
                Forms\Components\TextInput::make("$key.alt")
                    ->label('Alt text')
                    ->maxLength(191),
                Forms\Components\TextInput::make("$key.caption")
                    ->label('Popisek')
                    ->maxLength(191),
            ])
            ->columns(2);
    }

    /* ==================================================================== */
    /*  Table                                                               */
    /* ==================================================================== */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Klient')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategorie')
                    ->badge()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publikováno')
                    ->state(fn (PortfolioProject $record): bool => $record->published_at !== null && $record->published_at->isPast())
                    ->boolean(),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Pořadí')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategorie')
                    ->options([
                        'website'     => 'Website',
                        'application' => 'Application',
                        'other'       => 'Other',
                    ]),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Publikováno')
                    ->placeholder('Všechny')
                    ->trueLabel('Publikované')
                    ->falseLabel('Drafty')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('published_at')->where('published_at', '<=', now()),
                        false: fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '>', now()),
                    ),
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes(['slug', 'published_at'])
                    ->beforeReplicaSaved(function (PortfolioProject $replica, PortfolioProject $original): void {
                        $replica->slug = $original->slug.'-copy-'.Str::random(4);
                        $replica->published_at = null;
                    }),
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
            'index'  => Pages\ListPortfolioProjects::route('/'),
            'create' => Pages\CreatePortfolioProject::route('/create'),
            'edit'   => Pages\EditPortfolioProject::route('/{record}/edit'),
        ];
    }

    /* ==================================================================== */
    /*  Persistence helpers — sdílené pro Create/Edit pages                 */
    /* ==================================================================== */

    /**
     * Předvyplní form data z DB pro Edit page.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function fillFormData(PortfolioProject $record, array $data): array
    {
        $record->loadMissing([
            'translations',
            'tags',
            'outcomes.translations',
            'screenshots.translations',
        ]);

        $data['is_published'] = $record->published_at !== null && $record->published_at->isPast();

        // Project translations
        $data['translations'] = [];
        foreach (['cs', 'en', 'de'] as $locale) {
            $tr = $record->translations->firstWhere('locale', $locale);
            $data['translations'][$locale] = [
                'slug'             => $tr?->slug,
                'title'            => $tr?->title,
                'subtitle'         => $tr?->subtitle,
                'summary'          => $tr?->summary,
                'description'      => $tr?->description,
                'challenge'        => $tr?->challenge,
                'solution'         => $tr?->solution,
                'result'           => $tr?->result,
                'meta_title'       => $tr?->meta_title,
                'meta_description' => $tr?->meta_description,
            ];
        }

        // Outcomes
        $data['outcomes'] = $record->outcomes
            ->sortBy('sort_order')
            ->values()
            ->map(function (PortfolioProjectOutcome $outcome): array {
                $row = [
                    'id'           => $outcome->id,
                    'key'          => $outcome->key,
                    'translations' => [],
                ];
                foreach (['cs', 'en', 'de'] as $locale) {
                    $tr = $outcome->translations->firstWhere('locale', $locale);
                    $row['translations'][$locale] = [
                        'label'       => $tr?->label,
                        'value'       => $tr?->value,
                        'description' => $tr?->description,
                    ];
                }

                return $row;
            })
            ->all();

        // Screenshots
        $data['screenshots'] = $record->screenshots
            ->sortBy('sort_order')
            ->values()
            ->map(function (PortfolioProjectScreenshot $shot): array {
                $row = [
                    'id'           => $shot->id,
                    'type'         => $shot->type,
                    'path'         => $shot->path,
                    'translations' => [],
                ];
                foreach (['cs', 'en', 'de'] as $locale) {
                    $tr = $shot->translations->firstWhere('locale', $locale);
                    $row['translations'][$locale] = [
                        'alt'     => $tr?->alt,
                        'caption' => $tr?->caption,
                    ];
                }

                return $row;
            })
            ->all();

        return $data;
    }

    /**
     * Validace + extrakce nepatřících klíčů před uložením do `portfolio_projects`.
     * Vrací tuple [data pro projekt, side effects k aplikaci v afterCreate/afterSave].
     *
     * @param  array<string, mixed>  $data
     * @return array{0: array<string, mixed>, 1: array{translations: array, outcomes: array, screenshots: array, is_published: bool}}
     */
    public static function splitFormData(array $data, ?PortfolioProject $record = null): array
    {
        $isPublished = (bool) ($data['is_published'] ?? false);

        // Validace: pokud `is_published`, požaduj alespoň 1 hero screenshot.
        if ($isPublished) {
            $hasHero = false;
            foreach ($data['screenshots'] ?? [] as $screenshot) {
                if (($screenshot['type'] ?? null) === 'hero' && filled($screenshot['path'] ?? null)) {
                    $hasHero = true;
                    break;
                }
            }
            if (! $hasHero) {
                throw new \Filament\Support\Exceptions\Halt(
                    'Pro publikaci je nutný alespoň jeden screenshot typu „hero".',
                );
            }
        }

        $sideEffects = [
            'translations' => $data['translations'] ?? [],
            'outcomes'     => $data['outcomes'] ?? [],
            'screenshots'  => $data['screenshots'] ?? [],
            'is_published' => $isPublished,
        ];

        unset($data['translations'], $data['outcomes'], $data['screenshots'], $data['is_published'], $data['tags']);

        // is_published → published_at
        $data['published_at'] = $isPublished
            ? ($record?->published_at ?? now())
            : null;

        return [$data, $sideEffects];
    }

    /**
     * Persist sideEffects (translations / outcomes / screenshots) k danému projektu.
     *
     * @param  array{translations: array, outcomes: array, screenshots: array, is_published: bool}  $sideEffects
     */
    public static function applySideEffects(PortfolioProject $record, array $sideEffects): void
    {
        static::persistProjectTranslations($record, $sideEffects['translations']);
        static::persistOutcomes($record, $sideEffects['outcomes']);
        static::persistScreenshots($record, $sideEffects['screenshots']);
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    public static function persistProjectTranslations(PortfolioProject $record, array $translations): void
    {
        foreach (['cs', 'en', 'de'] as $locale) {
            $row = $translations[$locale] ?? null;
            $title = $row['title'] ?? null;

            if (! filled($title)) {
                if ($locale !== 'cs') {
                    $record->translations()->where('locale', $locale)->delete();
                }
                continue;
            }

            $record->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    // Prázdný string → NULL, ať unique index nespadne na duplicitě ''.
                    'slug'             => filled($row['slug'] ?? null) ? $row['slug'] : null,
                    'title'            => $title,
                    'subtitle'         => $row['subtitle'] ?? null,
                    'summary'          => $row['summary'] ?? null,
                    'description'      => $row['description'] ?? null,
                    'challenge'        => $row['challenge'] ?? null,
                    'solution'         => $row['solution'] ?? null,
                    'result'           => $row['result'] ?? null,
                    'meta_title'       => $row['meta_title'] ?? null,
                    'meta_description' => $row['meta_description'] ?? null,
                ],
            );
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $outcomes
     */
    public static function persistOutcomes(PortfolioProject $record, array $outcomes): void
    {
        $kept = [];
        $sort = 0;

        foreach ($outcomes as $row) {
            $id = $row['id'] ?? null;
            $attrs = [
                'key'        => $row['key'] ?? null,
                'sort_order' => $sort++,
            ];

            if ($id && ($outcome = $record->outcomes()->find($id))) {
                $outcome->update($attrs);
            } else {
                $outcome = $record->outcomes()->create($attrs);
            }

            $translations = $row['translations'] ?? [];
            foreach (['cs', 'en', 'de'] as $locale) {
                $tr = $translations[$locale] ?? null;
                $label = $tr['label'] ?? null;
                $value = $tr['value'] ?? null;

                if (! filled($label) && ! filled($value)) {
                    $outcome->translations()->where('locale', $locale)->delete();
                    continue;
                }

                $outcome->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'label'       => $label ?? '',
                        'value'       => $value ?? '',
                        'description' => $tr['description'] ?? null,
                    ],
                );
            }

            $kept[] = $outcome->id;
        }

        $record->outcomes()->whereNotIn('id', $kept ?: [0])->delete();
    }

    /**
     * @param  array<int, array<string, mixed>>  $screenshots
     */
    public static function persistScreenshots(PortfolioProject $record, array $screenshots): void
    {
        $kept = [];
        $sort = 0;

        foreach ($screenshots as $row) {
            $id = $row['id'] ?? null;
            $attrs = [
                'type'       => $row['type'] ?? 'gallery',
                'path'       => $row['path'] ?? null,
                'sort_order' => $sort++,
            ];

            if (! filled($attrs['path'])) {
                continue;
            }

            if ($id && ($shot = $record->screenshots()->find($id))) {
                $shot->update($attrs);
            } else {
                $shot = $record->screenshots()->create($attrs);
            }

            $translations = $row['translations'] ?? [];
            foreach (['cs', 'en', 'de'] as $locale) {
                $tr = $translations[$locale] ?? null;
                $alt = $tr['alt'] ?? null;
                $caption = $tr['caption'] ?? null;

                if (! filled($alt) && ! filled($caption)) {
                    $shot->translations()->where('locale', $locale)->delete();
                    continue;
                }

                $shot->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'alt'     => $alt,
                        'caption' => $caption,
                    ],
                );
            }

            $kept[] = $shot->id;
        }

        $record->screenshots()->whereNotIn('id', $kept ?: [0])->delete();
    }
}
