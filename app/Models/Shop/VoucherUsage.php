<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherUsage extends Model
{
    use SoftDeletes;

    protected $table = 'shop_voucher_usages';

    protected $fillable = [
        'shop_voucher_id',
        'shop_order_id',
        'amount',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'shop_voucher_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'shop_order_id');
    }
}
