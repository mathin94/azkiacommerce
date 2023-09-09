<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\OrderStatus;
use App\Jobs\UpdateReceiptJob;
use App\Models\Shop\Order;
use App\Services\Backoffice\OrderService;

class SendPackageService
{
    public $errors, $receipt_number;

    public function __construct(
        public Order $order,
        public User $user,
        $receipt_number,
    ) {
        $this->receipt_number = $receipt_number;
    }

    public function execute()
    {
        if (!$this->valid()) {
            return false;
        }

        $this->order->update([
            'status' => OrderStatus::PackageSent(),
        ]);

        $this->order->shipping->update([
            'receipt_number' => $this->receipt_number
        ]);

        UpdateReceiptJob::dispatch(
            order_resource_id: $this->order->resource_id,
            user_token: $this->user->authorization_token,
            receipt_number: $this->receipt_number
        );

        return true;
    }

    public function valid()
    {
        return $this->order->status == OrderStatus::Paid();
    }
}
