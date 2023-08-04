<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Shop\UploadPaymentProofService;

class UploadPayment extends Component
{
    use WithFileUploads;

    public $order,
        $bankAccounts,
        $customer,
        $bankAccountId,
        $selectedBankAccount,
        $file;

    public function mount($order)
    {
        $this->order = $order;

        $this->customer = auth()->guard('shop')->user();

        $this->bankAccounts = cache()->remember('all_bank_account', 24 * 60 * 60, function () {
            return BankAccount::with('bank')->get();
        });
    }

    public function openPaymentModal()
    {
        $this->reset(['bankAccountId', 'file']);
        $this->emit('open-payment-modal');
    }

    public function updatedBankAccountId()
    {
        $this->selectedBankAccount = $this->bankAccounts->find($this->bankAccountId);
    }

    public function savePayment()
    {
        $this->validate([
            'bankAccountId' => 'required|exists:App\Models\BankAccount,id',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'bankAccountId.required' => 'Nomor rekening tujuan harus di isi',
            'bankAccountId.exists' => 'Nomor rekening tujuan tidak ditemukan',
        ]);

        $service = new UploadPaymentProofService(
            order: $this->order,
            bankAccount: $this->selectedBankAccount,
            file: $this->file
        );

        $service->perform();

        $this->reset(['bankAccountId', 'file']);

        $this->emit('close-payment-modal');

        sleep(2);

        $this->emit('showAlert', [
            "alert" => "
                        <div class=\"white-popup\">
                            <h5>Berhasil</h5>
                            <p>Bukti pembayaran berhasil di upload</p>
                        </div>
                    "
        ]);
    }

    public function render()
    {
        return view('livewire.upload-payment');
    }
}
