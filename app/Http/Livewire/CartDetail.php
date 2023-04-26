<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CartDetail extends Component
{
    public $user, $cart, $cartItems;

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->user = auth()->guard('shop')->user();
        }
    }

    public function mount()
    {
        $cart = $this->user?->cart;
        $cartItems = $cart->items;

        $this->cart = $cart;
        $this->cartItems = $cartItems;
    }

    public function render()
    {
        return view('livewire.cart-detail')
            ->layout('layouts.frontpage');
    }
}
