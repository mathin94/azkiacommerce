<?php

namespace App\Models\Shop;

use App\Jobs\CartItemProductValidatorJob;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 0; // 7 days

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['shop_product_variants'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'shop_product_variants_';

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

    public const TABLE_NAME = 'shop_product_variants';

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        'resource_id',
        'shop_product_id',
        'color_id',
        'size_id',
        'barcode',
        'code_name',
        'name',
        'weight',
        'price',
        'media_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'shop_product_variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'shop_product_variant_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function media()
    {
        return $this->belongsTo(\App\Models\Media::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductVariantReview::class, 'shop_product_variant_id');
    }

    public function resource()
    {
        return $this->belongsTo(\App\Models\Backoffice\Product::class, 'resource_id');
    }

    protected function alternateName(): Attribute
    {
        return Attribute::make(get: function () {
            if (!$this->color || !$this->size) {
                return $this->name;
            }

            return "{$this->product->name} - {$this->color->name} - {$this->size->name}";
        });
    }

    protected function productOutletId(): Attribute
    {
        return Attribute::make(get: fn () => $this->resource->product_outlet_id);
    }

    protected function stock(): Attribute
    {
        return Attribute::make(get: fn () => $this->resource->stock);
    }

    static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            CartItemProductValidatorJob::dispatch($model->id);
        });
    }
}
