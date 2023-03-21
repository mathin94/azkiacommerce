<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = \App\Models\Blog\Post::all();

        foreach ($data as $item) {
            $item->published_at = now();
            $item->save();
            sleep(3);
        }
    }
}
