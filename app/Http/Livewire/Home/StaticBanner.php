<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class StaticBanner extends Component
{
    public $banners;

    public function mount()
    {
        $this->banners = Slider::cacheTags(['slider_static_'])->activeBanners()->get();
    }

    public function render()
    {
        return view('livewire.home.static-banner');
    }
}
