<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $data = [
            'title' => 'Azkia Hijab'
        ];

        return view('livewire.pages.home', $data)
            ->layout('layouts.app');
    }
}
