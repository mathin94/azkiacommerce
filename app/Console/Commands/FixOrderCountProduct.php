<?php

namespace App\Console\Commands;

use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use Illuminate\Console\Command;

class FixOrderCountProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-order-count-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing Order Count Product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::whereHas('variants', function ($q) {
            $q->whereHas('orderItems', function ($q) {
                $q->whereHas('order', function ($q) {
                    $q->whereNotNull('paid_at');
                });
            });
        })
            ->get();

        foreach ($products as $product) {
            $product_variant_ids = $product->variants->pluck('id')->toArray();

            $order_items = OrderItem::whereIn('shop_product_variant_id', $product_variant_ids)
                ->whereHas('order', function ($q) {
                    $q->whereNotNull('paid_at');
                });

            $product->order_count = $order_items->sum('quantity');
            $product->save();

            $this->info($product->order_count);
        }
    }
}
