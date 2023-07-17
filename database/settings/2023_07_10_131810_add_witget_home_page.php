<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.widget_home_page', '');
        $this->migrator->add('general.custom_scripts', '');
    }
};
