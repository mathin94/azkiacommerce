<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class StaticBanner extends Component
{
    public $banners;

    public function mount()
    {
        $this->banners = cache()->remember('home::slider::static', 60 * 60 * 24, function () {
            return Slider::activeBanners()->get();
        });
    }

    public function render()
    {
        return view('livewire.home.static-banner');
    }
}
