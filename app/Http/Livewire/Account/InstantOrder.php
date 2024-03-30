<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class InstantOrder extends Component
{
    public $customer, $cart, $cartItems, $itemQuantities = [];

    protected $listeners = ['reloadCart'];

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->customer = auth()->guard('shop')->user();
        }
    }

    public function deleteItem($id)
    {
        $item = $this->cartItems->find($id);

        if ($item) {
            $item->delete();

            $this->reloadCart();
        }
    }

    public function updatingItemQuantities($quantity, $itemId)
    {
        $item = $this->cartItems->find((int) $itemId);

        if ($item) {
            $item->quantity = (int) $quantity;
            $item->save();
        }

        $this->reloadCart();
    }

    public function reloadCart()
    {
        $cart = $this->customer->cart?->refresh() ?? $this->customer->createCart();

        $cartItems = $cart->items ?? [];

        $this->cart = $cart;

        foreach ($cartItems as $item) {
            $this->itemQuantities[$item->id] = $item->quantity;
        }

        $this->cartItems = $cartItems;

        $this->cart->recalculate();

        $this->emit('refreshComponent');
    }

    public function mount()
    {
        $this->reloadCart();
    }

    public function render()
    {
        return view('livewire.account.instant-order')
            ->layout('layouts.dashboard');
    }
}
