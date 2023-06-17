<?php

namespace App\Services\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\ProductDiscount;

class RecalculateCartDiscountService
{
    public function __construct(
        private ProductDiscount $discount
    ) {
    }

    public function execute()
    {
        $this->discount->load('discountVariants');

        $product_variant_ids = $this->discount->discountVariants->pluck('shop_product_variant_id');

        $carts = Cart::with('items')->active()
            ->whereHas('items', function ($q) use ($product_variant_ids) {
                $q->whereIn('shop_cart_items.shop_product_variant_id', $product_variant_ids);
            });

        $carts?->each(function ($cart) {
            $cart->items->each(function ($cartItem) {
                $cartItem->price = $this->calculatePrice($cartItem);
                $cartItem->total_price = $cartItem->price * $cartItem->quantity;
                $cartItem->discount = $this->discount->discount_percentage;
                $cartItem->save();
            });

            $cart->recalculate();
        });
    }

    private function calculatePrice($cartItem)
    {
        $discountable = $this->discount
            ->discountVariants
            ->where('shop_product_variant_id', $cartItem->productVariant->id)
            ->isNotEmpty();

        if (!$this->discount->is_active || !$discountable) {
            return $cartItem->normal_price;
        }

        if (!blank($this->discount->max_quantity) && $cartItem->quantity > $this->discount->max_quantity) {
            return $cartItem->normal_price;
        }

        return $cartItem->normal_price - ($cartItem->normal_price * ($this->discount->discount_percentage / 100));
    }
}
