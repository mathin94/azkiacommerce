<?php

namespace App\Services;

use App\Models\Shop\Product;
use App\Models\Shop\ProductVariantReview;

class RecalculateProductRating
{
    public function __construct(private int $product_id)
    {
    }

    public function execute()
    {
        $product = Product::find($this->product_id);

        if (!$product) {
            return false;
        }

        $reviews = $product->reviews;

        if (!$reviews) {
            $product->rating       = 0;
            $product->review_count = 0;
            $product->total_score  = 0;
            $product->save();

            return false;
        }

        $rating_count   = $reviews->count();
        $rating_total   = $reviews->sum('rating');
        $rating_average = $rating_total / $rating_count;

        $product->rating       = $rating_average;
        $product->review_count = $rating_count;
        $product->total_score  = $rating_total;
        $product->save();

        return true;
    }
}
