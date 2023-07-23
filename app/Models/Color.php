<?php

namespace App\Models;

use App\Models\Shop\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Color extends Model
{
    use HasFactory, SoftDeletes, QueryCacheable;

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
    public $cacheTags = ['colors'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'colors_';

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

    public const ALL_CACHE_KEY = 'colors::all';

    protected $fillable = [
        'code', 'name', 'hexcode'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = \Str::slug($model->name);
        });
    }
}
