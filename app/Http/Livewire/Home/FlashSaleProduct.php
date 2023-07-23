<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\Shop\Product;

class FlashSaleProduct extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::with(['media', 'activeDiscount', 'wishlists' => function ($q) {
            $q->dontCache()->where('shop_customer_id', auth()->guard('shop')->id());
        }])
            ->published()
            ->visible()
            ->flashSale()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.home.flash-sale-product');
    }
}
