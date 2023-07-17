<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('comingsoon.link_title', 'Segera Hadir');
        $this->migrator->add('comingsoon.link_slug', 'coming-soon');
        $this->migrator->add('comingsoon.body_title', 'Segera Hadir');
        $this->migrator->add('comingsoon.launching_date', '2023-09-01 15:00:00');
        $this->migrator->add('comingsoon.body_content', 'Launching New Product Dress Jelita...');
        $this->migrator->add('comingsoon.background_image', '');
        $this->migrator->add('comingsoon.active', false);
    }
};
