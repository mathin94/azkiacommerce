<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'shop_order_items';

    protected $fillable = [
        'shop_order_id',
        'shop_product_variant_id',
        'name',
        'alternate_name',
        'normal_price',
        'price',
        'weight',
        'quantity',
        'discount',
        'total_price',
        'color',
        'size',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'shop_order_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id')
            ->withTrashed();
    }

    public function review()
    {
        return $this->hasOne(ProductVariantReview::class, 'shop_order_item_id');
    }

    protected function weightLabel(): Attribute
    {
        return Attribute::make(get: fn () => (int) $this->weight . ' gram');
    }

    protected function normalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->normal_price, 0, ',', '.'));
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->price, 0, ',', '.'));
    }

    protected function totalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->total_price, 0, ',', '.'));
    }

    protected function productImageUrl(): Attribute
    {
        return Attribute::make(get: function () {
            $variant = $this->productVariant;

            $variant_image = $variant?->media?->getUrl();

            if ($variant_image) {
                return $variant_image;
            }

            $image = $this->productVariant?->product?->main_image_url;

            return $image ?? asset('/build/assets/images/no-thumbnail-medium.png');
        });
    }

    protected function ratingPercentage(): Attribute
    {
        return Attribute::make(get: function () {
            if (!$this->review) {
                return 0;
            }

            return $this->review->rating_percentage;
        });
    }
}
