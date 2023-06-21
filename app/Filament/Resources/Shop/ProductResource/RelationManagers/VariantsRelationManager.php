<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Shop\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Jobs\SyncProductVariantJob;
use App\Models\Shop\ProductVariant;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\NotifyUploadVariantCompleted;
use App\Imports\UpdateMassalProductVariant;
use App\Services\Product\SyncVariantService;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\ProductVariantTemplateExport;
use Filament\Resources\RelationManagers\RelationManager;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('barcode')
                    ->label('Barcode')
                    ->disabled(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Varian')
                    ->disabled(),
                Forms\Components\Select::make('color')
                    ->label('Warna')
                    ->searchable(true)
                    ->relationship('color', 'name'),
                Forms\Components\Select::make('size')
                    ->label('Ukuran')
                    ->searchable(true)
                    ->relationship('size', 'name'),
                Forms\Components\TextInput::make('price')
                    ->label('Harga Jual')
                    ->numeric(),
                Forms\Components\Select::make('media_id')
                    ->label('Gambar Produk')
                    ->relationship('media', 'file_name', function (Builder $query, ProductVariant $record) {
                        $query->where('collection_name', Product::GALLERY_IMAGE_COLLECTION_NAME)
                            ->where('model_type', Product::class)
                            ->where('model_id', $record->shop_product_id);
                    })
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('media.original_url')
                    ->height(100),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('color.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('size.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                Filter::make('unconfigured')
                    ->query(function (Builder $query) {
                        $query->where(function ($q) {
                            $q->whereNull('color_id')
                                ->orWhereNull('size_id');
                        });
                    })
                    ->label('Tidak Memiliki Warna / Ukuran')
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync-variants')
                    ->label('Sinkron Varian')
                    ->icon('fas-sync-alt')
                    ->button()
                    ->tooltip('Sinkron Data Produk dari data backoffice')
                    ->requiresConfirmation()
                    ->action(function (RelationManager $livewire) {
                        SyncProductVariantJob::dispatch($livewire->ownerRecord->id);

                        Notification::make()
                            ->title('Varian Sedang Di Sinkronkan')
                            ->body('Proses dilakukan di belakang layar')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('update-variants')
                    ->label('Update Massal')
                    ->icon('fas-edit')
                    ->color('success')
                    ->modalHeading('Ubah Masal Varian Produk')
                    ->form([
                        FileUpload::make('excel')
                            ->label('Upload File')
                    ])
                    ->button()
                    ->modalButton('Proses File')
                    ->action(function ($data, RelationManager $livewire) {
                        $product = $livewire->ownerRecord;
                        $file_path = storage_path('app/public/' . $data['excel']);
                        // $import = new UpdateMassalProductVariant($product);
                        // Excel::import($import, $file_path)
                        $recipient = auth()->user();

                        (new UpdateMassalProductVariant($product))->queue($file_path)->chain([
                            new NotifyUploadVariantCompleted($recipient)
                        ]);

                        Notification::make()
                            ->title('Upload Produk Selesai')
                            ->body('Proses dilakukan di belakang layar, akan ada notifikasi pada icon lonceng jika selesai')
                            ->success()
                            ->send();

                        // return $this->sendResponse('Data Produk Berhasil Diupload. Proses dilakukan di belakang layar, silahkan menunggu dan cek email notifikasi untuk melihat hasil upload', []);
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('download-excel')
                    ->label('Download file excel')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records) {
                        $fileName = 'UpdateVariant';

                        return (new ProductVariantTemplateExport($records))->download($fileName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
                            'Content-Type' => 'blob'
                        ]);
                    })
                    ->deselectRecordsAfterCompletion()
            ]);
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'name';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }
}
