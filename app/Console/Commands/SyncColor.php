<?php

namespace App\Console\Commands;

use App\Models\Color;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\Backoffice\Product;
use App\Models\Backoffice\Category;
use Illuminate\Support\Arr;

class SyncColor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-color';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Color from Product Backoffice';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sizes      = collect([]);
        $colors     = collect([]);

        $allsize_products = [
            'bilqis',
            'abaya musamma bordir',
            'abaya musamma zipper',
            'abaya musamma',
            'khadijah',
            'masker kaos',
            'janna',
            'safira',
            'sahla',
            'anjani',
            'kamila',
            'mukena kirania',
            'jessica twotone',
            'bandana',
            'ciput polos',
            'ciput serut',
            'jessica twotone',
            'twotone',
            'masker kaos', 'masker manis',
            'ciput jessica polos',
            'jessica polos',
            'fatimah',
            'mukena qirania',
            'nikob noor',
            'bros azkia hijab',
        ];

        $categories = Category::where('name', '!=', 'Lain - Lain')
            ->where('name', 'not like', '%handsock%')
            ->where('name', 'not like', '%cadar%')
            ->where('name', 'not like', 'gb %')
            ->where('name', 'not like', '%square%')
            ->where('name', 'not like', '%mukena%')
            ->where('name', 'not like', '%bundling%')
            ->where('name', 'not like', '%sekolah%')
            ->where('name', 'not like', '%masker spanda%')
            ->where('name', 'not like', '%kaos kaki%')
            ->where('name', 'not like', '%jersy serut%')
            ->where('name', 'not like', '%0%')
            ->get();

        foreach ($categories as $category) {
            $category_name = Str::lower($category->name);

            $products = Product::where('category_id', '=', $category->id)
                ->whereRaw("name not like '%grade b%'")
                ->whereRaw("name not like '%gb%'")
                ->whereRaw("name not like '%bundling%'")
                ->whereRaw("name not like '%bandling%'")
                ->whereRaw("name not like '%pe'")
                ->orderBy('name', 'asc')
                ->get();

            foreach ($products as $product) {
                $product_name = Str::lower($product->name);

                if (Str::of($product_name)->contains($allsize_products)) {
                    $color = '';
                    $name = $product_name;

                    foreach ($allsize_products as $key => $value) {
                        $name = Str::of($name)->replace($value, '')
                            ->replace(' all', '')
                            ->replace('ciput polos', '')
                            ->replace('ciput serut', '')
                            ->replace('ciput', '')
                            ->trim()->toString();
                    }

                    $color = $name;

                    $color = $this->normalizeColor($color);

                    // if ($color == 'polos millo') {
                    //     $this->info($category_name);
                    //     $this->info($name);
                    //     $this->info($size);
                    //     $this->info($color);
                    //     dd($product->name);
                    // }

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }
                } else if (Str::of($product_name)->contains('twoface')) {
                    $name = Str::of($product_name)
                        ->replace('twoface ', '')
                        ->trim()
                        ->toString();

                    $size = Str::of($name)->before(' ')->trim()->toString();
                    $color = Str::of($name)->after($size)->trim()->toString();

                    $color = $this->normalizeColor($color);

                    if (!$sizes->contains($size)) {
                        $sizes->push($size);
                    }

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }
                } else if (Str::of($product_name)->contains(['bersy', 'jersut']) && ($category_name == 'bersy' || $category_name == '013 bersy' || $category_name == '014 jersut')) {
                    $color = '';
                    $name = $product_name;

                    foreach ($allsize_products as $key => $value) {
                        $name = Str::of($name)->replace($value, '')
                            ->replace(' all', '')
                            ->replace('bersy ', '')
                            ->replace('jersut ', '')
                            ->trim()->toString();
                    }

                    $color = $name;

                    $color = $this->normalizeColor($color);

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }

                    if ($color == 'bersy biru') {
                        $this->info($category_name);
                        $this->info($name);
                        $this->info($color);
                        dd($product->name);
                    }
                } else if (Str::of($category_name)->contains('new hulya')) {
                    $name = Str::of($product_name)
                        ->replace('new hulya rd', '')
                        ->replace('new hulya ', '')
                        ->trim()
                        ->toString();

                    $size = Str::of($name)->afterLast(' ')->trim()->toString();
                    $color = Str::of($name)->before(' ' . $size)->trim()->toString();
                    $color = $this->normalizeColor($color);

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }

                    if (!$sizes->contains($size)) {
                        $sizes->push($size);
                    }
                } else if (Str::of($category_name)->contains('new jersut')) {
                    $name = Str::of($product_name)
                        ->replace('jersut ', '')
                        ->replace('.', '')
                        ->trim()
                        ->toString();

                    $size = Str::of($name)->afterLast(' ')->trim()->toString();
                    $color = Str::of($name)->before(' ' . $size)->trim()->toString();

                    $color = $this->normalizeColor($color);

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }

                    if (!$sizes->contains($size)) {
                        $sizes->push($size);
                    }
                } else {
                    $name = Str::of($product->name)
                        ->lower()
                        ->replace('french khimar alya rd ', '')
                        ->replace('khimar ghina rd ', '')
                        ->replace('mukena terusan ', '')
                        ->replace('medina kids ', '')
                        ->replace('l pendek ', '')
                        ->replace('l panjang ', '')
                        ->replace('gb ', '')
                        ->replace('alesya antem ', '')
                        ->replace('jersut ', '')
                        ->replace('new hulya ', '')
                        ->replace('non pet ', '')
                        ->replace('pet ', '')
                        ->replace('koko zaid', '')
                        ->replace('alesya ', '')
                        ->replace('alesya ', '')
                        ->replace('dress set maudy ', '')
                        ->replace('agnia flora ', '')
                        ->replace('dress fahira ', '')
                        ->replace('home dress haura ', '')
                        ->replace('.', '')
                        ->replace('wardah', '')
                        ->replace('mukena terusan  ', '')
                        ->replace('mukena ', '')
                        ->replace('fathan ', '')
                        ->replace('muqena ', '')
                        ->replace('rosda ', '')
                        ->replace('medina ', '')
                        ->replace('new rd sg4 cerruty ', '')
                        ->replace('segi4 cerruty rd', '')
                        ->replace('segi4 cerruty', '')
                        ->replace('segi4 ', '')
                        ->replace('gamus muzak ', '')
                        ->replace('bersy ', '')
                        ->replace('syilfa ', '')
                        ->replace('ryzan ', '')
                        ->replace($category_name, '')
                        ->trim()
                        ->toString();

                    $size = Str::afterLast($name, ' ');
                    $color = Str::of($name)->replaceLast($size, '')->trim()->toString();
                    $size = $size == '4q' ? '4' : $size;

                    $color = $this->normalizeColor($color);

                    if (!$colors->contains($color) && !empty($color)) {
                        $colors->push($color);
                    }

                    if (!$sizes->contains($size) && !empty($size)) {
                        $sizes->push($size);
                    }

                    if ($color == 'ryzan black') {
                        $this->info($category_name);
                        $this->info($name);
                        $this->info($size);
                        $this->info($color);
                        dd($product->name);
                    }
                }
            }
        }

        $this->storeColor($colors);
        $this->storeSize($sizes);
    }

    public function storeColor($colors)
    {
        foreach ($colors as $key => $value) {
            $code = \Str::slug($value);
            $name = \Str::upper($value);

            \App\Models\Color::updateOrCreate(
                ['code' => $code],
                ['name' => $name],
            );

            $this->info("color {$name} stored");
        }
    }

    public function storeSize($sizes)
    {
        foreach ($sizes as $key => $value) {
            $value = $value == 'all' ? 'all size' : $value;

            $code = \Str::slug($value);
            $name = \Str::upper($value);

            \App\Models\Size::updateOrCreate(
                ['code' => $code],
                ['name' => $name],
            );

            $this->info("size {$name} stored");
        }
    }

    public function normalizeColor($color)
    {
        $color = trim(preg_replace('/\d+/', '', $color));

        $color = $color == 'softb rown' ? 'soft brown' : $color;
        $color = $color == 'abumuda' ? 'abu muda' : $color;
        $color = $color == 'peachpoof' ? 'peach poof' : $color;
        $color = $color == 'babypink' ? 'baby pink' : $color;
        $color = $color == 'babyblue' ? 'baby blue' : $color;
        $color = $color == 'abu m fanta' ? 'abu fanta' : $color;
        $color = $color == 'hijaufuji' ? 'hijau fuji' : $color;
        $color = $color == 'moca' ? 'mocca' : $color;
        $color = $color == 'llac' ? 'lilac' : $color;
        $color = $color == 'softpink' ? 'soft pink' : $color;
        $color = $color == "d'pink" ? 'dustypink' : $color;
        $color = $color == "d'ungu" ? 'dustyungu' : $color;

        $color = Str::of($color)
            ->replace('babpink', 'babypink')
            ->replace('babyblue', 'baby blue')
            ->replace('babypink', 'baby pink')
            ->replace('elektrik', 'electric')
            ->replace('electrik', 'electric')
            ->toString();

        return $color;
    }
}
