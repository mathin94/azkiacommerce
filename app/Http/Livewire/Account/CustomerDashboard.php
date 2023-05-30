<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent as Component;

class CustomerDashboard extends Component
{
    public function render()
    {
        return view('livewire.account.customer-dashboard')
            ->layout('layouts.dashboard');
    }
}
