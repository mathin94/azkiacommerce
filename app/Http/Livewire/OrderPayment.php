<?php

namespace App\Http\Livewire;

use App\Models\BankAccount;
use App\Models\Shop\Order;
use Livewire\Component;

class OrderPayment extends Component
{
    public $order, $bankAccounts;

    public function mount()
    {
        $uuid = request()->payment_uuid;

        $this->bankAccounts = BankAccount::with('bank')->get();

        $this->order = Order::with(['items', 'shipping', 'payment'])
            ->whereHas('payment', function ($q) use ($uuid) {
                $q->where('shop_order_payments.uuid', $uuid);
            })
            ->first();

        if (!$this->order) {
            redirect(route('home'));
        }
    }

    public function render()
    {
        return view('livewire.order-payment')
            ->layout('layouts.frontpage');
    }
}
