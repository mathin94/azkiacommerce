<?php

namespace App\Console\Commands;

use App\Models\Shop\ProductVariantReview;
use Illuminate\Console\Command;

class FixReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing Nameless Review';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reviews = ProductVariantReview::with('productVariant')->whereNull('product_name')->get();

        foreach ($reviews as $review) {
            $review->product_name = $review->productVariant->name;
            $review->save();

            $this->info($review->product_name . ' has been fixed.');
        }
    }
}
