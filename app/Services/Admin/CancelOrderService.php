<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use App\Services\Backoffice\OrderService;

class CancelOrderService
{
    public $errors;

    public function __construct(
        public Order $order,
        public User $user
    ) {
    }

    public function execute(): bool
    {
        if (!$this->valid()) {
            return false;
        }

        if (empty($this->user->authorization_token)) {
            $this->errors = [
                'User doesnt have authorization token'
            ];

            return false;
        }

        $service = new OrderService($this->user->authorization_token, $this->order->resource_id);

        if (!$service->cancelByAdmin()) {
            $this->errors = $service->errors;
            return false;
        }

        $this->order->update([
            'status'      => OrderStatus::Canceled(),
            'canceled_at' => now(),
            'notes'       => "Dibatalkan oleh {$this->user->name}"
        ]);

        return true;
    }

    public function valid(): bool
    {
        return $this->order->admin_cancelable === true;
    }
}
