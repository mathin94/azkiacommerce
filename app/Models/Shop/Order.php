<?php

namespace App\Models\Shop;

use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Support\Str;
use App\Models\Backoffice\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        return $this->hasMany(OrderItem::class, 'shop_order_id')
            ->orderBy('created_at', 'desc');
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

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class, 'shop_order_id');
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

    protected function finalPriceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return 'Rp. ' . number_format($this->grandtotal, 0, ',', '.');
        });
    }

    protected function discountVoucherLabel(): Attribute
    {
        return Attribute::make(get: fn () => format_rupiah($this->discount_voucher));
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(get: function () {
            switch ($this->status->value) {
                case OrderStatus::WaitingPayment:
                    return 'secondary';
                    break;
                case OrderStatus::Paid:
                    return 'info';
                    break;
                case OrderStatus::PackageSent:
                    return 'success';
                    break;
                case OrderStatus::Completed:
                    return 'success';
                    break;
                case OrderStatus::Canceled:
                    return 'danger';
                    break;
                default:
                    return 'warning';
                    break;
            }
        });
    }

    protected function statusPaid(): Attribute
    {
        return Attribute::make(get: function () {
            return !in_array($this->status->value, [
                OrderStatus::WaitingPayment,
                OrderStatus::Canceled,
            ]);
        });
    }

    protected function statusWaitingPayment(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->status->value === OrderStatus::WaitingPayment;
        });
    }

    protected function dateFormatId(): Attribute
    {
        return Attribute::make(get: fn () => $this->created_at->locale('id')->isoFormat('DD MMMM YYYY, HH:mm [WIB]'));
    }

    protected function totalItem(): Attribute
    {
        return Attribute::make(get: fn () => $this->items->count('quantity'));
    }

    protected function totalWeight(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->items->sum(function ($item) {
                return $item->weight * $item->quantity;
            });
        });
    }

    public function cancel(): bool
    {
        if (empty($this->canceled_at)) {
            $this->update([
                'status'      => OrderStatus::Canceled,
                'canceled_at' => now(),
            ]);

            return true;
        }

        return false;
    }

    protected function customerCancelable(): Attribute
    {
        return Attribute::make(fn () => $this->status->value === OrderStatus::WaitingPayment);
    }

    protected function adminCancelable(): Attribute
    {
        return Attribute::make(get: function () {
            return in_array($this->status->value, [
                OrderStatus::WaitingPayment,
                OrderStatus::WaitingConfirmation,
            ]);
        });
    }

    // scope canceled
    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::Canceled);
    }

    public function scopeWaitingPayment($query)
    {
        return $query->whereIn('status', [
            OrderStatus::WaitingConfirmation,
            OrderStatus::WaitingPayment,
        ]);
    }

    public function scopeWaitingDelivery($query)
    {
        return $query->where('status', OrderStatus::Paid);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::PackageSent);
    }

    // scope ongoing
    public function scopeOngoing($query)
    {
        return $query->whereIn('status', [
            OrderStatus::WaitingPayment,
            OrderStatus::WaitingConfirmation,
            OrderStatus::Paid,
            OrderStatus::PackageSent,
        ]);
    }

    // scope completed
    public function scopeCompleted($query)
    {
        return $query->where('status', OrderStatus::Completed);
    }

    public function courierLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->shipping?->courier_label_alternative;
        });
    }

    protected function proofOfPaymentUrl(): Attribute
    {
        return Attribute::make(get: fn () => $this->payment?->proof_of_payment_url);
    }

    protected function transferTo(): Attribute
    {
        return Attribute::make(get: function () {
            if (empty($this->payment->payment_properties)) {
                return null;
            }

            $props = json_decode($this->payment->payment_properties);

            return $props->bank_name . ' - ' . $props->account_name . ' - ' . $props->account_number;
        });
    }

    protected function trackable(): Attribute
    {
        return Attribute::make(get: function () {
            return !blank($this->shipping?->receipt_number) && $this->statusPackageSent();
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if (!empty($status)) {
            $status = Str::of($status)->studly()->toString();

            $status_enum = OrderStatus::fromKey($status);

            if (in_array($status, OrderStatus::getKeys())) {
                return $query->where('status', $status_enum);
            }
        }

        return $query;
    }


    public function __call($methodName, $arguments)
    {
        if (strpos($methodName, 'status') === 0) {
            $statusName = substr($methodName, 6);

            if (in_array($statusName, OrderStatus::getKeys())) {
                // Check if the status name matches the current status
                return $this->status->key === $statusName;
            }
        }

        return parent::__call($methodName, $arguments);
    }
}
