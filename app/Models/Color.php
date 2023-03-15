<?php

namespace App\Models;

use App\Models\Shop\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'hexcode'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
