<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class PartnerLocation extends Component
{
    public function render()
    {
        return view('livewire.pages.partner-location')
            ->layout('layouts.frontpage', [
                'title' => 'Peta Lokasi Mitra'
            ]);
    }
}
