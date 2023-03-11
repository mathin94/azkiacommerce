<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{

    public static function group(): string
    {
        return 'general';
    }
}