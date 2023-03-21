<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class AppIntroSlider extends Component
{
    public $sliders;

    public function mount()
    {
        $this->sliders = cache()->remember('sliders::intro::home', 60 * 60 * 24, function () {
            return Slider::activeIntroSliders()->get();
        });
    }
    public function render()
    {
        return view('livewire.home.app-intro-slider');
    }
}
