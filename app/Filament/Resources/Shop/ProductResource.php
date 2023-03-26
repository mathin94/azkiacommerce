<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ProductResource\Pages;
use App\Filament\Resources\Shop\ProductResource\RelationManagers;
use App\Filament\Resources\Shop\ProductResource\RelationManagers\VariantsRelationManager;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\SEO\SEO;
use App\Services\Backoffice\CategoryService;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use CoringaWc\FilamentInputLoading\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use App\Models\Backoffice\Product as PosProduct;
use Illuminate\Support\Facades\Validator;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'carbon-product';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Produk')
                            ->schema([
                                Forms\Components\Select::make('shop_product_category_id')
                                    ->relationship('category', 'name')
                                    ->label('Kategori')
                                    ->required(),

                                Forms\Components\Select::make('resource_id')
                                    ->label('Kategori di Backoffice')
                                    ->searchable()
                                    ->reactive()
                                    ->lazy()
                                    ->getOptionLabelUsing(function ($state) {
                                        $categoryService = new CategoryService();
                                        $category = $categoryService->getById($state);

                                        return $category['name'];
                                    })
                                    ->getSearchResultsUsing(function ($query) {
                                        $categoryService = new CategoryService();

                                        $data = collect($categoryService->all($query, 50))->pluck('name', 'id');

                                        return $data;
                                    })
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        if ($context === 'create') {
                                            $categoryService = new CategoryService();
                                            $category = $categoryService->getById($state);

                                            $set('name', $category['name']);
                                            $set('slug', \Str::slug($category['name']));
                                        }
                                    })
                                    ->required(),

                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->debounce()
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        if ($context === 'create') {
                                            $set('slug', \Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->required()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                TinyEditor::make('description')
                                    ->label(__('Deskripsi Produk'))
                                    ->required()
                                    ->columnSpan('full'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now())
                                    ->label('Tanggal diterbitkan'),

                                Forms\Components\Toggle::make('visible')
                                    ->label('Visible')
                                    ->columnSpan('full')
                                    ->helperText('Produk akan di tampilkan ke customer.')
                                    ->default(true),

                                Forms\Components\Toggle::make('featured')
                                    ->label('Produk Unggulan')
                                    ->columnSpan('full')
                                    ->helperText('Tampilkan sebagai produk unggulan.')
                                    ->default(false),

                                Forms\Components\Toggle::make('allow_preorder')
                                    ->label('Pre-Order')
                                    ->columnSpan('full')
                                    ->helperText('Produk dapat di pesan dengan metode pre-order')
                                    ->default(false),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Foto Produk')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('product-images')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('300')
                                    ->enableReordering()
                                    ->enableDownload()
                                    ->enableOpen()
                                    ->disableLabel(),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                SEO::make()
                            ])
                            ->collapsible(true)
                            ->collapsed(fn (?Product $record) => $record === null ? false : true)
                    ])
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns([
                'sm' => 2,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                    ->label('Foto Produk')
                    ->square()
                    ->size(80)
                    ->collection('product-images'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('slug')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\IconColumn::make('visible')
                    ->boolean()
                    ->label('Tampilkan'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
