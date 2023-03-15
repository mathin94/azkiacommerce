<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql_pos';

    public int $price;

    public function detail()
    {
        return $this->hasOne(ProductOutlet::class)
            ->where('product_outlets.outlet_id', 1);
    }

    public function price(): Float | null
    {
        return $this->detail?->retail_price;
    }

    public function stock(): Int | null
    {
        return $this->detail?->stock;
    }
}
