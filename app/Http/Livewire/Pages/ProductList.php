<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class ProductList extends Component
{
    public function render()
    {
        return view('livewire.pages.product-list')
            ->layout('layouts.frontpage');
    }
}
