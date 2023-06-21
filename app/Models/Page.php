<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Page extends Model implements HasMedia
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
    public $cacheTags = ['pages'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'pages_';

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

    public const ACTIVE_CACHE_KEY = 'pages::active';
    public const CACHE_KEY_PREFIX = 'page::';

    protected $fillable = [
        'slug', 'title', 'content', 'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected function getThumbnailUrl()
    {
        return $this->media->first()->getUrl();
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getThumbnailUrl()
        );
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => route('page', $this->slug));
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->loadMedia('default')->reverse();
            $cache_key = self::CACHE_KEY_PREFIX . $model->slug;

            Cache::set($cache_key, $model, 24 * 60 * 60);
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });

        static::updating(function ($model) {
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });

        static::deleting(function ($model) {
            $cache_key = self::CACHE_KEY_PREFIX . $model->slug;

            Cache::forget($cache_key);
            Cache::forget(self::ACTIVE_CACHE_KEY);
        });
    }
}
