<?php

namespace App\Models;

use App\Traits\PageTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Cachable;

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
