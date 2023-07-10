<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ContactUs extends Component
{
    public $name, $email, $phone, $subject, $message;

    public function render()
    {
        return view('livewire.contact-us')
            ->layout('layouts.frontpage', [
                'title' => 'Hubungi Kami'
            ]);
    }
}
