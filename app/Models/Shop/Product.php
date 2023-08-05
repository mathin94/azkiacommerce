<?php

namespace App\Models\Shop;

use App\Enums\DiscountType;
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
        'total_score',
        'total_like',
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

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'shop_product_id');
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
            $min_price = base_price($this->variants?->min('price') ?? 0);
            $max_price = base_price($this->variants?->max('price') ?? 0);

            if ($min_price && $max_price) {
                return collect([$min_price, $max_price])->unique()->toArray();
            }
        });
    }

    protected function discountPercentage(): Attribute
    {
        return Attribute::make(get: function () {
            if (!empty($this->activeDiscount)) {
                if (auth()->guard('shop')->check()) {
                    if (auth()->guard('shop')->user()->is_member && !$this->discount_with_membership) {
                        return 0;
                    }
                }

                return $this->activeDiscount->discount_percentage;
            }
        });
    }

    protected function discountWithMembership(): Attribute
    {
        return Attribute::make(get: function () {
            if (!$this->activeDiscount) {
                return false;
            }

            return $this->activeDiscount->with_membership_price;
        });
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $prices = [];
            $valid_discount = $this->discount_with_membership;

            $_prices = $this->prices ?? [];

            foreach ($_prices as $key => $price) {
                if ($valid_discount) {
                    $price = $price - ($price * ($this->discount_percentage / 100));
                }

                $prices[] = format_rupiah($price);
            }

            return collect($prices)->unique()->implode(' - ');
        });
    }

    protected function normalPriceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $prices = [];
            $_prices = $this->prices ?? [];
            foreach ($_prices as $key => $value) {
                $prices[] = format_rupiah($value);
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

    private function getFinalPrice($price)
    {
        $membership_discount = auth()->guard('shop')->user()?->discount_percentage;

        if ($membership_discount) {
            $price -= ($price * $membership_discount / 100);
        }

        return $price;
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    public function scopeOnSale($query)
    {
        return $query->whereHas('activeDiscount', function ($q) {
            $q->where('discount_type', DiscountType::NormalDiscount());
        });
    }

    public function scopeFlashSale($query)
    {
        return $query->whereHas('activeDiscount', function ($q) {
            $q->where('discount_type', DiscountType::FlashSale());
        });
    }

    public function scopeByCategories($query, $categoryIds)
    {
        return $query->whereIn('shop_product_category_id', $categoryIds);
    }

    public function scopeBySizes($query, $sizeIds)
    {
        return $query->whereHas('variants', function ($query) use ($sizeIds) {
            $query->whereIn('size_id', $sizeIds);
        });
    }

    public function scopeByColor($query, $colorId)
    {
        return $query->whereHas('variants', function ($query) use ($colorId) {
            $query->where('color_id', $colorId);
        });
    }

    public function scopeMinPrice($query, $price)
    {
        return $query->whereHas('variants', function ($query) use ($price) {
            $query->where('price', '>=', $price);
        });
    }

    public function scopeMaxPrice($query, $price)
    {
        return $query->whereHas('variants', function ($query) use ($price) {
            $query->where('price', '<=', $price);
        });
    }

    public function scopeSortByValue($query, $value)
    {
        if ($value == 'created_at') {
            return $query->orderBy('created_at', 'desc');
        }

        if ($value == 'top_rated') {
            return $query->orderBy('total_score', 'desc');
        }

        if ($value == 'lowest_price') {
            return $query->orderBy(function ($q) {
                $q->select('price')
                    ->from('shop_product_variants')
                    ->whereColumn('shop_product_variants.shop_product_id', '=', 'shop_products.id')
                    ->orderBy('price')
                    ->limit(1);
            }, 'asc');
        }

        if ($value == 'highest_price') {
            return $query->orderBy(function ($q) {
                $q->select('price')
                    ->from('shop_product_variants')
                    ->whereColumn('shop_product_variants.shop_product_id', '=', 'shop_products.id')
                    ->orderBy('price', 'desc')
                    ->limit(1);
            }, 'desc');
        }

        if ($value == 'name_desc') {
            return $query->orderBy('name', 'desc');
        }

        return $query->orderBy('name', 'asc');
    }

    protected function wishlisted(): Attribute
    {
        return Attribute::make(get: function () {
            if (!auth()->guard('shop')->check()) {
                return false;
            }

            return $this->wishlists->where('shop_customer_id', auth()->guard('shop')->id())->count();
        });
    }

    protected function discountRemainingSecondsLabel(): Attribute
    {
        return Attribute::make(get: function () {
            if (empty($this->activeDiscount))
                return;

            return "+{$this->activeDiscount->remaining_seconds}s";
        });
    }

    protected function isActiveFlashSale(): Attribute
    {
        return Attribute::make(get: function () {
            if (empty($this->activeDiscount))
                return false;

            return $this->activeDiscount->is_flash_sale;
        });
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->variants()->delete();
        });
    }
}
