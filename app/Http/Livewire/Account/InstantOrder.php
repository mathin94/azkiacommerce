<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Services\Shop\CheckLimitationService;

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

    public function checkout()
    {
        if (is_null($this->customer->mainAddress)) {
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

            $this->reloadCart();

            return;
        }

        $check_stock = $this->validateStock();

        if (is_array($check_stock)) {
            $alert = "<div class=\"white-popup\"> <h5>Tidak dapat checkout !</h5> <br>";
            $alert .= "<ol>";

            foreach ($check_stock as $error) {
                $alert .= "<li>- {$error}</li>";
            }

            $alert .= "</ol></div>";

            $this->emit('showAlert', [
                "alert" => $alert
            ]);

            $this->reloadCart();

            return;
        }

        return redirect()->route('cart.checkout');
    }

    private function validateStock(): bool | array
    {
        $errors = [];

        foreach ($this->cartItems as $item) {
            $variant = $item->productVariant;
            $resource = $variant->resource;

            if ($resource->stock < $item->quantity) {
                $errors[] = "Stok {$item->name} tidak mencukupi, stok tersedia saat ini: {$resource->stock}";
            } else {
                $service = new CheckLimitationService(
                    customer: $this->customer,
                    cart: $this->cart,
                    cartItem: $item,
                    variant: $variant,
                );

                if (!$service->execute()) {
                    $errors[] = "Produk {$item->name} melebihi batas pembelian untuk produk {$variant->product->name}, anda hanya dapat membeli {$service->limit} buah untuk keseluruhan total quantity variant ini";
                }
            }
        }

        return count($errors) > 0 ? $errors : true;
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
