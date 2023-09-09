<?php

namespace App\Services\Shop;

use App\Jobs\CancelOrderJob;
use App\Models\Shop\Order;

class CancelOrderService
{
    protected $customer;

    public $errors;

    public function __construct(
        private Order $order
    ) {
        $this->customer = $order->customer;
    }

    public function perform()
    {
        if (empty($this->customer->authorization_token)) {
            $this->errors = [
                'User doesnt have authorization token'
            ];

            return false;
        }

        $this->order->cancel();

        CancelOrderJob::dispatch(
            order_resource_id: $this->order->resource_id,
            user_token: $this->customer->authorization_token
        );

        return true;
    }
}
