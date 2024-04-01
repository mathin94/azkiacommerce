<?php

namespace App\Http\Livewire;

use App\Models\Shop\Product;
use App\Models\Shop\ProductVariant;
use App\Services\Shop\CheckLimitationService;
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

    public function updatingItemQuantities($quantity, int $itemId)
    {
        $item = $this->cartItems->find($itemId);
        $variant = $item->productVariant;
        $product = $variant->product;

        $prev_quantity = $item->quantity;

        // if ($quantity != $prev_quantity && !$this->validLimitation($product, $variant->id, $quantity)) {
        //     $this->emit('showAlert', [
        //         "alert" => "
        //             <div class=\"white-popup\">
        //                 <h5>Tidak mengubah qty !</h5>
        //                 <p>Anda melebihi batas pembelian untuk produk ini</p>
        //             </div>
        //         "
        //     ]);

        //     $this->itemQuantities[$item->id] = $prev_quantity;

        //     $this->emit('refreshComponent');

        //     $this->mount();

        //     return;
        // }

        // $available_stock = $variant->resource->stock;

        // if ($quantity < $available_stock) {
        //     $this->emit('showAlert', [
        //         "alert" => "
        //             <div class=\"white-popup\">
        //                 <h5>Stock $variant->name tidak mencukupi !</h5>
        //                 <p>Stock Tersedia hanya ada {$available_stock}</p>
        //             </div>
        //         "
        //     ]);

        //     $this->itemQuantities[$item->id] = $available_stock;

        //     $this->emit('refreshComponent');

        //     $this->mount();

        //     return;
        // }

        if ($item) {
            $item->quantity = (int) $quantity;
            $item->save();
        }

        $this->emit('refreshComponent');

        $this->mount();
    }

    private function validLimitation(Product $product, int $variantId, int $quantity): bool
    {
        $service = new CheckLimitationService($this->cart, $product, $quantity, $variantId);

        return $service->execute();
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
                $service = new CheckLimitationService($this->cart, $variant->product, $item->quantity, $variant->id);

                if (!$service->execute()) {
                    $errors[] = "Produk {$item->name} melebihi batas pembelian untuk produk {$variant->product->name}, anda hanya dapat membeli {$service->limit} buah untuk keseluruhan total quantity produk ini";
                }
            }
        }

        return count($errors) > 0 ? $errors : true;
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
