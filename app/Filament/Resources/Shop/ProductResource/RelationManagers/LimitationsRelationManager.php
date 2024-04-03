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
    protected static string $relationship = 'notExpiredLimitations';

    protected static ?string $recordTitleAttribute = 'quantity_limit';

    protected static ?string $title = 'Pembatasan Order';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_type_id')
                    ->label('Jenis Kemitraan')
                    ->relationship('customerType', 'name')
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('quantity_limit')
                    ->label('Batas Qty Pesanan Dalam Sekali Order')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Forms\Components\DateTimePicker::make('active_at')
                    ->label('Tgl Mulai')
                    ->required(),

                Forms\Components\DateTimePicker::make('inactive_at')
                    ->label('Tgl berakhir')
                    ->after('active_at')
                    ->after(now())
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
                Tables\Columns\TextColumn::make('active_at')
                    ->date("d F Y H:i")
                    ->label('Aktif Sejak'),
                    Tables\Columns\TextColumn::make('inactive_at')
                    ->date("d F Y H:i")
                    ->label('Sampai'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modelLabel('Pembatasan Order Baru'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->recordTitle('Data'),
                Tables\Actions\DeleteAction::make()
                    ->recordTitle('Data'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
