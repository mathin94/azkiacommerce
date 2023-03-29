<?php

namespace App\Models\Shop;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Cachable;

    public const CACHE_PREFIX       = 'product_category::';
    public const ALL_CATEGORY_CACHE = self::CACHE_PREFIX . 'all';

    protected $table = 'shop_product_categories';

    protected $fillable = [
        'name',
        'slug',
        'banner_image',
        'catalog_image',
        'product_count'
    ];

    protected function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => route('category.show', $this->slug));
    }
}
