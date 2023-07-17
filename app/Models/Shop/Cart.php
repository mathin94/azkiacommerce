<?php

namespace App\Models\Shop;

use App\Enums\CartStatus;
use App\Models\Backoffice\Address;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'shop_carts';

    protected $fillable = [
        'shop_customer_id',
        'status',
        'total_weight',
        'subtotal',
        'shipping_cost',
        'grandtotal',
        'checked_out_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'status'         => CartStatus::class,
        'deleted_at'     => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'shop_cart_id')
            ->orderBy('created_at', 'desc');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
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
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->subtotal, 0, ',', '.'));
    }

    protected function grandtotalLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->grandtotal, 0, ',', '.'));
    }

    protected function totalItem(): Attribute
    {
        return Attribute::make(get: fn () => $this->items->count('quantity'));
    }

    public function recalculate()
    {
        $this->subtotal     = $this->items->sum('total_price');
        $this->total_weight = $this->getTotalWeightAttribute();
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('status', CartStatus::Draft);
    }

    public static function boot()
    {
        parent::boot();
    }
}
