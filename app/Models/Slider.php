<?php

namespace App\Models;

use App\Enums\SliderType;
use App\Traits\SliderTrait;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, SliderTrait;

    protected $fillable = [
        'slider_type', 'title', 'link', 'active_at', 'inactive_at'
    ];

    protected $casts = [
        'slider_type' => SliderType::class,
        'active_at'   => 'datetime',
        'inactive_at' => 'datetime'
    ];
}
