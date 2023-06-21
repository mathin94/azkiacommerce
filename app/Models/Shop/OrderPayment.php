<?php

namespace App\Models\Shop;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderPayment extends Model
{
    use SoftDeletes;

    protected $table = 'shop_order_payments';

    protected $fillable = [
        'shop_order_id',
        'bank_account_id',
        'proof_of_payment',
        'proof_uploaded_at',
        'payment_properties',
        'uuid',
    ];

    protected $casts = [
        'payment_properties' => 'json',
        'proof_uploaded_at'  => 'timestamp',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'shop_order_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function proofOfPaymentUrl(): Attribute
    {
        return Attribute::make(get: function () {
            if (!blank($this->proof_of_payment)) {
                return asset("storage/$this->proof_of_payment", config('app.env') !== 'local');
            }
        });
    }

    public static function boot()
    {
        parent::boot();
    }
}
