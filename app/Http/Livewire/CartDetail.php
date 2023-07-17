<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CartDetail extends Component
{
    public $user, $cart, $cartItems, $itemQuantities = [];

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->user = auth()->guard('shop')->user();
        }
    }

    public function deleteItem($id)
    {
        $item = $this->cartItems->find($id);

        if ($item) {
            # Delete Record
            $item->delete();

            # Refresh Cart
            $this->cart->refresh();

            # Reload Items
            $this->cartItems = $this->cart->items;

            # Recalculate Cart
            $this->cart->recalculate();

            $this->emit('refreshComponent');
        }
    }

    public function updatingItemQuantities(Int $quantity, Int $itemId)
    {
        $item = $this->cartItems->find($itemId);

        if ($item) {
            $item->quantity = $quantity;
            $item->save();
        }

        $this->emit('refreshComponent');

        $this->mount();
    }

    public function mount()
    {
        $cart = $this->user?->cart;

        if ($cart) {
            $cart->load(['items.productVariant.media']);
            $cartItems = $cart->items ?? [];

            $this->cart = $cart;

            foreach ($cartItems as $item) {
                $this->itemQuantities[$item->id] = $item->quantity;
            }

            $this->cartItems = $cartItems;

            $this->cart->recalculate();
        }
    }

    public function render()
    {
        return view('livewire.cart-detail')
            ->layout('layouts.frontpage');
    }
}
