<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class AppAdsSlider extends Component
{
    public $sliders;

    public function mount()
    {
        $this->sliders = cache()->remember('sliders::ads::home', 60 * 60 * 24, function () {
            return Slider::activeAdsSliders()->get();
        });
    }
    public function render()
    {
        return view('livewire.home.app-ads-slider');
    }
}
