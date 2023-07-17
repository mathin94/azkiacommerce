<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShopSettings extends Settings
{
    public string $address;

    public string $phone;

    public string $email;

    public string $latitude;

    public string $longitude;

    public static function group(): string
    {
        return 'shop';
    }
}
