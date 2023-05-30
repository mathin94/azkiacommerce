<?php

namespace App\Services;

use App\Models\Shop\Customer;
use Illuminate\Support\Facades\Http;

class UpdateCustomerService
{
    public Customer $customer, $errors;
    private $baseUrl;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->baseUrl = config('app.backoffice_api_url');
    }

    public function updateProfile(
        string $name,
        string $email,
        string $phone,
        string $gender,
    ) {
        if (!$this->updateCustomerProfileAPI($name, $email, $phone, $gender)) {
            return false;
        }

        $this->customer->name   = $name;
        $this->customer->email  = $email;
        $this->customer->phone  = $phone;
        $this->customer->gender = $gender;
        $this->customer->save();
    }

    private function updateCustomerProfileAPI(
        string $name,
        string $email,
        string $phone,
        string $gender,
    ) {
        $endpoint = '/customer/profile';

        $request = Http::baseUrl($this->baseUrl)
            ->withToken($this->customer->authorization_token)
            ->withOptions([
                'verify' => false
            ])
            ->put($endpoint, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender
            ]);

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        $response = $request->json();

        return true;
    }
}
