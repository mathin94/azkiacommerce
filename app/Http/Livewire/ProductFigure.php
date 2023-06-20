<?php

namespace App\Http\Livewire;

use App\Models\Shop\Product;
use Livewire\Component;

class ProductFigure extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.product-figure');
    }
}
