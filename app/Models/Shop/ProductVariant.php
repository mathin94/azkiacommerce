<?php

namespace App\Models\Shop;

use App\Models\Backoffice\Product as ResourceModel;
use App\Models\Color;
use App\Models\Size;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $table = 'shop_product_variants';

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

    public function resource(): ResourceModel
    {
        return ResourceModel::with('detail')
            ->has('detail')
            ->find($this->resource_id);
    }
}
