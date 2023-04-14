<?php

namespace App\Models\Shop;

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
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id')
            ->withDeleted();
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
