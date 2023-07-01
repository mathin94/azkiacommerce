<?php

namespace App\Services\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\ProductVariant;

class AddToCartService
{
    public array $errors = [];

    public function __construct(
        private readonly Cart $cart,
        private readonly ?Product $product = null,
        private readonly ProductVariant | int $variant,
        private readonly int $quantity,
    ) {
    }

    public function execute(): bool
    {
        if (gettype($this->variant) === 'integer') {
            $this->variant = ProductVariant::find($this->variant);

            if (!$this->variant) {
                $this->errors[] = 'Variant tidak ditemukan';
                return false;
            }
        }

        if (empty($this->product)) {
            $this->product = $this->variant->product;
        }

        return $this->handle();
    }

    public function handle(): bool
    {
        $item = $this->cart->items()->firstOrNew([
            'shop_product_variant_id' => $this->variant->id,
        ]);

        $quantity = (int) $item->quantity + $this->quantity;

        if (!$this->validStock($quantity)) {
            return false;
        }

        $service = new ProductVariantPriceService(
            customer: $this->cart->customer,
            productVariant: $this->variant
        );

        $service->execute();

        $prices = $service->prices;

        $item->name           = $this->variant->name;
        $item->alternate_name = $this->variant->alternate_name;
        $item->normal_price   = $prices['normal_price'];
        $item->price          = $prices['final_price'];
        $item->weight         = $this->variant->weight;
        $item->quantity       = $quantity;
        $item->discount       = $prices['discount_percentage'];
        $item->save();

        $this->cart->recalculate();
        $this->cart->save();

        return true;
    }

    private function validStock($quantity): bool
    {
        if ($quantity <= $this->variant->resource->stock) {
            return true;
        }

        $this->errors[] = "Stok {$this->variant->name} tidak mencukupi, stok tersedia saat ini: {$this->variant->resource->stock}";

        return false;
    }
}
