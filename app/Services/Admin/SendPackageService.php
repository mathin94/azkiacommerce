<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\OrderStatus;
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

        $service = new OrderService(
            $this->user->authorization_token,
            $this->order->resource_id
        );

        if (!$service->updateReceiptNumber($this->receipt_number)) {
            $this->errors = $service->errors;
            return false;
        }

        $this->order->update([
            'status' => OrderStatus::PackageSent(),
        ]);

        $this->order->shipping->update([
            'receipt_number' => $this->receipt_number
        ]);

        return true;
    }

    public function valid()
    {
        return $this->order->status == OrderStatus::Paid();
    }
}
