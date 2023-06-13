<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use App\Services\Backoffice\OrderService;

class ConfirmOrderService
{
    public $errors, $bankAccount, $payment_properties, $transfer_to;

    public function __construct(
        public Order $order,
        public User $user,
    ) {
    }

    public function execute(): bool
    {
        if (!$this->valid()) {
            return false;
        }

        $service = new OrderService(
            $this->user->authorization_token,
            $this->order->resource_id
        );

        if (!$service->confirmPayment($this->order->transfer_to)) {
            $this->errors = $service->errors;
            return false;
        }

        $this->order->update([
            'status'      => OrderStatus::Paid(),
            'paid_at'     => now(),
            'approved_by' => $this->user->name
        ]);

        return true;
    }

    public function valid()
    {
        if (empty($this->user->authorization_token)) {
            $this->errors = [
                'User doesnt have authorization token'
            ];

            return false;
        }

        if ($this->order->status != OrderStatus::WaitingConfirmation()) {
            $this->errors = [
                'Order is not waiting confirmation'
            ];

            return false;
        }

        return true;
    }
}
