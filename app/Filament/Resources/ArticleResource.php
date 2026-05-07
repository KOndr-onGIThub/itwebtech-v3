<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Revisions\ArticleRevision;
use App\Models\Slugs\ArticleSlug;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Obsah';

    protected static ?string $navigationLabel = 'Články';

    protected static ?string $modelLabel = 'článek';

    protected static ?string $pluralModelLabel = 'články';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'slug';

    /**
     * Pole překladů exponovaná v adminu (musí korespondovat se schématem
     * `article_translations` a s public render šablonou `pages.article.blade.php`).
     */
    public const TRANSLATION_FIELDS = [
        'title',
        'description',
        'perex',
        'content_1',
        'content_mid',
        'content_2',
        'bonus',
        'extra',
        'img_preview',
        'img_main',
        'img_mid',
        'img_end',
    ];

    public const LOCALES = ['cs', 'en', 'de'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('article')
                ->columnSpanFull()
                ->persistTabInQueryString()
                ->tabs([
                    static::basicsTab(),
                    static::translationsTab(),
                    static::revisionsTab(),
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
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(191)
                    ->alphaDash()
                    ->unique(ignoreRecord: true)
                    ->helperText('Master slug pro `/blog/{slug}`. Při změně se starý slug zachová pro 301 redirect.'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Datum publikace')
                    ->seconds(false)
                    ->displayFormat('d.m.Y H:i'),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publikováno')
                    ->helperText('Skryté články se nezobrazí v `/blog` ani v detailu.'),
                Forms\Components\TextInput::make('author')
                    ->label('Autor')
                    ->maxLength(191)
                    ->default(fn (): ?string => config('admin.author')),
                Forms\Components\TextInput::make('position')
                    ->label('Pořadí (sort)')
                    ->numeric()
                    ->default(0),
                Forms\Components\FileUpload::make('image_url')
                    ->label('Featured image (master)')
                    ->disk('public')
                    ->directory(fn (Get $get): string => 'articles/'.($get('slug') ?: '_unsaved'))
                    ->visibility('public')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->imagePreviewHeight('120')
                    ->preserveFilenames(false)
                    ->columnSpanFull(),
                Forms\Components\Section::make('Historie slugů')
                    ->description('Audit záznam pro 301 redirecty. Read-only.')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('slug_history_table')
                            ->label('')
                            ->content(function (?Article $record): \Illuminate\Contracts\Support\Htmlable {
                                if (! $record) {
                                    return new \Illuminate\Support\HtmlString('<em>Záznamy se zobrazí po prvním uložení.</em>');
                                }

                                $rows = $record->slugHistory()->withTrashed()->orderByDesc('created_at')->get();

                                if ($rows->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString('<em>Žádné historické záznamy.</em>');
                                }

                                $html = '<table class="text-sm" style="width:100%;border-collapse:collapse">';
                                $html .= '<thead><tr>'
                                    .'<th style="text-align:left;padding:4px 8px">Locale</th>'
                                    .'<th style="text-align:left;padding:4px 8px">Slug</th>'
                                    .'<th style="text-align:left;padding:4px 8px">Aktivní</th>'
                                    .'<th style="text-align:left;padding:4px 8px">Vytvořeno</th>'
                                    .'</tr></thead><tbody>';
                                foreach ($rows as $row) {
                                    /** @var ArticleSlug $row */
                                    $html .= '<tr>'
                                        .'<td style="padding:4px 8px">'.e($row->locale).'</td>'
                                        .'<td style="padding:4px 8px"><code>'.e($row->slug).'</code></td>'
                                        .'<td style="padding:4px 8px">'.($row->active ? '✓' : '—').'</td>'
                                        .'<td style="padding:4px 8px">'.e(optional($row->created_at)->format('d.m.Y H:i') ?? '—').'</td>'
                                        .'</tr>';
                                }
                                $html .= '</tbody></table>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ]),
            ])
            ->columns(2);
    }

    protected static function translationsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Překlady')
            ->icon('heroicon-o-language')
            ->schema([
                Forms\Components\Tabs::make('locales')
                    ->columnSpanFull()
                    ->tabs([
                        static::localeTab('cs', 'Česky', required: true),
                        static::localeTab('en', 'English', required: false),
                        static::localeTab('de', 'Deutsch', required: false),
                    ]),
            ]);
    }

    protected static function localeTab(string $locale, string $label, bool $required): Forms\Components\Tabs\Tab
    {
        $key = "translations.$locale";

        return Forms\Components\Tabs\Tab::make($label)
            ->badge(function (Get $get) use ($key, $required) {
                if ($required) {
                    return null;
                }

                // Warning ikona pro EN/DE, pokud je locale prázdný (jen kosmetické).
                $title = $get("$key.title");

                return filled($title) ? null : '⚠';
            })
            ->schema([
                Forms\Components\TextInput::make("$key.title")
                    ->label('Titulek')
                    ->required($required)
                    ->maxLength(191),
                Forms\Components\Textarea::make("$key.description")
                    ->label('Excerpt / popis (max 500 znaků)')
                    ->rows(3)
                    ->maxLength(500)
                    ->required($required)
                    ->columnSpanFull(),
                // Body fields používají Filament `RichEditor` (Trix-based WYSIWYG).
                // Pozn. SEO: Trix neuchovává všechny HTML atributy — `<a>` zachová `href`,
                // ale strippuje `target`/`rel`/`title`. Pro nový obsah, který Ondra píše,
                // je to OK. Pro legacy importované články s těmito atributy je doporučeno
                // necesarializovat editaci přes UI (raw HTML zůstává v DB nezměněn dokud
                // není článek přes admin uložen). Tahy storage round-trip beze ztrát
                // pokrývají i nadále testy `ArticleAdminResourceTest`.
                Forms\Components\RichEditor::make("$key.perex")
                    ->label('Perex')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make("$key.content_1")
                    ->label('Hlavní obsah / body')
                    ->required($required)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make("$key.img_preview")
                    ->label('Náhled (filename relativní k storage `articles/`)')
                    ->maxLength(191),
                Forms\Components\TextInput::make("$key.img_main")
                    ->label('Hlavní obrázek (filename)')
                    ->maxLength(191),
                Forms\Components\RichEditor::make("$key.content_mid")
                    ->label('Obsah – prostřední')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make("$key.img_mid")
                    ->label('Obrázek uprostřed (filename)')
                    ->maxLength(191)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make("$key.content_2")
                    ->label('Obsah – druhý díl')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make("$key.img_end")
                    ->label('Obrázek na konci (filename)')
                    ->maxLength(191)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make("$key.bonus")
                    ->label('Bonus blok')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make("$key.extra")
                    ->label('Extra blok')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    protected static function revisionsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Revize')
            ->icon('heroicon-o-clock')
            ->schema([
                Forms\Components\Placeholder::make('revisions_table')
                    ->label('')
                    ->columnSpanFull()
                    ->content(function (?Article $record): \Illuminate\Contracts\Support\Htmlable {
                        if (! $record) {
                            return new \Illuminate\Support\HtmlString('<em>Revize se začnou zaznamenávat po prvním uložení.</em>');
                        }

                        $rows = $record->revisions()->limit(10)->get();

                        if ($rows->isEmpty()) {
                            return new \Illuminate\Support\HtmlString('<em>Zatím žádné revize.</em>');
                        }

                        $html = '<table class="text-sm" style="width:100%;border-collapse:collapse">';
                        $html .= '<thead><tr>'
                            .'<th style="text-align:left;padding:4px 8px">Vytvořeno</th>'
                            .'<th style="text-align:left;padding:4px 8px">Autor</th>'
                            .'<th style="text-align:left;padding:4px 8px">Změněná pole</th>'
                            .'</tr></thead><tbody>';
                        foreach ($rows as $row) {
                            /** @var ArticleRevision $row */
                            $payload = $row->payload ?? [];
                            $changes = $payload['changed_fields'] ?? [];
                            $authorLabel = $payload['author_name'] ?? ($row->author_id ? '#'.$row->author_id : '—');
                            $html .= '<tr>'
                                .'<td style="padding:4px 8px">'.e(optional($row->created_at)->format('d.m.Y H:i') ?? '—').'</td>'
                                .'<td style="padding:4px 8px">'.e($authorLabel).'</td>'
                                .'<td style="padding:4px 8px">'.e(implode(', ', $changes) ?: '—').'</td>'
                                .'</tr>';
                        }
                        $html .= '</tbody></table>';
                        $html .= '<p class="text-xs" style="margin-top:8px;opacity:.7">Audit-only. Restore z revize není ve v1 podporován.</p>';

                        return new \Illuminate\Support\HtmlString($html);
                    }),
            ]);
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
                Tables\Columns\TextColumn::make('title_cs')
                    ->label('Titulek (cs)')
                    ->getStateUsing(fn (Article $record): ?string => $record->title_cs)
                    ->limit(60)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('translations', function (Builder $q) use ($search): void {
                            $q->where('locale', 'cs')->where('title', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publikováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('published')
                    ->label('Aktivní')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizováno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('published')
                    ->label('Publikováno')
                    ->placeholder('Všechny')
                    ->trueLabel('Publikované')
                    ->falseLabel('Drafty'),
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Publikováno od'),
                        Forms\Components\DatePicker::make('until')->label('Publikováno do'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $v) => $q->whereDate('published_at', '>=', $v))
                            ->when($data['until'] ?? null, fn (Builder $q, $v) => $q->whereDate('published_at', '<=', $v));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes(['slug', 'published_at'])
                    ->beforeReplicaSaved(function (Article $replica, Article $original): void {
                        $replica->slug = $original->slug.'-copy-'.Str::random(4);
                        $replica->published = false;
                        $replica->published_at = null;
                    })
                    ->after(function (Article $replica, Article $original): void {
                        // Replikuj překlady (slugy a revize ne, ty jsou audit a per-record).
                        foreach ($original->translations as $tr) {
                            $attrs = $tr->only([
                                'locale', 'active', 'title', 'description', 'perex',
                                'content_1', 'content_mid', 'content_2', 'bonus', 'extra',
                                'img_preview', 'img_main', 'img_mid', 'img_end',
                            ]);
                            $replica->translations()->create($attrs);
                        }

                        // Master slug → cs `article_slugs` aktivní záznam.
                        $replica->slugs()->create([
                            'slug'   => $replica->slug,
                            'locale' => 'cs',
                            'active' => true,
                        ]);
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
            'index'  => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit'   => Pages\EditArticle::route('/{record}/edit'),
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
    public static function fillFormData(Article $record, array $data): array
    {
        $record->loadMissing(['translations']);

        $data['is_published'] = (bool) $record->published;

        $data['translations'] = [];
        foreach (static::LOCALES as $locale) {
            $tr = $record->translations->firstWhere('locale', $locale);
            $row = [];
            foreach (static::TRANSLATION_FIELDS as $field) {
                $row[$field] = $tr?->{$field};
            }
            $data['translations'][$locale] = $row;
        }

        return $data;
    }

    /**
     * Validace + extrakce nepatřících klíčů před uložením do `articles`.
     * Vrací tuple [data pro článek, side effects k aplikaci v afterCreate/afterSave].
     *
     * @param  array<string, mixed>  $data
     * @return array{0: array<string, mixed>, 1: array{translations: array, original_slug: ?string, is_published: bool}}
     */
    public static function splitFormData(array $data, ?Article $record = null): array
    {
        $isPublished = (bool) ($data['is_published'] ?? false);
        $originalSlug = $record?->slug;

        // Slug normalizace POUZE pokud uživatel pole skutečně přepsal proti původnímu.
        // Tím chráníme master slug před nepředvídatelnou auto-mutací (SEO requirement).
        $slug = (string) ($data['slug'] ?? '');
        if ($record === null) {
            // Create: přijmi co uživatel zadal, případně Str::slug pro safety.
            $slug = $slug !== '' ? Str::slug($slug) : '';
        } elseif ($slug !== '' && $slug !== $originalSlug) {
            $slug = Str::slug($slug);
        } else {
            // No-op — zachovej přesně jak je v DB.
            $slug = $originalSlug ?? '';
        }
        $data['slug'] = $slug;

        // is_published → published boolean (databázový sloupec).
        $data['published'] = $isPublished;

        $sideEffects = [
            'translations'  => $data['translations'] ?? [],
            'original_slug' => $originalSlug,
            'is_published'  => $isPublished,
        ];

        unset($data['translations'], $data['is_published']);

        return [$data, $sideEffects];
    }

    /**
     * Persist sideEffects (translations / slug history / revize) k danému článku.
     *
     * @param  array{translations: array, original_slug: ?string, is_published: bool}  $sideEffects
     */
    public static function applySideEffects(Article $record, array $sideEffects): void
    {
        $changedTranslationFields = static::persistTranslations($record, $sideEffects['translations']);
        static::syncSlugHistory($record, $sideEffects['original_slug']);

        // Snapshot revize jen pokud se opravdu něco měnilo (debounce no-op save).
        if (! empty($changedTranslationFields)) {
            static::recordRevision($record, $changedTranslationFields);
        }
    }

    /**
     * Upsert article_translations + smaž EN/DE locale pokud uživatel vyprázdnil title.
     * CS locale nikdy nemažeme (povinný).
     *
     * @param  array<string, array<string, mixed>>  $translations
     * @return array<int, string> seznam změněných polí ve formátu „field.locale"
     */
    public static function persistTranslations(Article $record, array $translations): array
    {
        $changed = [];

        $existing = $record->translations()->get()->keyBy('locale');

        foreach (static::LOCALES as $locale) {
            $row = $translations[$locale] ?? [];
            $title = $row['title'] ?? null;

            if (! filled($title) && $locale !== 'cs') {
                if ($existing->has($locale)) {
                    $existing[$locale]->delete();
                    $changed[] = "title.$locale";
                }
                continue;
            }

            $attrs = ['active' => true];
            foreach (static::TRANSLATION_FIELDS as $field) {
                $attrs[$field] = $row[$field] ?? null;
            }

            $current = $existing->get($locale);
            if ($current) {
                // Detekce změn pro revize (porovnáváme jen klíčová textová pole).
                foreach (['title', 'description', 'perex', 'content_1', 'content_mid', 'content_2', 'bonus', 'extra'] as $tracked) {
                    if (($current->{$tracked} ?? null) !== ($attrs[$tracked] ?? null)) {
                        $changed[] = "$tracked.$locale";
                    }
                }
                $current->update($attrs);
            } else {
                $record->translations()->create(array_merge($attrs, ['locale' => $locale]));
                $changed[] = "title.$locale";
            }
        }

        return $changed;
    }

    /**
     * Master slug → `article_slugs` synchronizace.
     *
     * - Aktuální master slug zajistí jediný `active=true` cs záznam.
     * - Pokud se slug změnil, starý slug se zachová jako neaktivní (audit + 301 lookup).
     */
    public static function syncSlugHistory(Article $record, ?string $originalSlug): void
    {
        $newSlug = $record->slug;
        if (! filled($newSlug)) {
            return;
        }

        if (filled($originalSlug) && $originalSlug !== $newSlug) {
            // Deaktivuj starý cs záznam (zůstává v tabulce pro 301 redirect lookup).
            $record->slugs()
                ->where('locale', 'cs')
                ->where('slug', $originalSlug)
                ->update(['active' => false]);
        }

        // Zajisti, že přesně jeden cs slug je aktivní = newSlug.
        $record->slugs()
            ->where('locale', 'cs')
            ->where('slug', '!=', $newSlug)
            ->update(['active' => false]);

        $record->slugs()->updateOrCreate(
            ['locale' => 'cs', 'slug' => $newSlug],
            ['active' => true],
        );
    }

    /**
     * Snapshot do `article_revisions`. Audit-only (žádný restore ve v1).
     *
     * @param  array<int, string>  $changedFields
     */
    public static function recordRevision(Article $record, array $changedFields): void
    {
        $authorId = auth()->id();
        $authorName = auth()->user()?->name ?? auth()->user()?->email;

        $snapshot = [];
        $record->loadMissing('translations');
        foreach ($record->translations as $tr) {
            $snapshot[$tr->locale] = $tr->only([
                'title', 'description', 'perex',
                'content_1', 'content_mid', 'content_2', 'bonus', 'extra',
            ]);
        }

        $record->revisions()->create([
            'author_id' => $authorId,
            'payload'   => [
                'author_name'    => $authorName,
                'changed_fields' => array_values(array_unique($changedFields)),
                'snapshot'       => $snapshot,
                'master'         => [
                    'slug'         => $record->slug,
                    'published'    => (bool) $record->published,
                    'published_at' => optional($record->published_at)->toIso8601String(),
                    'image_url'    => $record->image_url,
                    'author'       => $record->author,
                ],
            ],
        ]);
    }
}
