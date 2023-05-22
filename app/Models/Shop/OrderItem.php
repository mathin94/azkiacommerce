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
        return $this->belongsTo(ProductVariant::class, 'shop_product_variant_id');
    }

    public function weightLabel(): Attribute
    {
        return Attribute::make(get: fn () => (int) $this->weight . ' gram');
    }

    public function priceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->price, 0, ',', '.'));
    }

    public function totalPriceLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->total_price, 0, ',', '.'));
    }
}
