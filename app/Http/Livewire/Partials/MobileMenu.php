<?php

namespace App\Http\Livewire\Partials;

use App\Helpers\Menu;
use Livewire\Component;

class MobileMenu extends Component
{
    public $mobile_menus;

    public function mount()
    {
        $menu = new Menu;

        $this->mobile_menus = $menu->mobile()
            ->render();
    }

    public function render()
    {
        return view('livewire.partials.mobile-menu');
    }
}
