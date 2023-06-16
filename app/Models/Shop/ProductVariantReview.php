<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductVariantReview extends Model
{
    use SoftDeletes;

    protected $table = 'shop_product_variant_reviews';

    protected $fillable = [
        'shop_product_variant_id',
        'shop_order_item_id',
        'shop_customer_id',
        'rating',
        'review',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'shop_order_item_id');
    }

    protected function ratingPercentage(): Attribute
    {
        return Attribute::make(get: fn () => $this->rating * 20);
    }
}
