<?php

namespace App\Models;

use App\Models\Shop\ProductVariant;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $cachePrefix = 'colors-';

    protected $cacheCooldownSeconds = 24 * 60 * 60;

    public const ALL_CACHE_KEY = 'colors::all';

    protected $fillable = [
        'code', 'name', 'hexcode'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
