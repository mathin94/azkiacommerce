<?php

namespace App\Console\Commands;

use App\Models\Shop\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\ProductVariantReview;
use App\Services\RecalculateProductRating;

class ConvertOldReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:convert-old-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Old Review';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $old_database        = env('OLD_DB_NAME');
        $reviews             = DB::table("$old_database.view_convert_review")->get();
        $total_data          = $reviews->count();
        $count               = 0;
        $product_variant_ids = $reviews->pluck('shop_product_variant_id')->unique()->toArray();
        $product_variant_ids = array_filter($product_variant_ids, function ($value) {
            return $value !== null;
        });

        foreach ($reviews as $item) {
            $count += 1;

            $this->info("Processing Data $count of $total_data");
            if (is_null($item->shop_product_variant_id))
                continue;

            $review = ProductVariantReview::firstOrNew(['reference_id' => $item->reference_id]);
            $review->shop_product_variant_id = $item->shop_product_variant_id;
            $review->shop_customer_id        = $item->shop_customer_id;
            $review->product_name            = $item->product_name;
            $review->review                  = $item->review;
            $review->rating                  = $item->rating;
            $review->created_at              = $item->created_at;
            $review->save();

            $this->info("Review for $item->product_name by customer $item->user_name has been saved.");
        }

        $products = Product::whereHas('variants', function ($q) use ($product_variant_ids) {
            $q->whereIn('shop_product_variants.id', $product_variant_ids);
        });

        $this->info("Recalculate Review & Rating for Products");

        $products->each(function ($product) {
            $service = new RecalculateProductRating($product->id);
            $service->execute();

            $this->info("Product $product->name review & rating has been updated.");
        });

        $this->info("All data has been saved.");
    }
}
