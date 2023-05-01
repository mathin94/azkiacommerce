<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 60 * 60 * 24 * 7; // 7 days

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['testimonials'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'testimonials_';

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

    public const ACTIVE_CACHE_KEY = 'testimonials::active';

    protected $table = 'testimonials';

    protected $fillable = [
        'name', 'title', 'comment', 'active'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });

        static::updating(function ($model) {
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });

        static::deleting(function ($model) {
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });
    }
}
