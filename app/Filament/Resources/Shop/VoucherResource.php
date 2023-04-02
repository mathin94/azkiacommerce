<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\VoucherResource\Pages;
use App\Filament\Resources\Shop\VoucherResource\RelationManagers;
use App\Models\Shop\Voucher;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Diskon & Voucher';

    protected static ?string $slug = 'promotions/vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('value_type')
                    ->required(),
                Forms\Components\TextInput::make('voucher_type')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('minimum_order')
                    ->required(),
                Forms\Components\TextInput::make('maximum_discount'),
                Forms\Components\TextInput::make('quota')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required(),
                Forms\Components\DateTimePicker::make('active_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('inactive_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('value_type'),
                Tables\Columns\TextColumn::make('voucher_type'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('minimum_order'),
                Tables\Columns\TextColumn::make('maximum_discount'),
                Tables\Columns\TextColumn::make('quota'),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('active_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('inactive_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVouchers::route('/'),
        ];
    }
}
