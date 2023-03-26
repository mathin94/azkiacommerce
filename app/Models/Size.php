<?php

namespace App\Models;

use App\Models\Shop\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;

    public const ALL_CACHE_KEY = 'sizes::all';

    protected $fillable = [
        'code', 'name'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
