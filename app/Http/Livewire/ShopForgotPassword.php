<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jobs\ForgotPasswordJob;
use Illuminate\Support\Facades\Session;

class ShopForgotPassword extends Component
{
    public $email;

    public function submit()
    {
        $this->validate([
            'email' => 'required|email'
        ]);

        ForgotPasswordJob::dispatch($this->email);

        Session::flash('success', 'Link reset password sedang di kirim ke email kamu.');

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.shop-forgot-password')->layout('layouts.frontpage');
    }
}
