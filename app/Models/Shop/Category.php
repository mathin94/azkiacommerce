<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_product_categories';

    protected $fillable = [
        'name', 'slug', 'banner_image', 'catalog_image'
    ];
}
