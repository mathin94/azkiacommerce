<?php

namespace App\Services;

use App\Models\Shop\Customer;
use App\Services\Backoffice\ApiService;

class ResetPasswordService extends ApiService
{
    public $errors;

    private $customer;

    public function __construct(
        private readonly string $email,
        private readonly string $token,
        private readonly string $password
    ) {
        $this->setCustomer($email);
    }

    public function perform()
    {
        if ($this->resetResource()) {
            $this->resetLocal();

            return true;
        }

        return false;
    }

    private function resetResource(): bool
    {
        $endpoint = "/password/reset?token=$this->token&email=$this->email";

        $request = $this->request()->post($endpoint, [
            'email'     => $this->email,
            'password'  => $this->password,
            'password_confirmation' => $this->password
        ]);

        if ($request->failed()) {
            $this->errors = $request->json();
            return false;
        }

        return true;
    }

    private function resetLocal(): void
    {
        if ($this->customer) {
            $this->customer->update([
                'password' => bcrypt($this->password)
            ]);
        }
    }

    private function setCustomer($email)
    {
        $this->customer = Customer::whereEmail($email)->first();
    }
}
