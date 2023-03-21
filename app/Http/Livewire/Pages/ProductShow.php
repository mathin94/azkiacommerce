<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class ProductShow extends Component
{
    public function render()
    {
        return view('livewire.pages.product-show')
            ->layout('layouts.frontpage');
    }
}
