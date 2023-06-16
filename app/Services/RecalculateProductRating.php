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
        $product = Product::with('reviews')->find($this->product_id);
        $reviews = $product->reviews;

        $rating_count = $reviews->count();
        $rating_total = $reviews->sum('rating');
        $rating_average = $rating_total / $rating_count;

        $product->update([
            'rating' => $rating_average,
            'review_count' => $rating_count
        ]);

        return true;
    }
}
