<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ProductTrait
{
    protected function getImageUrl(int $number)
    {
        return $this->media
            ->where('order_column', $number)
            ->first()
            ?->getUrl();
    }

    protected function firstImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageUrl(1)
        );
    }

    protected function secondImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageUrl(2)
        );
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $min_price = $this->variants?->min('price');
            $max_price = $this->variants?->max('price');

            if ($min_price && $max_price) {
                $min_price = number_format($min_price, 0, ',', '.');
                $max_price = number_format($max_price, 0, ',', '.');

                return collect([
                    "Rp. {$min_price}",
                    "Rp. {$max_price}",
                ])->unique()->implode(' - ');
            }
        });
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::make(get: fn () => secure_url("/product/{$this->slug}"));
    }
}
