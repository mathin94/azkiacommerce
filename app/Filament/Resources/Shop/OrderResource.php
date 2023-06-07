<?php

namespace App\Filament\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use App\Models\Shop\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->sortable()
                    ->dateTime('d F Y H:i'),
                Tables\Columns\TextColumn::make('number')
                    ->label('No. Order')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return "{$record->number} <br>
                        <small class=\"font-weight-bold\">No. Invoice: {$record->invoice_number}</small>";
                    })->html(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return "{$record->customer->name} <br>
                        <small class=\"font-weight-bold\">Kemitraan: {$record->customer->customer_type_name}</small>";
                    })->html(),
                Tables\Columns\TextColumn::make('grandtotal')
                    ->label('Total Transaksi')
                    ->getStateUsing(fn (Model $record) => $record->grandtotal_label),
                Tables\Columns\TextColumn::make('courier')
                    ->label('Kurir')
                    ->getStateUsing(fn (Model $record) => $record->courier_label),
            ])
            ->filters(
                [
                    Filter::make('created_at')
                        ->form([
                            Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                            Forms\Components\DatePicker::make('created_until')->label('Sampai Dengan'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['created_from'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                )
                                ->when(
                                    $data['created_until'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)->default(now()),
                                );
                        }),
                    SelectFilter::make('status')
                        ->options([
                            'waitingPayment' => 'Menunggu Pembayaran',
                            'waitingDelivery' => 'Perlu Dikirim',
                            'delivered' => 'Dikirim',
                            'completed' => 'Selesai',
                            'canceled' => 'Dibatalkan'
                        ])
                        ->default('waitingPayment')
                        ->query(function (Builder $query, array $data) {
                            $status = $data['value'];

                            if (!in_array($data['value'], ['waitingPayment', 'waitingDelivery', 'delivered', 'completed', 'canceled'])) {
                                $status = 'waitingPayment';
                                $builder = $query->waitingPayment();
                            } else {
                                $builder = $query->{$data['value']}();
                            }

                            return $builder;
                        })
                ],
            )
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
