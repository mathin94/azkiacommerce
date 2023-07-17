<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ComingSoon extends Settings
{
    public ?string $link_title;

    public ?string $link_slug;

    public ?string $body_title;

    public ?string $launching_date;

    public ?string $body_content;

    public ?string $background_image;

    public ?bool $active;

    public static function group(): string
    {
        return 'comingsoon';
    }

    public function backgroundImage()
    {
        if (blank($this->background_image)) {
            return null;
        }

        return asset("storage/$this->background_image");
    }
}
