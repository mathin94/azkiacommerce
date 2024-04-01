<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use App\Models\Backoffice\CustomerType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LimitationsRelationManager extends RelationManager
{
    protected static string $relationship = 'limitations';

    protected static ?string $recordTitleAttribute = 'quantity_limit';

    protected static ?string $title = 'Pembatasan Order';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_type_id')
                    ->label('Jenis Kemitraan')
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->getOptionLabelUsing(function ($state) {
                        $customerType = CustomerType::find($state);

                        return $customerType->name;
                    })
                    ->getSearchResultsUsing(function ($query) {
                        $customerType = new CustomerType();

                        return $customerType->where('name', 'like', "%{$query}%")->limit(10)->pluck('name', 'id');
                    })
                    ->required(),

                Forms\Components\TextInput::make('quantity_limit')
                    ->label('Batas Qty Pesanan Dalam Sekali Order')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customerType.name')
                    ->label('Jenis Kemitraan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('quantity_limit')
                    ->label('Batas Qty Pesanan Dalam Sekali Order'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
