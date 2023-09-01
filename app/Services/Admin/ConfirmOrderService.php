<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\OrderStatus;
use App\Jobs\ConfirmOrderJob;
use App\Models\Shop\Order;
use App\Jobs\RecalculateOrderCountJob;
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

        $this->order->update([
            'status'      => OrderStatus::Paid(),
            'paid_at'     => now(),
            'approved_by' => $this->user->name
        ]);

        ConfirmOrderJob::dispatch(
            order_resource_id: $this->order->resource_id,
            user_token: $this->user->authorization_token,
            transfer_to: $this->order->transfer_to
        );

        RecalculateOrderCountJob::dispatch($this->order->id);

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

        if (!$this->order->statusWaitingConfirmation()) {
            $this->errors = [
                'Order is not waiting confirmation'
            ];

            return false;
        }

        return true;
    }
}
