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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Backoffice\Category as ResourceModel;
use App\Models\Backoffice\Product as ResourceVariant;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class Product extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        HasSEO,
        InteractsWithMedia,
        Cachable;

    public const CACHE_PREFIX = 'products::';
    public const PRICE_LABEL_CACHE_PREFIX = self::CACHE_PREFIX . 'price_label::';

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

    public function resource(): ResourceModel
    {
        return ResourceModel::find($this->resource_id);
    }

    public function resourceVariants(): Builder
    {
        return ResourceVariant::with('detail')
            ->where('category_id', $this->resource_id);
    }

    public function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => route('products.show', $this->slug));
    }

    protected function getImageUrl(int $number)
    {
        return $this->media
            ->where('order_column', $number)
            ->first()
            ?->getUrl();
    }

    protected function firstImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageUrl(1)
        );
    }

    protected function secondImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageUrl(2)
        );
    }

    protected function categoryName(): Attribute
    {
        return Attribute::make(get: function () {
            $cache_key = Category::CACHE_PREFIX . $this->category->slug;
            $ttl = 10 * 60 * 60;

            return Cache::remember($cache_key, $ttl, function () {
                return $this->category->name;
            });
        });
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $cache_key = self::PRICE_LABEL_CACHE_PREFIX . $this->slug;
            $ttl = 24 * 60 * 60;

            return Cache::remember($cache_key, $ttl, function () {
                $min_price = $this->variants?->min('price');
                $max_price = $this->variants?->max('price');

                if ($min_price && $max_price) {
                    $min_price = number_format($min_price, 0, ',', '.');
                    $max_price = number_format($max_price, 0, ',', '.');

                    return collect([
                        "Rp. {$min_price}",
                        "Rp. {$max_price}",
                    ])->unique()->implode(' - ');
                }
            });
        });
    }
}
