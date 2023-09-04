<?php

namespace App\Jobs;

use App\Models\Shop\Cart;
use Illuminate\Bus\Queueable;
use App\Models\Shop\ProductVariant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CartItemProductValidatorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $shop_product_variant_id
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product_variant = ProductVariant::withTrashed()
            ->find($this->shop_product_variant_id);

        if (!$product_variant || !$product_variant->trashed()) {
            return;
        }

        $cart_items = $product_variant->cartItems()
            ->whereHas('cart', function ($query) {
                $query->where('status', \App\Enums\CartStatus::Draft);
            })
            ->get();

        $cart_ids = [];

        foreach ($cart_items as $cart_item) {
            $cart_item->delete();
            $cart_ids[] = $cart_item->shop_cart_id;
        }

        $carts = Cart::whereIn('id', $cart_ids)->get();

        foreach ($carts as $cart) {
            $cart->recalculate();
        }
    }
}
