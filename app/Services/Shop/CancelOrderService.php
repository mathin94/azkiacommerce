<?php

namespace App\Services\Shop;

use App\Jobs\CancelOrderJob;
use App\Models\Shop\Order;

class CancelOrderService
{
    protected $customer;

    public $errors;

    public function __construct(
        private Order $order,
        private $dispatch_job = true,
    ) {
        $this->customer = $order->customer;
    }

    public function perform()
    {
        if (empty($this->customer->authorization_token) && $this->dispatch_job) {
            $this->errors = [
                'User doesnt have authorization token'
            ];

            return false;
        }

        $this->order->cancel();

        if ($this->dispatch_job) {
            CancelOrderJob::dispatch(
                order_resource_id: $this->order->resource_id,
                user_token: $this->customer->authorization_token
            );
        }

        return true;
    }
}
