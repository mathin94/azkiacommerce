<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent;

class ChangePasswordForm extends BaseComponent
{
    public $currentPassword, $newPassword, $confirmPassword;

    protected $rules = [
        'currentPassword' => 'required',
        'newPassword'     => 'required|min:5',
        'confirmPassword' => 'required|same:newPassword'
    ];

    public function changePassword()
    {
        $this->validate();

        if (!password_verify($this->currentPassword, $this->customer->password)) {
            $this->addError('currentPassword', 'Password anda salah');
            return;
        }

        $data = [
            'password' => bcrypt($this->newPassword)
        ];

        $this->customer->update($data);
        $this->customer->resource->update($data);

        $this->emit('showAlert', [
            "alert" => "
                    <div class=\"white-popup\">
                        <h5>Sukses !</h5>
                        <p>Kata Sandi Berhasil Di update</p>
                    </div>
                "
        ]);

        $this->resetForm();

        $this->resetErrorBag();
    }

    private function resetForm()
    {
        $this->currentPassword  = '';
        $this->newPassword      = '';
        $this->confirmPassword  = '';
    }

    public function render()
    {
        return view('livewire.account.change-password-form');
    }
}
