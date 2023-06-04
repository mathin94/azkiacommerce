<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent;

class OrderList extends BaseComponent
{
    public $tab, $detail;

    protected $queryString = ['tab'];

    public function show($id)
    {
        $this->detail = $this->customer->orders()
            ->with(['items.productVariant.media', 'shipping', 'payment'])
            ->findOrFail($id);

        $this->emit('open-modal');
    }

    public function render()
    {
        $orders = $this->customer->orders()->with('items.productVariant.media')->latest()->paginate(10);

        return view('livewire.account.order-list', [
            'orders' => $orders
        ])->layout('layouts.dashboard');
    }
}
