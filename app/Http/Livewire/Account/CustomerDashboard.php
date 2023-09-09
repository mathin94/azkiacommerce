<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent as Component;
use App\Services\CustomerPointCalculatorService;

class CustomerDashboard extends Component
{
    protected $listeners = ['loadPoint'];

    public $point_label = '<i class="fa fa-spinner fa-spin"></i>';
    public $amount_label = '<i class="fa fa-spinner fa-spin"></i>';

    public function loadPoint()
    {
        $service = new CustomerPointCalculatorService($this->customer, now()->format('Y'));
        $service->calculate();

        $this->point_label  = number_format($service->total_point, 2, ',', '.');
        $this->amount_label = 'Rp. ' . number_format($service->total_amount, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.account.customer-dashboard')
            ->layout('layouts.dashboard');
    }
}
