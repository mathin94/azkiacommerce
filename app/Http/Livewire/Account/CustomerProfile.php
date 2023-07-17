<?php

namespace App\Http\Livewire\Account;

use App\Enums\GenderEnum;
use App\Http\Livewire\BaseComponent;
use App\Services\Shop\UpdateCustomerService;

class CustomerProfile extends BaseComponent
{
    public $genderOptions;

    public $fullName, $email, $phone, $gender;

    public function mount()
    {
        $this->genderOptions = GenderEnum::asSelectArray();
        $this->fullName      = $this->customer->name;
        $this->email         = $this->customer->email;
        $this->phone         = $this->customer->phone;
        $this->gender        = $this->customer->gender->value;
    }

    public function updateProfile()
    {
        $this->validate([
            'fullName'  => 'required',
            'email'     => 'required|email|unique:shop_customers,email,' . $this->customer->id,
            'phone'     => 'required',
            'gender'    => 'required|in:L,P',
        ]);

        $service = new UpdateCustomerService($this->customer);
        $update = $service->updateProfile($this->fullName, $this->email, $this->phone, $this->gender);

        if ($update) {
            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <h5>Update Profil Sukses !</h5>
                            <p>Profil anda berhasil di perbarui</p>
                        </div>
                    "
            ]);
        } else {
            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <h5>Update Profil Gagal !</h5>
                            <p>Profil anda gagal di perbarui</p>
                        </div>
                    "
            ]);
        }
    }

    public function render()
    {
        return view('livewire.account.customer-profile')
            ->layout('layouts.dashboard', [
                'title' => 'Profil Pengguna'
            ]);
    }
}
