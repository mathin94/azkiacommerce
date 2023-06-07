<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\Sales;

class OrderService extends ApiService
{
    private $sales, $token;

    public $errors;

    public function __construct($token, $sales_id = null)
    {
        if ($sales_id) {
            $this->sales = Sales::find($sales_id);
        }

        $this->token = $token;
    }

    public function cancel()
    {
        if (empty($this->sales)) {
            return false;
        }

        $request = $this->request()
            ->withToken($this->token)
            ->post("/customer/orders/{$this->sales->uuid}/cancel");

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        return $request->json();
    }

    public function rollback()
    {
        if (empty($this->sales)) {
            return false;
        }

        $request = $this->request()
            ->withToken($this->token)
            ->post("/customer/orders/{$this->sales->uuid}/rollback");

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        return $request->json();
    }

    public function createOrder($data)
    {
        $request = $this->request()
            ->withToken($this->token)
            ->post("/customer/orders", $data);

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        return $request->json();
    }
}
