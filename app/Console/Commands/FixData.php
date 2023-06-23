<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing Data, For Development Only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // write custom code here

        $reviews = \App\Models\Shop\ProductVariantReview::with(['productVariant.color', 'productVariant.size'])->get();

        $reviews->each(function ($review) {
            $color = $review->productVariant->color->name;
            $size = $review->productVariant->size->name;

            $review->update([
                'product_name' => $review->productVariant->name,
                'variant_name' => $color . ' - ' . $size
            ]);
        });

        $this->info('Done');
    }
}
