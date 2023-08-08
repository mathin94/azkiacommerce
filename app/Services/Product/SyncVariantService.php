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
        foreach ($this->product->resource->products as $variant) {
            if ($variant->price == null) {
                continue;
            }

            $productVariant = $this->product->variants()->firstOrNew(['resource_id' => $variant->id]);
            $productVariant->barcode   = $variant->barcode;
            $productVariant->code_name = $variant->code_name;
            $productVariant->name      = $variant->name;
            $productVariant->weight    = $variant->weight;
            $productVariant->price     = $variant->price;
            $productVariant->save();
        }
    }
}
