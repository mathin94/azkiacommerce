<?php

namespace App\Models\Shop;

use App\Enums\ValueType;
use App\Enums\VoucherType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'value_type',
        'voucher_type',
        'code',
        'minimum_order',
        'maximum_discount',
        'quota',
        'value',
        'active_at',
        'inactive_at',
    ];

    protected $casts = [
        'value_type'   => ValueType::class,
        'voucher_type' => VoucherType::class,
        'active_at'    => 'datetime',
        'inactive_at'  => 'datetime'
    ];
}
