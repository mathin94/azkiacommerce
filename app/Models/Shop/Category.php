<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 60 * 60 * 24; // 1 day

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['shop_product_categories'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'shop_product_categories_';

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'redis';

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

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_product_category_id');
    }

    protected function catalogImageUrl(): Attribute
    {
        return Attribute::make(get: fn () => blank($this->catalog_image) ? null : asset('storage/' . $this->catalog_image));
    }

    protected function bannerImageUrl(): Attribute
    {
        return Attribute::make(get: fn () => blank($this->banner_image) ? null : asset('storage/' . $this->banner_image));
    }
}
