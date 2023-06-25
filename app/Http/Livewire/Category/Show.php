<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Shop\Product;
use App\Models\Shop\Category;

class Show extends Component
{
    public $category, $sortBy = 'created_at';

    protected $queryString = ['sortBy'];

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $products = $this->category->products()
            ->with(['media', 'activeDiscount'])
            ->visible()
            ->published()
            ->sortByValue($this->sortBy);

        return view('livewire.category.show', [
            'products' => $products->paginate(10)
        ])
            ->layout('layouts.frontpage');
    }
}
