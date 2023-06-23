<?php

namespace App\Http\Livewire;

use App\Models\Shop\Order;
use Livewire\Component;

class ShippingLabel extends Component
{
    public $order;
    public $orderItems;

    public function mount($id)
    {
        $this->order = Order::with([
            'shipping', 'customer', 'items.productVariant'
        ])->find($id);

        $this->orderItems = $this->order->items;
    }

    public function render()
    {
        return view('livewire.shipping-label')
            ->layout('layouts.blank', [
                'title' => 'Label Pengiriman'
            ]);
    }
}
