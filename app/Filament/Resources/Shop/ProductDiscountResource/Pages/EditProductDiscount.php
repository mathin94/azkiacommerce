<?php

namespace App\Filament\Resources\Shop\ProductDiscountResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\RecalculateCartDiscountJob;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Shop\ProductDiscountResource;

class EditProductDiscount extends EditRecord
{
    protected static string $resource = ProductDiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function (Model $record) {
                    RecalculateCartDiscountJob::dispatch($record->id);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $variants = $this->data['discountVariants'];

        $this->record->variants()->sync($variants);

        RecalculateCartDiscountJob::dispatch($this->data['id']);
    }
}
