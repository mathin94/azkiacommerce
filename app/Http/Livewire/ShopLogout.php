<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShopLogout extends Component
{
    public function logout()
    {
        auth()->guard('shop')->logout();
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.shop-logout');
    }
}
