<?php

namespace App\Http\Livewire\Admin;

use App\Enums\OrderStatus;
use Livewire\Component;

class OrderTabFilter extends Component
{
    public $menus, $status = 'waitingPayment';

    protected $listeners = ['setStatus'];

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function mount()
    {
        $this->menus = [
            'waitingPayment' => 'Menunggu Pembayaran',
            'waitingDelivery' => 'Perlu Dikirim',
            'delivered' => 'Dikirim',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan',
        ];
    }

    public function render()
    {
        return view('livewire.admin.order-tab-filter');
    }
}
