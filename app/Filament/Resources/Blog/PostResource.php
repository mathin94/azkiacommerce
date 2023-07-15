<?php

namespace App\Filament\Resources\Blog;

use Filament\Forms;
use Filament\Tables;
use App\Enums\PostStatus;
use App\Models\Blog\Post;
use Filament\Resources\Form;
use App\Models\Blog\Category;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use RalphJSmit\Filament\SEO\SEO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Blog\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\Blog\PostResource\RelationManagers;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Artikel')
                                    ->required()
                                    ->lazy()
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        if ($context === 'create') {
                                            $set('slug', \Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->required()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                TinyEditor::make('content')
                                    ->label('Konten')
                                    ->required()
                                    ->columnSpan('full'),

                                Forms\Components\Select::make('blog_post_category_id')
                                    ->relationship('category', 'name', function ($query) {
                                        $query->where('active', true);
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal diterbitkan'),

                                Forms\Components\SpatieTagsInput::make('tags'),

                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Image')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection(Post::MEDIA_COLLECTION)
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('300')
                                    ->enableDownload()
                                    ->enableOpen()
                                    ->disableLabel(),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                SEO::make()
                            ])
                    ])
                    ->columnSpan(['lg' => fn (?Post $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat Pada')
                            ->content(fn (Post $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Terakhir di ubah pada')
                            ->content(fn (Post $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Post $record) => $record === null),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('blog-image')
                    ->label('Gambar')
                    ->square()
                    ->size(80)
                    ->collection(Post::MEDIA_COLLECTION),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->getStateUsing(fn (Post $record) => $record->public_url)
                    ->url(fn (Post $record) => $record->public_url)
                    ->icon('heroicon-o-link')
                    ->limit(50)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Post $record): string => $record->published_at?->isPast() ? 'Diterbitkan' : 'Draft')
                    ->colors([
                        'success' => 'Diterbitkan',
                    ]),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('published_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Diterbitkan Dari ' . Carbon::parse($data['published_from'])->toFormattedDateString();
                        }
                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Diterbitkan Sampai ' . Carbon::parse($data['published_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['author', 'category']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name', 'category.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Post $record */
        $details = [];

        if ($record->author) {
            $details['Author'] = $record->author->name;
        }

        if ($record->category) {
            $details['Category'] = $record->category->name;
        }

        return $details;
    }
}
