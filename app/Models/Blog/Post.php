<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use SoftDeletes, HasTags, HasSEO, HasFactory, InteractsWithMedia, QueryCacheable;

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
    public $cacheTags = ['blog_posts'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'blog_posts_';

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

    public const TOP_CACHE_PREFIX  = 'blog-post::_top_five';
    public const TOP_POPULAR_CACHE = 'blog-post::_top_five::popular';
    public const CACHE_PREFIX      = 'blog-post::';
    public const DEFAULT_CACHE_TTL = 24 * 60 * 60; // 24 hours
    public const MEDIA_COLLECTION  = 'post-image';

    protected $table = 'blog_posts';

    protected $fillable = [
        'blog_post_category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'blog_post_category_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable')->orderBy('created_at', 'desc');
    }

    protected function commentCountLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->comments->count() . ' Komentar';
        });
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => route('blogs.show', $this->slug));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(get: function () {
            $url = $this->getFirstMediaUrl(self::MEDIA_COLLECTION);

            if (empty($url))
                $url = asset('/build/assets/images/no-thumbnail-medium.png');

            return $url;
        });
    }

    public function nextPost()
    {
        return Post::where('id', '!=', $this->id)
            ->published()
            ->where('published_at', '>', $this->published_at)
            ->orderBy('published_at')
            ->first();
    }

    public function prevPost()
    {
        return Post::where('published_at', '<', $this->published_at)
            ->orderBy('published_at', 'desc')
            ->published()
            ->where('id', '!=', $this->id)
            ->first();
    }

    public function getRelated()
    {
        $tag_ids = $this->tags->pluck('id');

        return Post::with(['media', 'comments', 'category'])
            ->where('id', '!=', $this->id)
            ->where('blog_post_category_id', $this->blog_post_category_id)
            ->orWhereHas('tags', function ($query) use ($tag_ids) {
                $query->whereIn('id', $tag_ids);
            })
            ->published()
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function scopeGetPopular($query)
    {
        return $query
            ->with(['media'])
            ->published()
            ->orderBy('view_count', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->author_id = auth()->id();
            }
        });
    }
}
