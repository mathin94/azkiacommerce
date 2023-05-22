<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class AppAdsSlider extends Component
{
    public $sliders;

    public function mount()
    {
        $this->sliders = Slider::cacheTags(['slider_ads_'])
            ->activeAdsSliders()
            ->get();
    }
    public function render()
    {
        return view('livewire.home.app-ads-slider');
    }
}
