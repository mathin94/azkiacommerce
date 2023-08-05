<?php

namespace App\Http\Livewire\Partials;

use App\Models\Page;
use Livewire\Component;
use App\Models\Shop\Category;

class Footer extends Component
{
    public $pages, $categories;

    public function mount()
    {
        $this->categories = Category::orderBy('name', 'asc')->get();
        $this->pages = Page::active()->get();
    }

    public function openLogoutModal()
    {
        $this->emit('open-logout-dialog');
    }

    public function render()
    {
        return view('livewire.partials.footer');
    }
}
