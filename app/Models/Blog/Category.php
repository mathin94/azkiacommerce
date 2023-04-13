<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Category extends Model
{
    use SoftDeletes, HasFactory, QueryCacheable;

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
    public $cacheTags = ['blog_post_categories'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'blog_post_categories_';

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'redis';

    public const CACHE_PREFIX     = 'blog_categories::';
    public const ACTIVE_CACHE_KEY = self::CACHE_PREFIX . '_active';

    protected $table = 'blog_post_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'blog_post_category_id');
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->whereSlug($slug)->first();
    }

    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

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
