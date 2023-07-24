<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Page;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Spatie\Menu\Laravel\Link;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PageResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $modelLabel = 'Halaman Statis';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Halaman')
                                    ->required()
                                    ->lazy()
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        $set('slug', \Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(callback: function (Unique $rule) {
                                        return $rule->where('deleted_at', null);
                                    }, ignoreRecord: true),

                                TinyEditor::make('content')
                                    ->label('Konten')
                                    ->minHeight(500)
                                    ->maxHeight(700)
                                    ->columnSpan('full'),
                            ])
                            ->columns(2),

                        Group::make()
                            ->schema([
                                Section::make('Media')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('media')
                                            ->directory('pages')
                                            ->maxSize(2048)
                                            ->multiple(true)
                                            ->maxFiles(5)
                                            ->enableOpen()
                                            ->enableReordering()
                                            ->disableLabel()
                                            ->helperText('Semua Gambar akan di tempatkan di bagian atas halaman sesuai urutan')
                                    ]),
                            ])
                    ])
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns([
                'sm' => 2,
                'lg' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('page-thumbnail')
                    ->label('Thumbnail')
                    ->square()
                    ->size(200),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->getStateUsing(fn (Page $record) => $record->public_url)
                    ->url(fn (Page $record) => $record->public_url)
                    ->icon('heroicon-o-link')
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
