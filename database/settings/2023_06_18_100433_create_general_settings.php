<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Azkia Hijab');
        $this->migrator->add('general.site_active', true);
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_logo_mobile', null);
        $this->migrator->add('general.site_favicon', null);
        $this->migrator->add('general.facebook_link', null);
        $this->migrator->add('general.instagram_link', null);
        $this->migrator->add('general.whatsapp_link', null);
        $this->migrator->add('general.tiktok_link', null);
    }
};
