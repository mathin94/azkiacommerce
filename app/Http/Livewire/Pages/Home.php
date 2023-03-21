<?php

namespace App\Http\Livewire\Pages;

use App\Enums\SliderType;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Home extends Component
{
    public string $title;

    public Collection | null $introSliders;
    public Collection | null $adsSliders;

    public function mount()
    {
        $this->title = "Azkia Hijab - Syar'i and Fashionable";
    }

    public function render()
    {
        return view('livewire.pages.home')
            ->layout('layouts.frontpage');
    }
}
