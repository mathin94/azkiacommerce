<?php

namespace App\Traits;

use App\Enums\SliderType;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait SliderTrait
{
    public function scopeActive($query)
    {
        return $query->where('active_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('inactive_at')
                    ->orWhere('inactive_at', '>', now());
            });
    }

    public function scopeActiveIntroSliders($query)
    {
        return $query->with('media')
            ->whereSliderType(SliderType::IntroSlider())
            ->active();
    }

    public function scopeActiveAdsSliders($query)
    {
        return $query->with('media')
            ->whereSliderType(SliderType::AdSlider())
            ->active();
    }

    public function scopeActiveBanners($query)
    {
        return $query->with('media')
            ->whereSliderType(SliderType::StaticBanner())
            ->active();
    }

    public function isActive(): bool
    {
        return $this->active_at?->isPast() &&
            ($this->inactive_at?->isFuture() ||
                $this->inactive_at?->isPast() ||
                !$this->inactive_at);
    }

    protected function getImageUrl()
    {
        return $this->media?->first()->getUrl();
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageUrl()
        );
    }
}
