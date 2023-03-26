<?php

namespace App\Http\Livewire\Products;

use App\Models\Color;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\Size;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $categories,
        $sizes,
        $colors;

    public $search;

    protected $queryString = ['search'];

    public function mount()
    {
        $ttl = 24 * 60 * 60;

        $this->categories = Cache::remember(Category::ALL_CATEGORY_CACHE, $ttl, function () {
            return Category::orderBy('name', 'asc')->get();
        });

        $this->sizes = Cache::remember(Size::ALL_CACHE_KEY, $ttl, function () {
            return Size::orderBy('code', 'asc')->get();
        });

        $this->colors = Cache::remember(Color::ALL_CACHE_KEY, $ttl, function () {
            return Color::orderBy('name', 'asc')->get();
        });
    }

    public function render()
    {
        $products = Product::with(['media']);

        if ($this->search) {
            $products->where('name', 'like', "%{$this->search}%");
        }

        return view('livewire.products.index', [
            'products' => $products->paginate(10)
        ])
            ->layoutData([
                'title' => 'Cari Produk'
            ])
            ->layout('layouts.frontpage');
    }
}
