<?php

namespace App\Filament\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Admin\CancelOrderService;
use App\Services\Admin\SendPackageService;
use App\Services\Admin\ConfirmOrderService;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Pesanan';

    protected static ?string $modelLabel = 'Kelola Pesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        $text = "<span>{$record->created_at->format('d F Y H:i')}</span> <br>";

                        if ($record->status == OrderStatus::WaitingConfirmation()) {
                            $text .= "» Lihat Bukti Transfer";
                        }

                        return $text;
                    })
                    ->url(function (Model $record) {
                        if ($record->status == OrderStatus::WaitingConfirmation()) {
                            return $record->proof_of_payment_url;
                        }

                        return null;
                    }, true)
                    ->html(),
                Tables\Columns\TextColumn::make('number')
                    ->label('No. Order')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return "{$record->number} <br>
                        <small class=\"font-weight-bold\">No. Invoice: {$record->invoice_number}</small>";
                    })
                    ->html(),
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
                            if (!in_array($data['value'], ['waitingPayment', 'waitingDelivery', 'delivered', 'completed', 'canceled'])) {
                                $builder = $query->waitingPayment();
                            } else {
                                $builder = $query->{$data['value']}();
                            }

                            return $builder;
                        })
                ],
            )
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail')
                        ->modalHeading('Detail Pesanan')
                        ->modalContent(function (Order $record) {
                            return view('filament.orders.show', ['order' => $record]);
                        }),

                    Tables\Actions\Action::make('cancel')
                        ->action(function (Order $record) {
                            $service = new CancelOrderService($record, auth()->user());

                            if (!$service->execute()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Gagal!')
                                    ->body('Terjadi kesalahan, harap refresh browser anda atau hubungi admin.')
                                    ->persistent()
                                    ->send();

                                dd($service->errors);

                                return;
                            }

                            Notification::make()
                                ->success()
                                ->title('Sukses!')
                                ->body('Order berhasil dibatalkan.')
                                ->persistent()
                                ->send();
                        })
                        ->label('Batalkan Pesanan')
                        ->icon('eos-cancel')
                        ->color('danger')
                        ->modalHeading(function (Order $record) {
                            return "Batalkan Order " . $record->number;
                        })
                        ->requiresConfirmation()
                        ->hidden(function (Order $record) {
                            return !$record->admin_cancelable;
                        }),
                    Tables\Actions\Action::make('confirm')
                        ->action(function (Order $record) {
                            $service = new ConfirmOrderService($record, auth()->user());

                            if (!$service->execute()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Gagal Konfirmasi!')
                                    ->body('Terjadi kesalahan, harap refresh browser anda atau hubungi admin.')
                                    ->persistent()
                                    ->send();

                                return;
                            }

                            Notification::make()
                                ->success()
                                ->title('Sukses!')
                                ->body('Order berhasil di konfirmasi. segera lakukan pengiriman.')
                                ->persistent()
                                ->send();
                        })
                        ->label('Konfirmasi Pesanan')
                        ->icon('eos-check')
                        ->color('success')
                        ->modalHeading(function (Order $record) {
                            return "Konfirmasi Pesanan " . $record->number;
                        })
                        ->requiresConfirmation()
                        ->hidden(function (Order $record) {
                            return $record->status != OrderStatus::WaitingConfirmation();
                        }),
                    Tables\Actions\Action::make('send-package')
                        ->action(function (Order $record, array $data) {
                            $receipt_number = $data['receipt_number'];

                            $service = new SendPackageService($record, auth()->user(), $receipt_number);

                            if (!$service->execute()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Gagal!')
                                    ->body('Terjadi kesalahan, harap refresh browser anda atau hubungi admin.')
                                    ->persistent()
                                    ->send();

                                return;
                            }

                            Notification::make()
                                ->success()
                                ->title('Sukses!')
                                ->body('Pesanan berhasil di update.')
                                ->persistent()
                                ->send();
                        })
                        ->modalWidth('sm')
                        ->label('Kirim Pesanan')
                        ->icon('eos-local-shipping-o')
                        ->color('success')
                        ->modalHeading('Kirim Pesanan')
                        ->modalSubheading('Silahkan masukan nomor resi sesuai pesanan')
                        ->hidden(function (Order $record) {
                            return $record->status != OrderStatus::Paid();
                        })
                        ->form([
                            Forms\Components\TextInput::make('order_number')
                                ->default(fn (Order $record) => $record->number)
                                ->label('Nomor Order')
                                ->disabled(),
                            Forms\Components\TextInput::make('courier')
                                ->default(fn (Order $record) => $record->courier_label)
                                ->label('Kurir Pengiriman')
                                ->disabled(),
                            Forms\Components\TextInput::make('receipt_number')
                                ->default(fn (Order $record) => $record->shipping->receipt_number)
                                ->label('Nomor Resi')
                                ->placeholder('Masukkan nomor resi')
                                ->required(),
                        ]),
                    Tables\Actions\ViewAction::make('tracking')
                        ->label('Lacak Paket')
                        ->modalHeading('Lacak Paket')
                        ->color('success')
                        ->icon('carbon-map')
                        ->modalContent(function (Order $record) {
                            return view('filament.orders.show', ['order' => $record]);
                        })
                        ->hidden(function (Order $record) {
                            return !in_array($record->status, [
                                OrderStatus::PackageSent(),
                                OrderStatus::Completed(),
                            ]);
                        })
                ]),
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
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::with(['customer', 'payment', 'shipping.courier'])->orderBy('status', 'desc');
    }
}
