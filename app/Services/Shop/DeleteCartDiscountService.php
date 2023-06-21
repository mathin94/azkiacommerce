<?php

namespace App\Services\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\ProductDiscount;

class DeleteCartDiscountService
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

        $carts?->each(function ($cart) use ($product_variant_ids) {
            $cart->items
                ->whereIn('shop_product_variant_id', $product_variant_ids)
                ->each(function ($cartItem) {
                    $cartItem->price       = $cartItem->normal_price;
                    $cartItem->total_price = $cartItem->price * $cartItem->quantity;
                    $cartItem->discount    = 0;
                    $cartItem->save();
                });

            $cart->recalculate();
        });
    }
}
