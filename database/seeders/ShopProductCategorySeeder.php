<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Aksesoris'
            ],
            [
                'name' => 'Khimar'
            ],
            [
                'name' => 'Koko'
            ],
            [
                'name' => 'Daily Hijab'
            ],
            [
                'name' => 'Dress'
            ],
            [
                'name' => 'Hijab Anak'
            ],
            [
                'name' => 'Hijab Bayi'
            ],
            [
                'name' => 'Hijab Sekolah'
            ],
        ];

        foreach ($categories as $key => $value) {
            $name = $value['name'];

            \App\Models\Shop\Category::updateOrCreate(
                ['slug' => \Str::slug($name)],
                ['name' => $name]
            );

            $this->command->info("Category {$name} Stored");
        }
    }
}
