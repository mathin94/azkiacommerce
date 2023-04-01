<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Ramsey\Uuid\Uuid;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Imports\UpdateMassalProductVariant;
use App\Services\Product\SyncVariantService;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\ProductVariantTemplateExport;
use App\Jobs\NotifyUploadVariantCompleted;
use App\Models\Backoffice\Product as PosProduct;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'name';

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
                        $product = $livewire->ownerRecord;

                        $service = new SyncVariantService($product);

                        $service->perform();
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
                // Tables\Actions\EditAction::make(),
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
}
