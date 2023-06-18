<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_slogan', "Syar'i and Fashionable");
        $this->migrator->add('general.site_description', 'azkiahijab.co.id adalah website toko online resmi dari Azkia Hijab');
    }
};
