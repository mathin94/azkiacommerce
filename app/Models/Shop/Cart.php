<?php

namespace App\Models\Shop;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function items()
    {
        return $this->hasMany(CartItem::class, 'shop_cart_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function getTotalPriceAttribute(): Float
    {
        // Calculate the total price based on the cart items
        $totalPrice = $this->items->sum(function ($cartItem) {
            return $cartItem->total * $cartItem->quantity;
        });

        return $totalPrice;
    }

    public function getTotalWeightAttribute(): Int
    {
        // Calculate the total price based on the cart items
        $totalWeight = $this->items->sum(function ($cartItem) {
            return $cartItem->weight * $cartItem->quantity;
        });

        return $totalWeight;
    }

    protected function itemCount(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->items->sum('quantity');
        });
    }

    protected function subtotalLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp' . number_format($this->subtotal, 0, ',', '.'));
    }

    public function recalculate()
    {
        $this->subtotal     = $this->getTotalPriceAttribute();
        $this->total_weight = $this->getTotalWeightAttribute();
        $this->total_price  = $this->subtotal + ($this->shipping_cost ?? 0);
        $this->save();
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->recalculate();
        });

        static::updated(function ($model) {
            $model->recalculate();
        });
    }
}
