<?php

namespace App\Filament\Resources\Shop\ProductDiscountResource\Pages;

use Filament\Pages\Actions;
use App\Models\Shop\ProductDiscount;
use App\Jobs\RecalculateCartDiscountJob;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Shop\ProductDiscountResource;

class CreateProductDiscount extends CreateRecord
{
    protected static string $resource = ProductDiscountResource::class;

    protected function beforeCreate(): void
    {
        $this->validateActiveDiscount();
    }

    protected function afterCreate(): void
    {
        $variants = $this->data['discountVariants'];

        $this->record->variants()->sync($variants);

        RecalculateCartDiscountJob::dispatch($this->record->id);
    }

    private function validateActiveDiscount(): void
    {
        $product_id = $this->data['shop_product_id'];

        $exists = ProductDiscount::active()->whereShopProductId($product_id)->count();

        if ($exists) {
            Notification::make()
                ->warning()
                ->title('Gagal Menyimpan Diskon!')
                ->body('Masih ada diskon aktif untuk produk ini. silahkan ubah produk.')
                ->persistent()
                ->send();

            $this->halt();
        }
    }
}
