<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent;
use App\Models\Backoffice\Address;

class EditAddressForm extends BaseComponent
{
    public $label,
        $street,
        $recipientName,
        $recipientPhone,
        $subdistrictId,
        $postCode,
        $isMain;

    public $idToEdit;

    protected $rules = [
        'label'          => 'required',
        'street'         => 'required',
        'recipientName'  => 'required|max:100',
        'recipientPhone' => 'required|max:30',
        'subdistrictId'  => 'required|exists:App\Models\Backoffice\Subdistrict,id',
    ];

    public function saveAddress()
    {
        $this->validate();

        $address = $this->customer->addresses()->find($this->idToEdit);
        $address->update([
            'label'          => $this->label,
            'street'         => $this->street,
            'recipient_name' => $this->recipientName,
            'recipient_phone' => $this->recipientPhone,
            'subdistrict_id' => $this->subdistrictId,
            'post_code'      => $this->postCode,
            'is_main'        => $this->isMain,
        ]);

        $this->resetForm();

        $this->emit('address-saved');

        $this->emit('close-modal');
    }

    public function closeModal()
    {
        $this->emit('close-modal');
    }

    private function resetForm()
    {
        $this->label          = '';
        $this->street         = '';
        $this->recipientName  = '';
        $this->recipientPhone = '';
        $this->subdistrictId  = '';
        $this->postCode       = '';
        $this->isMain         = false;
    }

    public function mount($address)
    {
        $this->label          = $address?->label;
        $this->street         = $address?->street;
        $this->recipientName  = $address?->recipient_name;
        $this->recipientPhone = $address?->recipient_phone;
        $this->subdistrictId  = $address?->subdistrict_id;
        $this->postCode       = $address?->post_code;
        $this->isMain         = $address?->is_main;
    }

    public function render()
    {
        return view('livewire.account.edit-address-form');
    }
}
