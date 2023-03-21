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
use App\Traits\ProductTrait;

class Product extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        HasSEO,
        InteractsWithMedia,
        ProductTrait;

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
}
