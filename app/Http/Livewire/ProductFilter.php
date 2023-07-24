<?php

namespace App\Http\Livewire;

use App\Models\Size;
use App\Models\Color;
use Livewire\Component;
use App\Models\Shop\Category;
use Illuminate\Database\Eloquent\Collection;

class ProductFilter extends Component
{
    public ?Collection $sizes;

    public ?Collection $colors;

    public ?Collection $categories;

    public $minimumPrice;

    public $maximumPrice;

    public $selectedCategories = [];

    public $selectedSizes = [];

    public $colorId;

    public function mount()
    {
        $this->sizes      = Size::has('productVariants')->orderBy('index', 'asc')->get();
        $this->colors     = Color::has('productVariants')->get();
        $this->categories = Category::orderBy('name', 'asc')->get();
    }

    public function updatedSelectedCategories($value)
    {
        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function updatedSelectedSizes($value)
    {
        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function updatedColorId($value)
    {
        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function updatedMinimumPrice($value)
    {
        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function updatedMaximumPrice($value)
    {
        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function clearFilter()
    {
        $this->reset([
            'selectedCategories',
            'selectedSizes',
            'colorId',
            'minimumPrice',
            'maximumPrice',
        ]);

        $this->emit('filterUpdated', $this->selectedCategories, $this->selectedSizes, $this->colorId, $this->minimumPrice, $this->maximumPrice);
    }

    public function render()
    {
        return view('livewire.product-filter');
    }
}
