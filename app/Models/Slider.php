<?php

namespace App\Models;

use App\Enums\SliderType;
use App\Traits\SliderTrait;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Slider extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, SliderTrait, QueryCacheable;

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
    public $cacheTags = ['sliders'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'sliders_';

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'redis';

    protected $fillable = [
        'slider_type', 'title', 'link', 'active_at', 'inactive_at'
    ];

    protected $casts = [
        'slider_type' => SliderType::class,
        'active_at'   => 'datetime',
        'inactive_at' => 'datetime'
    ];
}
