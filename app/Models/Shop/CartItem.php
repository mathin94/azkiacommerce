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
        'unit_price',
        'weight',
        'quantity',
        'discount',
        'total',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'shop_cart_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id');
    }

    protected function productImageUrl(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->productVariant->product->main_image_url;
        });
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return 'Rp. ' . number_format($this->unit_price, 0, ',', '.');
        });
    }

    protected function subtotalLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $subtotal = $this->unit_price * $this->quantity;

            return 'Rp. ' . number_format($subtotal, 0, ',', '.');
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->total = $model->unit_price * $model->quantity;
        });

        static::updating(function ($model) {
            $model->total = $model->unit_price * $model->quantity;
        });
    }
}
