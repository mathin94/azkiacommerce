<?php

namespace App\Http\Livewire\Admin\Orders;

use Livewire\Component;

class TopFilter extends Component
{
    public $menus, $status, $filters;

    protected $listeners = ['setStatus'];

    protected $queryString = ['filters'];

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

        $this->status = $this->filters['status']['value'] ?? 'waitingPayment';
    }

    public function render()
    {
        return view('livewire.admin.orders.top-filter');
    }
}
