<?php

namespace App\Http\Livewire\Partials;

use App\Helpers\Menu;
use Livewire\Component;

class Navbar extends Component
{
    public $navbar_menu;
    public $topbar_menu;

    public function mount()
    {
        $menu = new Menu;

        $this->navbar_menu = $menu->navbar()
            ->render();
        $this->topbar_menu = $menu->topbar()
            ->render();
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
