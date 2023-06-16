<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Product extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        HasSEO,
        QueryCacheable,
        InteractsWithMedia;

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
    public $cacheTags = ['shop_products'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'shop_products_';

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

    public const CACHE_PREFIX = 'products::';
    public const MAIN_IMAGE_COLLECTION_NAME = 'main-image';
    public const GALLERY_IMAGE_COLLECTION_NAME = 'product-images';

    protected $table = 'shop_products';

    protected $fillable = [
        'resource_id',
        'shop_product_category_id',
        'name',
        'slug',
        'description',
        'published_at',
        'featured',
        'visible',
        'allow_preorder',
        'order_count',
        'view_count',
        'review_count',
        'rating',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'shop_product_category_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'shop_product_id');
    }

    public function resource()
    {
        return $this->belongsTo(\App\Models\Backoffice\Category::class, 'resource_id');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'shop_product_id');
    }

    public function activeDiscount()
    {
        return $this->hasOne(ProductDiscount::class, 'shop_product_id')->active();
    }

    public function reviews()
    {
        return $this->hasManyThrough(ProductVariantReview::class, ProductVariant::class, 'shop_product_id', 'shop_product_variant_id');
    }

    public function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => route('products.show', $this->slug));
    }

    protected function mainImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MAIN_IMAGE_COLLECTION_NAME)
        );
    }

    protected function categoryName(): Attribute
    {
        return Attribute::make(get: fn () => $this->category->name);
    }

    protected function prices(): Attribute
    {
        return Attribute::make(get: function () {
            $min_price = $this->variants?->min('price');
            $max_price = $this->variants?->max('price');

            if ($min_price && $max_price) {
                return collect([$min_price, $max_price])->unique()->toArray();
            }
        });
    }

    protected function discountPercentage(): Attribute
    {
        return Attribute::make(get: function () {
            if (!$this->activeDiscount) {
                return 0;
            }

            return $this->activeDiscount->discount_percentage;
        });
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $prices = [];

            foreach ($this->prices as $key => $value) {
                $price = $value - ($value * ($this->discount_percentage / 100));
                $prices[] = 'Rp. ' . number_format($price, 0, ',', '.');
            }

            return collect($prices)->unique()->implode(' - ');
        });
    }

    protected function normalPriceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $prices = [];

            foreach ($this->prices as $key => $value) {
                $prices[] = 'Rp. ' . number_format($value, 0, ',', '.');
            }

            return collect($prices)->unique()->implode(' - ');
        });
    }

    protected function weightLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $min_weight = $this->variants?->min('weight');
            $max_weight = $this->variants?->max('weight');

            if ($min_weight && $max_weight) {
                return collect([
                    "{$min_weight}",
                    "{$max_weight}",
                ])->unique()->implode(' - ') . ' gram';
            }
        });
    }

    protected function ratingPercentage(): Attribute
    {
        return Attribute::make(get: fn () => $this->rating * 20);
    }
}
