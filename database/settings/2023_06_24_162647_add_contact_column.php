<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.address', 'Nagrak, Kec. Cisaat, Kabupaten Sukabumi, Jawa Barat 43152');
        $this->migrator->add('shop.phone', '08568924115');
        $this->migrator->add('shop.email', 'azkiahijabofficial@gmail.com');
        $this->migrator->add('shop.latitude', '-6.8990726');
        $this->migrator->add('shop.longitude', '106.8807283');
    }
};
