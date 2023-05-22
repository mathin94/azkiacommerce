<?php

namespace App\Models\Shop;

use App\Enums\OrderStatus;
use App\Models\Backoffice\Address;
use App\Models\Backoffice\Sales;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $fillable = [
        'shop_customer_id',
        'resource_id',
        'user_id',
        'packing_employee_id',
        'number',
        'invoice_number',
        'total_weight',
        'total',
        'discount_voucher',
        'shipping_cost',
        'grandtotal',
        'total_profit',
        'status',
        'notes',
        'prepared_by',
        'approved_by',
        'payment_properties',
        'proof_of_payment',
        'proof_uploaded_at',
        'paid_at',
        'canceled_at',
    ];

    protected $casts = [
        'status'             => OrderStatus::class,
        'deleted_at'         => 'timestamp',
        'paid_at'            => 'timestamp',
        'canceled_at'        => 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'shop_order_id');
    }

    public function shipping()
    {
        return $this->hasOne(OrderShipping::class, 'shop_order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function resource()
    {
        return $this->belongsTo(Sales::class, 'resource_id');
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class, 'shop_order_id');
    }

    protected function grandtotalLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' . number_format($this->grandtotal, 0, ',', '.'));
    }

    protected function totalWeightLabel(): Attribute
    {
        return Attribute::make(get: fn () => number_format($this->total_weight, 0, ',', '.') . ' gram');
    }

    protected function subtotalLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' .  number_format($this->total, 0, ',', '.'));
    }

    protected function shippingCostLabel(): Attribute
    {
        return Attribute::make(get: fn () => 'Rp. ' .  number_format($this->shipping_cost, 0, ',', '.'));
    }
}
