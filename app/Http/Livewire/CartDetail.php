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

    public function updatingItemQuantities($quantity, $itemId)
    {
        $item = $this->cartItems->find((int) $itemId);

        if ($item) {
            $item->quantity = (int) $quantity;
            $item->save();
        }

        $this->emit('refreshComponent');

        $this->mount();
    }

    public function mount()
    {
        $cart = $this->user?->cart;

        if ($cart) {
            $cart->load(['items.productVariant' => ['media', 'resource.detail']]);
            $cartItems = $cart->items ?? [];

            $this->cart = $cart;

            $this->checkAllStock();

            foreach ($cartItems as $item) {
                $this->itemQuantities[$item->id] = $item->quantity;
            }

            $this->cartItems = $cartItems;

            $this->cart->recalculate();
        }
    }

    public function checkout()
    {
        if (is_null($this->user->mainAddress)) {
            $address_link = route('customer.addresses');
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Tidak dapat checkout !</h5>
                        <p>Anda belum mempunyai alamat pengiriman, harap mengisi alamat pengiriman agar dapat checkout</p>
                        <p><a href=\"$address_link\">Klik Disini Untuk Mengisi Alamat</a></p>
                    </div>
                "
            ]);

            return;
        }

        return redirect()->route('cart.checkout');
    }

    public function checkAllStock()
    {
        foreach ($this->cart->items as $item) {
            $product = $item->productVariant->resource;

            if ($product->stock < 1) {
                $item->delete();
            }
        }
    }

    public function render()
    {
        return view('livewire.cart-detail')
            ->layout('layouts.frontpage');
    }
}
