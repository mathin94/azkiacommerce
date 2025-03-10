<?php

namespace App\Models\Shop;

use App\Enums\ValueType;
use App\Enums\VoucherType;
use Dotenv\Parser\Value;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_vouchers';

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
        'inactive_at'  => 'datetime',
    ];

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class, 'shop_voucher_id');
    }

    protected function isPercentage(): Attribute
    {
        return Attribute::make(get: fn () => $this->value_type == ValueType::Percentage());
    }

    public function scopeActive($query)
    {
        $now = now();

        $query->where('active_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->where('inactive_at', '>', $now)
                    ->orWhereNull('inactive_at');
            });
    }
}
