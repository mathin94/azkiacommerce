<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

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
