<?php

namespace App\Http\Livewire\Home;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Testimonials extends Component
{
    public $testimonials;

    public function mount()
    {
        $this->testimonials = Testimonial::cacheTags(['testimonials_home'])->with('media')
            ->whereActive(true)
            ->get();
    }

    public function render()
    {
        return view('livewire.home.testimonials');
    }
}
