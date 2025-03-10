<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Models\Shop\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search, $sortBy = 'created_at';

    protected $selectedCategories = [];

    protected $selectedSizes = [];

    protected $colorId, $minimumPrice, $maximumPrice;

    protected $queryString = ['search', 'sortBy'];

    protected $listeners = ['filterUpdated', 'refreshProducts' => 'getProducts'];

    public function filterUpdated($selectedCategories, $selectedSizes, $colorId, $minimumPrice, $maximumPrice)
    {
        $this->selectedCategories = $selectedCategories;
        $this->selectedSizes      = $selectedSizes;
        $this->colorId            = $colorId;
        $this->minimumPrice       = $minimumPrice;
        $this->maximumPrice       = $maximumPrice;
    }

    public function getProducts()
    {
        $products = Product::with(['media', 'activeDiscount', 'wishlists' => function ($q) {
            $q->dontCache()->where('shop_customer_id', auth()->guard('shop')->id());
        }])
            ->visible()
            ->published();

        if ($this->search) {
            $products->where('name', 'like', "%{$this->search}%");
        }

        if ($this->selectedCategories) {
            $products->byCategories($this->selectedCategories);
        }

        if ($this->selectedSizes) {
            $products->bySizes($this->selectedSizes);
        }

        if ($this->colorId) {
            $products->byColor($this->colorId);
        }

        if ($this->minimumPrice) {
            $products->minPrice($this->minimumPrice);
        }

        if ($this->maximumPrice) {
            $products->maxPrice($this->maximumPrice);
        }

        return $products->sortByValue($this->sortBy);
    }

    public function render()
    {
        $products = $this->getProducts();

        $title = 'Produk';

        if ($this->search) {
            $title = "Hasil Pencarian : {$this->search}";
        }

        return view('livewire.products.index', [
            'products' => $products->paginate(12)
        ])->layout('layouts.frontpage', [
            'title' => $title
        ]);
    }
}
