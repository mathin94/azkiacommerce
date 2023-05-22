<?php

namespace App\Http\Livewire\Home;

use App\Models\Slider;
use Livewire\Component;

class AppIntroSlider extends Component
{
    public $sliders;

    public function mount()
    {
        $this->sliders = Slider::cacheTags(['slider_intro_'])
            ->activeIntroSliders()
            ->get();
    }
    public function render()
    {
        return view('livewire.home.app-intro-slider');
    }
}
