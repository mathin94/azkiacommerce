<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Shop\Product;
use App\Models\Shop\ProductVariant;

class ProductVariantTemplate implements FromCollection
{
    public function __construct(
        protected Product $product
    ) {
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $related_product_ids = Product::where('category_id', $this->product->category_id)->pluck('id');

        $existing_variant_ids = ProductVariant::whereIn('shop_product_id', $related_product_ids)->pluck('resource_id');

        $variants = $this->product->resourceVariants()->whereNot(fn ($q) => $q->whereIn('id', $existing_variant_ids));

        return $variants;
    }
}
