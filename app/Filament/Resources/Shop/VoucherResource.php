<?php

namespace App\Filament\Resources\Shop;

use App\Enums\ValueType;
use App\Enums\VoucherType;
use App\Filament\Resources\Shop\VoucherResource\Pages;
use App\Filament\Resources\Shop\VoucherResource\RelationManagers;
use App\Models\Shop\Voucher;
use Closure;
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
                    ->rules('required')
                    ->label('Nama Voucher'),

                Forms\Components\Textarea::make('description')
                    ->rules('required|min:10')
                    ->label('Deskripsi'),

                Forms\Components\TextInput::make('code')
                    ->rules('required|min:4')
                    ->label('Kode Voucher'),

                Forms\Components\Select::make('voucher_type')
                    ->label('Jenis Voucher')
                    ->options(VoucherType::asSelectArray()),

                Forms\Components\TextInput::make('minimum_order')
                    ->label('Minimal Pemesanan')
                    ->numeric(),

                Forms\Components\TextInput::make('maximum_discount')
                    ->label('Maksimal Diskon (Dalam Rupiah)')
                    ->numeric()
                    ->helperText('Kosongkan Untuk Diskon Tanpa Maksimal'),

                Forms\Components\Select::make('value_type')
                    ->label('Jenis Diskon')
                    ->options(ValueType::asSelectArray()),

                Forms\Components\TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->numeric()
                    ->rules([
                        'required',
                        'min:1'
                    ])
                    ->helperText('Isi Sesuai Jenis Diskon (Rp atau %)'),

                Forms\Components\TextInput::make('quota')
                    ->label('Kuota Pemakaian')
                    ->numeric()
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('active_at')
                    ->label('Tanggal Aktif')
                    ->required(),

                Forms\Components\DateTimePicker::make('inactive_at')
                    ->label('Tanggal Selesai')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->label('Nama Diskon'),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Voucher'),

                Tables\Columns\TextColumn::make('value')
                    ->getStateUsing(function (Voucher $record) {
                        if ($record->is_percentage) {
                            return (int) $record->value . '%';
                        }

                        return 'Rp. ' . number_format($record->value, 0, ',', '.');
                    })
                    ->label('Nilai Diskon'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Masa Berlaku')
                    ->getStateUsing(function (Voucher $record) {
                        $status = $record->active_at?->isPast() ? 'Aktif' : 'Tidak Aktif';

                        return "
                            <span class=\"font-bold\">Status : </span> {$status}<br>
                            <span class=\"font-bold\">Tgl Mulai : </span> {$record->active_at->format('d M, Y H:i')}<br>
                            <span class=\"font-bold\">Tgl Selesai : </span> {$record->inactive_at->format('d M, Y H:i')}
                        ";
                    })
                    ->html()
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
