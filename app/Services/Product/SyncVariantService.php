<?php

namespace App\Services\Product;

use App\Models\Shop\Product;

class SyncVariantService
{
    public function __construct(
        private Product $product,
    ) {
    }

    public function perform()
    {
        try {
            $this->handle();

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function handle()
    {
        foreach ($this->product->resourceVariants()->get() as $variant) {
            $this->product->variants()->updateOrCreate(
                ['resource_id' => $variant->id],
                [
                    'barcode'     => $variant->barcode,
                    'code_name'   => $variant->code_name,
                    'name'        => $variant->name,
                    'weight'      => $variant->weight,
                    'price'       => $variant->price(),
                ]
            );
        }
    }
}
