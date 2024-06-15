<?php

namespace App\Services\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\CartItem;
use App\Models\Shop\Customer;
use App\Models\Shop\ProductVariant;
use Illuminate\Database\Eloquent\Collection;

class CheckLimitationService
{
    protected Product $product;
    protected Collection $limitations;
    public int $limit;

    public function __construct(
        protected Customer $customer,
        protected Cart $cart,
        protected ?ProductVariant $variant = null,
        protected ?CartItem $cartItem = null,
        protected ?int $quantity = null,
    ) {
        $this->limitations = $this->customer->limitations;
        $this->product = $this->variant?->product;
    }

    public function execute(): bool
    {
        if (is_null($this->variant) && is_null($this->cartItem) && is_null($this->quantity))
            return false;

        if (empty($this->limitations))
            return true;

        $product_id = $this->variant?->shop_product_id ?? $this->cartItem?->productVariant?->shop_product_id;

        $limitation = $this->limitations
            ->where('shop_product_id', $product_id)
            ->first();

        if (empty($limitation))
            return true;

        $cartItem = $this->cartItem;

        if (is_null($cartItem)) {
            $cartItem = $this->cart->items
                ->where('shop_product_variant_id', $this->variant?->id)
                ->first();
        }

        $qty = $cartItem?->quantity ?? 0;
        $qty += $this->quantity ?? 0;

        $this->limit = $limitation->quantity_limit;

        if ($qty > $this->limit)
            return false;

        return true;
    }
}
