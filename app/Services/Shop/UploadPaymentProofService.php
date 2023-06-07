<?php

namespace App\Services\Shop;

use App\Enums\OrderStatus;
use App\Models\BankAccount;
use App\Models\Shop\Order;
use Illuminate\Support\Facades\DB;

class UploadPaymentProofService
{
    private $filePath;

    public function __construct(
        private Order $order,
        private BankAccount $bankAccount,
        private $file,
    ) {
        $this->filePath = "orders/{$this->order->id}/payment";
    }

    public function perform()
    {
        try {
            DB::beginTransaction();

            $this->saveOrderPayment();
            $this->updateOrder();

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function saveOrderPayment()
    {
        $payment = $this->order->payment;

        $payment->update([
            'bank_account_id'   => $this->bankAccount->id,
            'proof_uploaded_at' => now(),
            'proof_of_payment'  => $this->uploadProof(),
            'payment_properties' => json_encode([
                'bank_name'       => $this->bankAccount->bank_name,
                'account_name'    => $this->bankAccount->account_name,
                'account_number'  => $this->bankAccount->account_number,
                'branch'          => $this->bankAccount->branch,
                'transfer_amount' => $this->order->grandtotal,
            ])
        ]);
    }

    private function updateOrder()
    {
        $this->order->update([
            'status' => OrderStatus::WaitingConfirmation
        ]);
    }

    private function uploadProof()
    {
        return $this->file->store($this->filePath, 'public');
    }
}
