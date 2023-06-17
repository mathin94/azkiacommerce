<?php

namespace App\Filament\Resources\Shop\ProductDiscountResource\Pages;

use App\Filament\Resources\Shop\ProductDiscountResource;
use App\Jobs\RecalculateCartDiscountJob;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductDiscount extends EditRecord
{
    protected static string $resource = ProductDiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $variants = $this->data['discountVariants'];

        $this->record->variants()->sync($variants);

        RecalculateCartDiscountJob::dispatch($this->data['id']);
    }
}
