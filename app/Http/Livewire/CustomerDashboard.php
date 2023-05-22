<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CustomerDashboard extends Component
{
    public function render()
    {
        return view('livewire.customer-dashboard')
            ->layout('layouts.dashboard');
    }
}
