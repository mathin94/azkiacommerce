<?php

namespace App\Http\Livewire\Products;

use App\Models\Shop\Product;
use Livewire\Component;

class Reviews extends Component
{
    public $product;
    public $perPage = 10;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.products.reviews', [
            'reviews' => $this->product->reviews()->latest()->paginate($this->perPage),
        ]);
    }
}
