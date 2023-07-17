<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\Shop\Product;

class ProductSlider extends Component
{
    public $featuredProducts;

    public $onSaleProducts;

    public $topRatedProducts;

    public function mount()
    {
        $this->featuredProducts = Product::with(['media', 'activeDiscount', 'wishlists' => function ($q) {
            $q->dontCache()->where('shop_customer_id', auth()->guard('shop')->id());
        }])
            ->published()
            ->visible()
            ->featured()
            ->limit(10)->get();
        $this->onSaleProducts = Product::with(['media', 'activeDiscount', 'wishlists' => function ($q) {
            $q->dontCache()->where('shop_customer_id', auth()->guard('shop')->id());
        }])
            ->published()
            ->visible()
            ->onSale()
            ->limit(10)->get();
        $this->topRatedProducts = Product::with(['media', 'activeDiscount', 'wishlists' => function ($q) {
            $q->dontCache()->where('shop_customer_id', auth()->guard('shop')->id());
        }])
            ->published()
            ->visible()
            ->orderBy('total_score', 'desc')->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.home.product-slider');
    }
}
