<?php

namespace App\Models\Shop;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'shop_carts';

    protected $fillable = [
        'shipping_address_id',
        'shop_customer_id',
        'number',
        'status',
        'total_weight',
        'subtotal',
        'shipping_cost',
        'total_price',
        'checked_out_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'status' => CartStatus::class,
        'deleted_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'shop_cart_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }
}
