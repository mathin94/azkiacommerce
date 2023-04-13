<?php

namespace App\Models\Shop;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_product_discounts';

    protected $fillable = [
        'shop_product_id',
        'discount_type',
        'with_membership_price',
        'discount_percentage',
        'maximum_qty',
        'active_at',
        'inactive_at'
    ];

    protected $casts = [
        'active_at' => 'datetime',
        'inactive_at' => 'datetime',
        'discount_type' => DiscountType::class
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function discountVariants()
    {
        return $this->hasMany(ProductDiscountVariant::class, 'shop_product_discount_id');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'shop_product_discount_variants', 'shop_product_discount_id', 'shop_product_variant_id')
            ->withTimestamps();
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

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->active_at?->isPast() &&
                    ($this->inactive_at?->isFuture() || is_null($this->inactive_at));
            }
        );
    }
}
