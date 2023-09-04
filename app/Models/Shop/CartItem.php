<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'shop_cart_items';

    protected $fillable = [
        'shop_cart_id',
        'shop_product_variant_id',
        'name',
        'alternate_name',
        'normal_price',
        'price',
        'weight',
        'quantity',
        'discount',
        'total_price',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'shop_cart_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id')
            ->withTrashed();
    }

    protected function productImageUrl(): Attribute
    {
        return Attribute::make(get: function () {
            $variant = $this->productVariant;

            if (!$variant) {
                return null;
            }

            $variant_image = $variant?->media?->getUrl();

            if ($variant_image) {
                return $variant_image;
            }

            return $this->productVariant->product?->main_image_url;
        });
    }

    protected function normalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->normal_price, 0, ',', '.'));
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->price, 0, ',', '.'));
    }

    protected function finalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->price - $this->discount, 0, ',', '.'));
    }

    protected function totalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () =>  'Rp. ' . number_format($this->total_price, 0, ',', '.'));
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->total_price = $model->price * $model->quantity;
        });

        static::created(function ($model) {
            // $model->cart->recalculate();
        });

        static::updating(function ($model) {
            $model->total_price = $model->price * $model->quantity;
        });
    }
}
