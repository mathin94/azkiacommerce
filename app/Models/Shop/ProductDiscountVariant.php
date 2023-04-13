<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscountVariant extends Model
{
    use HasFactory;

    protected $table = 'shop_product_discount_variants';

    protected $fillable = [
        'shop_product_discount_id',
        'shop_product_variant_id',
    ];

    public function productDiscount()
    {
        return $this->belongsTo(ProductDiscount::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
