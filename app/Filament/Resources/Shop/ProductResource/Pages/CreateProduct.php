<?php

namespace App\Filament\Resources\Shop\ProductResource\Pages;

use Filament\Pages\Actions;
use App\Jobs\SyncProductVariantJob;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Shop\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament::resources.pages.create-record-custom';

    protected function afterCreate(): void
    {
        $id = $this->record->id;

        SyncProductVariantJob::dispatch($id);

        Notification::make()
            ->title('Varian Sedang Di Sinkronkan')
            ->body('Proses dilakukan di belakang layar')
            ->success()
            ->send();
    }
}
