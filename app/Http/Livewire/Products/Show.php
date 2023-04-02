<?php

namespace App\Http\Livewire\Products;

use App\Models\Shop\Product;
use Illuminate\Support\Arr;
use Livewire\Component;

class Show extends Component
{
    public $product, $colors, $sizes, $stock;

    public $quantity = 1, $variant, $sizeId, $colorId;

    public function updatedSizeId()
    {
        return $this->getVariant();
    }

    public function updatedColorId()
    {
        return $this->getVariant();
    }

    protected function getVariant()
    {
        if (empty($this->sizeId) && empty($this->colorId)) {
            return null;
        }

        $variant = $this->product
            ->variants
            ->where('color_id', $this->colorId)
            ->where('size_id', $this->sizeId)
            ->first();

        if ($variant) {
            $this->emit('variantChanged', [
                'variant' => $variant,
                'price' => 'Rp. ' . number_format($variant->price, 0, ',', '.'),
                'image' => $variant->media?->original_url
            ]);
        }

        $this->variant = $variant;
    }

    public function mount()
    {
        $slug      = request()->route('slug');

        $product = Product::with(['media', 'category', 'variants.color', 'variants.size'])
            ->where('slug', $slug)
            ->first();

        abort_if(!$product, 404);

        $colors = $product->variants->pluck('color.name', 'color_id')->toArray();
        $sizes  = $product->variants->pluck('size.name', 'size_id')->toArray();

        $this->product = $product;
        $this->colors = Arr::where($colors, fn ($val) => !is_null($val));
        $this->sizes = Arr::where($sizes, fn ($val) => !is_null($val));
    }

    public function render()
    {
        return view('livewire.products.show')
            ->layout('layouts.frontpage');
    }
}
