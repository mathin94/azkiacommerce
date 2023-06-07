<?php

namespace App\Services\Shop;

use App\Models\Shop\Order;
use App\Services\Backoffice\OrderService;

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

        $service = new OrderService(
            sales_id: $this->order->resource_id,
            token: $this->customer->authorization_token
        );

        if (!$service->cancel()) {
            return false;
        }

        $this->order->cancel();

        return true;
    }
}
