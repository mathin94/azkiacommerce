<?php

namespace App\Services;

use App\Models\Shop\Order;

class RecalculateOrderCountService
{
    public function __construct(
        private int $order_id
    ) {
    }

    public function execute()
    {
        $order    = Order::with('items.productVariant.product')->find($this->order_id);
        $products = $order->items->pluck('productVariant.product')->unique();

        if (!$order) {
            return false;
        }

        foreach ($products as $product) {
            $items   = $order->items->where('productVariant.shop_product_id', $product->id);
            $qty_sum = $items->sum('quantity');

            $product->increment('order_count', $qty_sum);
        }

        return true;
    }
}
