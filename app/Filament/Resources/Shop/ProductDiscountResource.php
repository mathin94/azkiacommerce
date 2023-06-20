<?php

namespace App\Filament\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use Livewire\Livewire;
use App\Enums\DiscountType;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Shop\ProductVariant;
use Filament\Forms\Components\Card;
use App\Models\Shop\ProductDiscount;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Shop\ProductDiscountResource\Pages;
use App\Filament\Resources\Shop\ProductDiscountResource\RelationManagers;
use App\Jobs\RecalculateCartDiscountJob;
use Illuminate\Database\Eloquent\Model;
use LucasGiovanny\FilamentMultiselectTwoSides\Forms\Components\Fields\MultiselectTwoSides;

class ProductDiscountResource extends Resource
{
    protected static ?string $model = ProductDiscount::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Diskon & Voucher';

    protected static ?string $modelLabel = 'Diskon / Flash Sale';

    protected static ?string $slug = 'promotions/discounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Detail Diskon')
                            ->schema([
                                Forms\Components\Select::make('shop_product_id')
                                    ->relationship('product', 'name')
                                    ->preload()
                                    ->reactive()
                                    ->searchable()
                                    ->label('Produk')
                                    ->required(),
                                Forms\Components\Select::make('discount_type')
                                    ->options(DiscountType::asSelectArray())
                                    ->label('Jenis Diskon')
                                    ->required(),
                                Forms\Components\TextInput::make('discount_percentage')
                                    ->label('Besaran Diskon (Dalam %)')
                                    ->numeric()
                                    ->maxValue(100)
                                    ->required(),
                                Forms\Components\TextInput::make('maximum_qty')
                                    ->label('Jumlah Maksimum Per Customer')
                                    ->helperText('Jika Diisi, Produk hanya dapat di beli dengan harga diskon berdasarkan maksimum qty'),
                                Forms\Components\Toggle::make('with_membership_price')
                                    ->label('Berlaku Harga Kemitraan')
                                    ->columnSpanFull()
                                    ->required(),
                                MultiselectTwoSides::make('discountVariants')
                                    ->options(function (callable $get) {
                                        $product_id = $get('shop_product_id');

                                        return ProductVariant::where('shop_product_id', $product_id)->pluck('name', 'id');
                                    })
                                    ->selectableLabel('Varian Tersedia')
                                    ->selectedLabel('Variant Dipilih')
                                    ->columnSpanFull()
                                    ->afterStateHydrated(function (?ProductDiscount $record, MultiselectTwoSides $component) {
                                        $variants = collect($record?->discountVariants);
                                        $component->state($variants->pluck('shop_product_variant_id')->toArray());
                                    })
                                    ->required()
                                    ->enableSearch(),
                                Forms\Components\DateTimePicker::make('active_at')
                                    ->default(now()),
                                Forms\Components\DateTimePicker::make('inactive_at')
                                    ->default(now()->addDays(1)),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),
            ])->columns([
                'sm' => 2,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Jenis Diskon')
                    ->getStateUsing(fn (Model $record) => DiscountType::fromValue($record->discount_type)->description),
                Tables\Columns\IconColumn::make('with_membership_price')
                    ->label('Berlaku Harga Mitra')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->getStateUsing(fn (Model $record) => "{$record->discount_percentage}%")
                    ->label('Diskon'),
                Tables\Columns\TextColumn::make('description')
                    ->getStateUsing(function (Model $record) {
                        $status = $record->is_active ? 'Aktif' : 'Tidak Aktif';

                        return "
                            <span class=\"font-bold\">Status : </span> {$status}<br>
                            <span class=\"font-bold\">Tgl Mulai : </span> {$record->active_at->format('d M, Y H:i')}<br>
                            <span class=\"font-bold\">Tgl Selesai : </span> {$record->inactive_at->format('d M, Y H:i')}
                        ";
                    })->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Model $record) {
                        RecalculateCartDiscountJob::dispatch($record->id);
                    })
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
            'index' => Pages\ListProductDiscounts::route('/'),
            'create' => Pages\CreateProductDiscount::route('/create'),
            'edit' => Pages\EditProductDiscount::route('/{record}/edit'),
        ];
    }
}
