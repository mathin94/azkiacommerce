<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\Shop\Product;

class LatestProduct extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::visible()
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.home.latest-product');
    }
}
