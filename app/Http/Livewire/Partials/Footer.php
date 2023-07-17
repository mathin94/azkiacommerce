<?php

namespace App\Http\Livewire\Partials;

use App\Models\Page;
use Livewire\Component;

class Footer extends Component
{
    public $pages;

    public function mount()
    {
        $this->pages = cache()->remember(Page::ACTIVE_CACHE_KEY, 24 * 60 * 60, function () {
            return Page::active()->get();
        });
    }

    public function render()
    {
        return view('livewire.partials.footer');
    }
}
