<?php

namespace App\Http\Livewire;

use App\Models\Shop\ProductVariant;
use App\Services\Shop\AddToCartService;
use App\Services\Shop\ProductVariantPriceService;
use Livewire\Component;

class SearchProductVariant extends Component
{
    public $productVariantId, $selectedVariant, $price, $quantity, $stock;

    public $customer, $cart;

    protected $listeners = ['changeProductVariant', 'clearProductVariant'];

    public function changeProductVariant($data)
    {
        $id = $data['id'];

        $variant = ProductVariant::find($id);

        if ($variant) {
            $service = new ProductVariantPriceService($this->customer, $variant);
            $service->execute();

            $prices = $service->prices;
            $this->price = format_rupiah($prices['final_price'], true);
            $this->stock = $variant->stock;
            $this->selectedVariant = $variant;
            $this->emit('focusToQty');
        }
    }

    public function addToCart()
    {
        if ((int) $this->quantity < 1) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <p>Kuantitas Minimal 1</p>
                    </div>
                "
            ]);

            return;
        }

        $service = new AddToCartService(
            cart: $this->cart,
            variant: $this->selectedVariant,
            quantity: $this->quantity,
            product: null
        );

        if (!$service->execute()) {
            $alert = "<div class=\"white-popup\">";

            foreach ($service->errors as $error) {
                $alert .= "<p>{$error}</p>";
            }

            $alert .= "</div>";

            $this->emit('showAlert', [
                "alert" => $alert
            ]);

            return;
        }

        $this->clearProductVariant();

        $this->emit('reloadCart');
    }

    public function mount($customer, $cart)
    {
        $this->customer = $customer;
        $this->cart = $cart;
    }

    public function clearProductVariant()
    {
        $this->reset([
            'price', 'stock', 'quantity'
        ]);
    }

    public function render()
    {
        return view('livewire.search-product-variant');
    }
}
