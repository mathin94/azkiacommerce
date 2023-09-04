<?php

namespace App\Jobs;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Models\Shop\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateVariantCartActiveJob implements ShouldQueue
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
                $query->where('status', CartStatus::Draft);
            })
            ->get();

        foreach ($cart_items as $cart_item) {
            $cart_item->delete();
        }
    }
}
