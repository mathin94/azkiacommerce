<?php

namespace App\Http\Livewire\Pages;

use App\Enums\SliderType;
use App\Models\Shop\Product;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Home extends Component
{
    public string $title;

    public ?Collection $introSliders;
    public ?Collection $adsSliders;
    public string $tab = 'featured';

    public $featuredProducts;

    public $onSaleProducts;

    public $topRatedProducts;

    public function render()
    {
        $products = Product::with('media')
            ->visible()
            ->published();

        $this->featuredProducts = $products->featured()->limit(10)->get();
        $this->onSaleProducts = $products->onSale()->limit(10)->get();
        $this->topRatedProducts = $products->orderBy('total_score', 'desc')->limit(10)->get();

        return view('livewire.pages.home')
            ->layout('layouts.frontpage');
    }
}
