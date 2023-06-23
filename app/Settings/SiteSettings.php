<?php

namespace App\Settings;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;

    public string $site_slogan;

    public string $site_description;

    public bool $site_active;

    public ?string $site_logo;

    public ?string $site_logo_mobile;

    public ?string $site_favicon;

    public ?string $facebook_link;

    public ?string $instagram_link;

    public ?string $whatsapp_link;

    public ?string $tiktok_link;

    public static function group(): string
    {
        return 'general';
    }

    public function siteTitle()
    {
        return "$this->site_name - $this->site_slogan";
    }

    public function favicon()
    {
        if (blank($this->site_favicon)) {
            return null;
        }

        return asset("storage/$this->site_favicon");
    }

    public function mainLogo()
    {
        if (blank($this->site_logo)) {
            return null;
        }

        return asset("storage/$this->site_logo");
    }

    public function mobileLogo()
    {
        if (blank($this->site_logo_mobile)) {
            return null;
        }

        return asset("storage/$this->site_logo_mobile");
    }
}
