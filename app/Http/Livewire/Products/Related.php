<?php

namespace App\Http\Livewire\Products;

use App\Models\Shop\Product;
use Livewire\Component;

class Related extends Component
{
    public $relatedProducts;

    public function mount($product)
    {
        $this->relatedProducts = Product::with([
            'media',
        ])
            ->where('shop_product_category_id', $product->shop_product_category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.products.related');
    }
}
