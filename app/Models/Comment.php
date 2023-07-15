<?php

namespace App\Models;

use App\Models\Shop\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Comment extends Model
{
    use HasFactory;

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
    public $cacheTags = ['comments'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'comments_';

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

    protected $table = 'comments';

    protected $fillable = [
        'approved', 'comment', 'shop_customer_id', 'user_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->approved = false;
        });
    }
}
