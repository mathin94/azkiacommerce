<?php

namespace App\Http\Livewire;

use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ShopResetPassword extends Component
{
    public $password;
    public $passwordConfirmation;
    public $token;
    public $email;

    protected $queryString = ['token', 'email'];

    protected $rules = [
        'password' => 'required|min:6|max:20',
        'passwordConfirmation' => 'required|same:password',
    ];

    public function submit()
    {
        $this->validate($this->rules);

        $service = new ResetPasswordService($this->email, $this->token, $this->password);

        if ($service->perform()) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. silahkan login menggunakan password baru');
        }

        Session::flash('error', 'Password gagal diubah. link salah atau token kadaluarsa.');
    }

    public function render()
    {
        return view('livewire.shop-reset-password')
            ->layout('layouts.frontpage');
    }
}
