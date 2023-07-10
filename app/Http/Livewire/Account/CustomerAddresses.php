<?php

namespace App\Http\Livewire\Account;

use Livewire\WithPagination;
use App\Http\Livewire\BaseComponent;

class CustomerAddresses extends BaseComponent
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $id,
        $label,
        $street,
        $recipientName,
        $recipientPhone,
        $subdistrictId,
        $postCode,
        $isMain,
        $formTitle;

    protected $listeners = ['changeSubdistrict'];

    protected $rules = [
        'label'          => 'required',
        'street'         => 'required|min:20|max:500',
        'recipientName'  => 'required|min:3|max:80',
        'recipientPhone' => 'required|min:6|max:20',
        'subdistrictId'  => 'required|gt:0',
    ];

    protected $messages = [
        'label.required'         => 'Label alamat wajib diisi',
        'street.required'        => 'Alamat lengkap wajib diisi',
        'street.min'             => 'Alamat lengkap minimal 20 karakter',
        'street.max'             => 'Alamat lengkap maksimal 500 karakter',
        'subdistrictId.required' => 'Kecamatan wajib diisi',
        'subdistrictId.gt'       => 'Kecamatan wajib diisi',
    ];

    public function changeSubdistrict($data)
    {
        $this->subdistrictId = (int) $data['id'];
    }

    public function openAddDialog()
    {
        $this->formTitle = 'Tambah Alamat';
        $this->resetInputField();
        $this->dispatchBrowserEvent('open-dialog');
    }

    public function submit()
    {
        $this->validate();

        $address = $this->customer->addresses()->firstOrNew(['id' => $this->id]);

        if (empty($this->subdistrictId)) {
            $this->addError('subdistrictId', 'Kecamatan wajib diisi');
        }

        $address->fill([
            'label'           => $this->label,
            'address_line'    => $this->street,
            'recipient_name'  => $this->recipientName,
            'recipient_phone' => $this->recipientPhone,
            'subdistrict_id'  => $this->subdistrictId,
            'post_code'       => $this->postCode,
            'is_main'         => $this->isMain,
        ]);

        if ($this->customer->addresses()->count() == 0) {
            $address->is_main = true;
        }

        $address->save();

        if ($address->is_main) {
            $this->customer->resource->update(['subdistrict_id', $address->subdistrict_id]);
        }

        $this->resetInputField();

        $this->emit('close-edit-dialog');

        session()->flash('message', 'Address added successfully');
    }

    public function edit($id)
    {
        $this->formTitle = 'Edit Alamat';

        $address = $this->customer->addresses()->find($id);

        $this->id             = $address->id;
        $this->label          = $address->label;
        $this->street         = $address->address_line;
        $this->recipientName  = $address->recipient_name;
        $this->recipientPhone = $address->recipient_phone;
        $this->subdistrictId  = $address->subdistrict_id;
        $this->postCode       = $address->post_code;
        $this->isMain         = $address->is_main;

        $this->emit('subdistrictSelected', $address->subdistrict_id);
    }

    public function openDeleteDialog($id)
    {
        $this->id = $id;
        $this->dispatchBrowserEvent('open-delete-dialog');
    }

    public function delete()
    {
        $address = $this->customer->addresses()->find($this->id);

        if ($address->is_main) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal Menghapus Alamat !</h5>
                        <p>Tidak dapat menghapus alamat utama</p>
                    </div>
                "
            ]);

            return;
        }

        $address->delete();

        $this->emit('showAlert', [
            "alert" => "
                    <div class=\"white-popup\">
                        <h5>Sukses !</h5>
                        <p>Alamat berhasil dihapus</p>
                    </div>
                "
        ]);

        $this->dispatchBrowserEvent('close-delete-dialog');
    }

    private function resetInputField()
    {
        $this->id             = null;
        $this->label          = null;
        $this->street         = null;
        $this->recipientName  = null;
        $this->recipientPhone = null;
        $this->subdistrictId  = null;
        $this->postCode       = null;
        $this->isMain         = false;

        $this->resetValidation();
        $this->emit('subdistrictSelected', null);
    }

    public function render()
    {
        return view('livewire.account.customer-addresses', [
            'addresses' => $this->customer->addresses()
                ->orderBy('created_at', 'desc')
                ->with('subdistrict')
                ->paginate(5),
        ])->layout('layouts.dashboard', [
            'title' => 'Daftar Alamat'
        ]);
    }
}
